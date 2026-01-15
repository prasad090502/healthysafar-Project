<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;
use App\Models\SubscriptionPlanModel;
use App\Models\SubscriptionPlanNutritionModel;
use App\Models\SubscriptionPlanConfigModel;
use App\Models\SubscriptionPlanChoiceModel;
use App\Models\MenuModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class SubscriptionPlans extends AdminBaseController
{
    protected SubscriptionPlanModel $plans;
    protected SubscriptionPlanNutritionModel $nutrition;
    protected SubscriptionPlanConfigModel $config;
    protected SubscriptionPlanChoiceModel $choices;
    protected MenuModel $menuModel;

    protected int $perPage = 10;

    public function __construct()
    {
        $this->plans     = new SubscriptionPlanModel();
        $this->nutrition = new SubscriptionPlanNutritionModel();
        $this->config    = new SubscriptionPlanConfigModel();
        $this->choices   = new SubscriptionPlanChoiceModel();
        $this->menuModel = new MenuModel();
    }

    public function index()
    {
        $q = trim((string) $this->request->getGet('q'));

        $builder = $this->plans
            ->select('id,title,slug,base_price,pricing_type,menu_mode,choice_per_day_limit,is_active,sort_order,created_at')
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'DESC');

        if ($q !== '') {
            $builder->groupStart()
                ->like('title', $q)
                ->orLike('slug', $q)
                ->groupEnd();
        }

        $plans = $builder->paginate($this->perPage);

        // Fetch menus for display in upcoming deliveries section
        $menus = $this->menuModel->findAll();

        return view('admin/subscription_plans/index', [
            'plans'      => $plans,
            'pager'      => $this->plans->pager,
            'searchTerm' => $q,
            'menus'      => $menus,
        ]);
    }

    public function create()
    {
        // Existing plans list for "Add Existing Subscription" selector
        $existingSubs = $this->plans
            ->select('id,title,short_description,thumbnail_url')
            ->where('is_active', 1)
            ->orderBy('title', 'ASC')
            ->findAll(1000);

        return view('admin/subscription_plans/form', [
            'mode'       => 'create',
            'plan'       => [
                'menu_mode'            => 'fixed',
                'choice_per_day_limit' => 1,
                'pricing_type'         => 'per_package',
                'is_active'            => 1,
                'sort_order'           => 0,
            ],
            'nutrition'    => [],
            'configRow'    => [],
            'durations'    => [],
            'slots'        => [],
            'offDays'      => [],
            'priceMap'     => [],
            'choices'      => [],         // none yet
            'existingSubs' => $existingSubs,
        ]);
    }

    public function store()
    {
        $request = $this->request;

        // Normalize slug
        $slug = trim((string)$request->getPost('slug'));
        $slug = strtolower($slug);
        $slug = preg_replace('/\s+/', '-', $slug ?? '');
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug ?? '');
        $slug = preg_replace('/-+/', '-', $slug ?? '');
        $slug = trim($slug, '-');
        $_POST['slug'] = $slug;

        $rules = [
            'title'        => 'required|min_length[3]',
            'slug'         => 'required|min_length[3]|is_unique[subscription_plans.slug]',
            'base_price'   => 'required|numeric',
            'pricing_type' => 'required|in_list[per_day,per_package]',
            'menu_mode'            => 'permit_empty|in_list[fixed,choice]',
            'choice_per_day_limit' => 'permit_empty|integer|greater_than_equal_to[1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Upload directory
        $uploadDir = FCPATH . 'uploads/subscriptions';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        // Thumbnail path
        $thumbnailPath = $request->getPost('thumbnail_url') ?: null;
        $thumbFile     = $request->getFile('thumbnail_file');
        if ($thumbFile && $thumbFile->isValid() && !$thumbFile->hasMoved()) {
            $newName = $thumbFile->getRandomName();
            $thumbFile->move($uploadDir, $newName);
            $thumbnailPath = 'uploads/subscriptions/' . $newName;
        }

        // Banner path
        $bannerPath = $request->getPost('banner_url') ?: null;
        $bannerFile = $request->getFile('banner_file');
        if ($bannerFile && $bannerFile->isValid() && !$bannerFile->hasMoved()) {
            $newName = $bannerFile->getRandomName();
            $bannerFile->move($uploadDir, $newName);
            $bannerPath = 'uploads/subscriptions/' . $newName;
        }

        $menuMode          = $request->getPost('menu_mode') ?: 'fixed';
        $choicePerDayLimit = (int)($request->getPost('choice_per_day_limit') ?: 1);

        $data = [
            'title'             => trim((string)$request->getPost('title')),
            'slug'              => $slug,
            'short_description' => $request->getPost('short_description'),
            'long_description'  => $request->getPost('long_description'),
            'thumbnail_url'     => $thumbnailPath,
            'banner_url'        => $bannerPath,
            'base_price'        => (float)$request->getPost('base_price'),
            'pricing_type'      => $request->getPost('pricing_type'),
            'menu_mode'         => $menuMode,
            'choice_per_day_limit' => max(1, $choicePerDayLimit),
            'is_active'         => $request->getPost('is_active') ? 1 : 0,
            'sort_order'        => (int)$request->getPost('sort_order'),
        ];

        $this->plans->transStart();

        $planId = $this->plans->insert($data, true);

        // Nutrition
        $nutData = [
            'subscription_plan_id' => $planId,
            'calories_kcal'        => $request->getPost('calories_kcal') !== '' ? (int)$request->getPost('calories_kcal') : null,
            'protein_g'            => $request->getPost('protein_g') !== '' ? (float)$request->getPost('protein_g') : null,
            'carbs_g'              => $request->getPost('carbs_g') !== '' ? (float)$request->getPost('carbs_g') : null,
            'fats_g'               => $request->getPost('fats_g') !== '' ? (float)$request->getPost('fats_g') : null,
            'fibre_g'              => $request->getPost('fibre_g') !== '' ? (float)$request->getPost('fibre_g') : null,
            'sugar_g'              => $request->getPost('sugar_g') !== '' ? (float)$request->getPost('sugar_g') : null,
            'sodium_mg'            => $request->getPost('sodium_mg') !== '' ? (int)$request->getPost('sodium_mg') : null,
            'notes'                => $request->getPost('nutrition_notes'),
        ];
        $this->nutrition->insert($nutData);

        // Config save
        $this->saveConfigForPlan($planId);

        // Choice pool save
        if ($menuMode === 'choice') {
            $this->saveChoicesForPlan($planId);
        }

        $this->plans->transComplete();

        if ($this->plans->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to create subscription plan.');
        }

        return redirect()->to(site_url('admin/subscription-plans'))
            ->with('success', 'Subscription plan created successfully.');
    }

    public function edit(int $id)
    {
        $plan = $this->plans->find($id);
        if (!$plan) throw PageNotFoundException::forPageNotFound();

        $nutrition = $this->nutrition->getByPlanId($id);
        $configRow = $this->config->getByPlanId($id);

        $durations = [];
        $slots     = [];
        $offDays   = [];
        $priceMap  = [];

        if (!empty($configRow['duration_options_json'])) $durations = json_decode($configRow['duration_options_json'], true) ?: [];
        if (!empty($configRow['delivery_slots_json']))   $slots     = json_decode($configRow['delivery_slots_json'], true) ?: [];
        if (!empty($configRow['off_days_json']))         $offDays   = json_decode($configRow['off_days_json'], true) ?: [];
        if (!empty($configRow['duration_pricing_json'])) $priceMap  = json_decode($configRow['duration_pricing_json'], true) ?: [];

        // Existing plans list for selector
        $existingSubs = $this->plans
            ->select('id,title,short_description,thumbnail_url')
            ->where('is_active', 1)
            ->orderBy('title', 'ASC')
            ->findAll(1000);

        // Load choices from DB
        $choices = $this->choices
            ->where('subscription_plan_id', $id)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        return view('admin/subscription_plans/form', [
            'mode'         => 'edit',
            'plan'         => $plan,
            'nutrition'    => $nutrition ?: [],
            'configRow'    => $configRow ?: [],
            'durations'    => $durations,
            'slots'        => $slots,
            'offDays'      => $offDays,
            'priceMap'     => $priceMap,
            'choices'      => $choices,
            'existingSubs' => $existingSubs,
        ]);
    }

    public function update(int $id)
    {
        $request = $this->request;
        $plan    = $this->plans->find($id);
        if (!$plan) throw PageNotFoundException::forPageNotFound();

        // Normalize slug
        $slug = trim((string)$request->getPost('slug'));
        $slug = strtolower($slug);
        $slug = preg_replace('/\s+/', '-', $slug ?? '');
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug ?? '');
        $slug = preg_replace('/-+/', '-', $slug ?? '');
        $slug = trim($slug, '-');
        $_POST['slug'] = $slug;

        $rules = [
            'title'        => 'required|min_length[3]',
            'slug'         => "required|min_length[3]|is_unique[subscription_plans.slug,id,{$id}]",
            'base_price'   => 'required|numeric',
            'pricing_type' => 'required|in_list[per_day,per_package]',
            'menu_mode'            => 'permit_empty|in_list[fixed,choice]',
            'choice_per_day_limit' => 'permit_empty|integer|greater_than_equal_to[1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $uploadDir = FCPATH . 'uploads/subscriptions';
        if (!is_dir($uploadDir)) @mkdir($uploadDir, 0755, true);

        // Thumbnail
        $thumbnailPath = $plan['thumbnail_url'] ?? null;
        if ($request->getPost('thumbnail_url')) $thumbnailPath = $request->getPost('thumbnail_url');
        $thumbFile = $request->getFile('thumbnail_file');
        if ($thumbFile && $thumbFile->isValid() && !$thumbFile->hasMoved()) {
            $newName = $thumbFile->getRandomName();
            $thumbFile->move($uploadDir, $newName);
            $thumbnailPath = 'uploads/subscriptions/' . $newName;
        }

        // Banner
        $bannerPath = $plan['banner_url'] ?? null;
        if ($request->getPost('banner_url')) $bannerPath = $request->getPost('banner_url');
        $bannerFile = $request->getFile('banner_file');
        if ($bannerFile && $bannerFile->isValid() && !$bannerFile->hasMoved()) {
            $newName = $bannerFile->getRandomName();
            $bannerFile->move($uploadDir, $newName);
            $bannerPath = 'uploads/subscriptions/' . $newName;
        }

        $menuMode          = $request->getPost('menu_mode') ?: ($plan['menu_mode'] ?? 'fixed');
        $choicePerDayLimit = (int)($request->getPost('choice_per_day_limit') ?: ($plan['choice_per_day_limit'] ?? 1));

        $data = [
            'title'             => trim((string)$request->getPost('title')),
            'slug'              => $slug,
            'short_description' => $request->getPost('short_description'),
            'long_description'  => $request->getPost('long_description'),
            'thumbnail_url'     => $thumbnailPath,
            'banner_url'        => $bannerPath,
            'base_price'        => (float)$request->getPost('base_price'),
            'pricing_type'      => $request->getPost('pricing_type'),
            'menu_mode'         => $menuMode,
            'choice_per_day_limit' => max(1, $choicePerDayLimit),
            'is_active'         => $request->getPost('is_active') ? 1 : 0,
            'sort_order'        => (int)$request->getPost('sort_order'),
        ];

        $this->plans->transStart();

        $this->plans->update($id, $data);

        // Nutrition upsert
        $nutData = [
            'subscription_plan_id' => $id,
            'calories_kcal'        => $request->getPost('calories_kcal') !== '' ? (int)$request->getPost('calories_kcal') : null,
            'protein_g'            => $request->getPost('protein_g') !== '' ? (float)$request->getPost('protein_g') : null,
            'carbs_g'              => $request->getPost('carbs_g') !== '' ? (float)$request->getPost('carbs_g') : null,
            'fats_g'               => $request->getPost('fats_g') !== '' ? (float)$request->getPost('fats_g') : null,
            'fibre_g'              => $request->getPost('fibre_g') !== '' ? (float)$request->getPost('fibre_g') : null,
            'sugar_g'              => $request->getPost('sugar_g') !== '' ? (float)$request->getPost('sugar_g') : null,
            'sodium_mg'            => $request->getPost('sodium_mg') !== '' ? (int)$request->getPost('sodium_mg') : null,
            'notes'                => $request->getPost('nutrition_notes'),
        ];

        $existingNut = $this->nutrition->getByPlanId($id);
        if ($existingNut && !empty($existingNut['id'])) {
            $this->nutrition->update((int)$existingNut['id'], $nutData);
        } else {
            $this->nutrition->insert($nutData);
        }

        // Config update
        $this->saveConfigForPlan($id);

        // Choice pool update
        if ($menuMode === 'choice') {
            $this->saveChoicesForPlan($id);
        } else {
            // if switched to fixed, remove all choices
            $this->choices->where('subscription_plan_id', $id)->delete();
        }

        $this->plans->transComplete();

        if ($this->plans->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to update subscription plan.');
        }

        return redirect()->to(site_url('admin/subscription-plans'))
            ->with('success', 'Subscription plan updated successfully.');
    }

   public function delete(int $id)
{
    $plan = $this->plans->find($id);
    if (!$plan) throw PageNotFoundException::forPageNotFound();

    $db = db_connect();

    // Check if any subscriptions exist for this plan
    $subCount = $db->table('subscriptions')
        ->where('subscription_plan_id', $id)
        ->countAllResults();

    // If subscriptions exist -> don't delete. Just deactivate.
    if ($subCount > 0) {
        $this->plans->update($id, ['is_active' => 0]);

        return redirect()->to(site_url('admin/subscription-plans'))
            ->with('warning', "This plan has {$subCount} subscription(s), so it cannot be deleted. It has been deactivated instead.");
    }

    // No subscriptions -> safe to hard delete (delete children first)
    $this->plans->transStart();

    $this->choices->where('subscription_plan_id', $id)->delete();
    $db->table('subscription_plan_config')->where('subscription_plan_id', $id)->delete();
    $db->table('subscription_plan_nutrition')->where('subscription_plan_id', $id)->delete();

    $this->plans->delete($id);

    $this->plans->transComplete();

    if ($this->plans->transStatus() === false) {
        return redirect()->to(site_url('admin/subscription-plans'))
            ->with('error', 'Failed to delete subscription plan.');
    }

    return redirect()->to(site_url('admin/subscription-plans'))
        ->with('success', 'Subscription plan deleted.');
}


    /**
     * Save config JSON fields (shared by store/update)
     */
    private function saveConfigForPlan(int $planId): void
    {
        $request = $this->request;

        $durations    = $request->getPost('duration_options') ?? [];
        $slotKeys     = $request->getPost('slot_key') ?? [];
        $slotLabels   = $request->getPost('slot_label') ?? [];
        $slotWindows  = $request->getPost('slot_window') ?? [];
        $offDays      = $request->getPost('off_days') ?? [];
        $priceDays    = $request->getPost('price_days') ?? [];
        $priceAmounts = $request->getPost('price_amounts') ?? [];

        $durationsClean = [];
        foreach ((array)$durations as $d) {
            $d = (int)$d;
            if ($d > 0) $durationsClean[] = $d;
        }
        $durationsClean = array_values(array_unique($durationsClean));

        $slots = [];
        foreach ((array)$slotKeys as $idx => $key) {
            $key   = trim((string)$key);
            $label = trim((string)($slotLabels[$idx] ?? ''));
            $win   = trim((string)($slotWindows[$idx] ?? ''));
            if ($key !== '' && $label !== '') {
                $slots[] = ['key'=>$key,'label'=>$label,'window'=>$win];
            }
        }

        $priceMap = [];
        foreach ((array)$priceDays as $idx => $d) {
            $d   = (int)$d;
            $amt = (float)($priceAmounts[$idx] ?? 0);
            if ($d > 0 && $amt > 0) $priceMap[$d] = $amt;
        }

        $configData = [
            'subscription_plan_id'  => $planId,
            'duration_options_json' => !empty($durationsClean) ? json_encode($durationsClean) : null,
            'delivery_slots_json'   => !empty($slots) ? json_encode($slots) : null,
            'off_days_json'         => !empty($offDays) ? json_encode(array_values($offDays)) : null,
            'duration_pricing_json' => !empty($priceMap) ? json_encode($priceMap) : null,
            'postponement_limit'    => (int)$request->getPost('postponement_limit'),
            'cut_off_hour'          => (int)$request->getPost('cut_off_hour'),
            'min_start_offset_days' => (int)$request->getPost('min_start_offset_days'),
        ];

        $existingConfig = $this->config->getByPlanId($planId);
        if ($existingConfig && !empty($existingConfig['id'])) {
            $this->config->update((int)$existingConfig['id'], $configData);
        } else {
            $this->config->insert($configData);
        }
    }

    /**
     * Save choice pool (upsert + delete removed)
     *
     * Expected POST arrays:
     * choice_id[], choice_ref_type[], choice_ref_id[], choice_title[], choice_description[], choice_image_url[],
     * choice_calories_kcal[], choice_sort_order[], choice_is_active[index]
     */
    private function saveChoicesForPlan(int $planId): void
    {
        $req = $this->request;

        $ids   = $req->getPost('choice_id') ?? [];
        $types = $req->getPost('choice_ref_type') ?? [];
        $refIds= $req->getPost('choice_ref_id') ?? [];
        $titles= $req->getPost('choice_title') ?? [];
        $descs = $req->getPost('choice_description') ?? [];
        $imgs  = $req->getPost('choice_image_url') ?? [];
        $cals  = $req->getPost('choice_calories_kcal') ?? [];
        $sorts = $req->getPost('choice_sort_order') ?? [];
        $active= $req->getPost('choice_is_active') ?? [];

        // existing IDs for deletion check
        $existing = $this->choices->select('id')->where('subscription_plan_id', $planId)->findAll();
        $existingIds = array_map(fn($r) => (int)$r['id'], $existing);

        $keptIds = [];

        foreach ((array)$titles as $i => $title) {
            $title = trim((string)$title);
            if ($title === '') continue;

            $rowId   = (int)($ids[$i] ?? 0);
            $refType = ($types[$i] ?? 'menu') === 'plan' ? 'plan' : 'menu';
            $refId   = (int)($refIds[$i] ?? 0);
            $desc    = (string)($descs[$i] ?? '');
            $img     = (string)($imgs[$i] ?? '');
            $cal     = ($cals[$i] ?? '') !== '' ? (float)$cals[$i] : null;
            $sort    = (int)($sorts[$i] ?? 0);
            $isAct   = isset($active[$i]) && (int)$active[$i] === 1 ? 1 : 0;

            // If ref_type=plan but ref_id missing, treat as custom
            if ($refType === 'plan' && $refId <= 0) {
                $refType = 'menu';
                $refId   = 0;
            }

            $payload = [
                'subscription_plan_id' => $planId,
                'ref_type'     => $refType,
                'ref_id'       => $refType === 'plan' ? $refId : null,
                'title'        => $title,
                'description'  => $desc,
                'image_url'    => $img,
                'calories_kcal'=> $cal,
                'sort_order'   => $sort,
                'is_active'    => $isAct,
            ];

            if ($rowId > 0) {
                $this->choices->update($rowId, $payload);
                $keptIds[] = $rowId;
            } else {
                $newId = $this->choices->insert($payload, true);
                $keptIds[] = (int)$newId;
            }
        }

        // delete removed
        $toDelete = array_diff($existingIds, $keptIds);
        if (!empty($toDelete)) {
            $this->choices->whereIn('id', array_values($toDelete))->delete();
        }
    }
}