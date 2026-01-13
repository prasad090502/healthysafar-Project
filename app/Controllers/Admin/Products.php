<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use Config\Database;

class Products extends BaseController
{
    protected ProductModel $products;
    protected int $perPage = 12;

    public function __construct()
    {
        $this->products = new ProductModel();
    }

    public function index()
    {
        $request = $this->request;

        $category = $request->getGet('category');
        $status   = $request->getGet('status');      // in_stock/out_of_stock/preorder/all
        $active   = $request->getGet('active');      // 1/0/all
        $search   = $request->getGet('q');
        $sort     = $request->getGet('sort') ?: 'created_desc';

        // Base builder with filters
        $builder = $this->products;

        if (!empty($category) && $category !== 'all') {
            $builder = $builder->where('category', $category);
        }

        if (!empty($status) && $status !== 'all') {
            $builder = $builder->where('stock_status', $status);
        }

        if ($active === '1') {
            $builder = $builder->where('is_active', 1);
        } elseif ($active === '0') {
            $builder = $builder->where('is_active', 0);
        }

        if (!empty($search)) {
            $builder = $builder->groupStart()
                ->like('name', $search)
                ->orLike('sku', $search)
                ->orLike('category', $search)
                ->groupEnd();
        }

        // Sorting
        switch ($sort) {
            case 'price_asc':
                $builder = $builder->orderBy('price', 'ASC');
                break;
            case 'price_desc':
                $builder = $builder->orderBy('price', 'DESC');
                break;
            case 'name_asc':
                $builder = $builder->orderBy('name', 'ASC');
                break;
            case 'name_desc':
                $builder = $builder->orderBy('name', 'DESC');
                break;
            case 'stock_low':
                $builder = $builder->orderBy('stock_quantity', 'ASC');
                break;
            default:
                $builder = $builder->orderBy('created_at', 'DESC');
        }

        // Paginated products list
        $products = $builder->paginate($this->perPage);
        $pager    = $this->products->pager;

        // Stats (fresh models so filters don’t leak)
        $totalProducts = (new ProductModel())->countAll();
        $activeCount   = (new ProductModel())->where('is_active', 1)->countAllResults();
        $outOfStock    = (new ProductModel())
            ->where('stock_status', 'out_of_stock')
            ->countAllResults();
        $lowStock      = (new ProductModel())
            ->where('stock_status', 'in_stock')
            ->where('stock_quantity <', 5)
            ->countAllResults();

        // Category list for filter — DISTINCT fix
        $categories = (new ProductModel())
            ->distinct()
            ->select('category')
            ->where('category !=', '')
            ->orderBy('category', 'ASC')
            ->findAll();

        $data = [
            'title'    => 'Products',
            'products' => $products,
            'pager'    => $pager,
            'filters'  => [
                'category' => $category ?: 'all',
                'status'   => $status ?: 'all',
                'active'   => $active ?? 'all',
                'q'        => $search,
                'sort'     => $sort,
            ],
            'categories' => $categories,
            'stats'      => [
                'total' => $totalProducts,
                'active'=> $activeCount,
                'oos'   => $outOfStock,
                'low'   => $lowStock,
            ],
        ];

        return view('admin/products/index', $data);
    }

    public function create()
    {
        $data = [
            'title'   => 'Add Product',
            'mode'    => 'create',
            'product' => null,
            'errors'  => session()->getFlashdata('errors') ?? [],
        ];

        return view('admin/products/create', $data);
    }

    public function store()
    {
        helper('text'); // for url_title

        $rules = [
            'name'        => 'required|min_length[3]',
            'sku'         => 'permit_empty|min_length[2]|is_unique[products.sku]',
            'category'    => 'required',
            'price'       => 'required|decimal',
            'stock_status'=> 'required|in_list[in_stock,out_of_stock,preorder]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $input = $this->request->getPost();

        // Auto-generate SKU if empty
        if (empty($input['sku'] ?? '')) {
            $input['sku'] = $this->generateSku($input['name'] ?? 'PRODUCT');
        }

        $slug = url_title($input['name'], '-', true);

        // Images – ensure /public/uploads/products exists and is writable
        $mainImage   = $this->handleUpload('main_image');
        $thumbImage  = $this->handleUpload('thumbnail_image');
        $galleryFiles = $this->handleMultipleUpload('gallery_images');
        $galleryJson  = $galleryFiles ? json_encode($galleryFiles) : null;

        // Flags
        $isActive   = isset($input['is_active']) ? 1 : 0;
        $isFeatured = isset($input['is_featured']) ? 1 : 0;
        $subAvail   = isset($input['subscription_available']) ? 1 : 0;
        $isVeg      = isset($input['is_veg']) ? 1 : 0;

        $seoTitle = $input['seo_title'] ?? '';
        $seoDesc  = $input['seo_description'] ?? '';

        $data = [
            'name'              => $input['name'],
            'slug'              => $slug,
            'short_description' => $input['short_description'] ?? null,
            'long_description'  => $input['long_description'] ?? null,

            'price'             => $input['price'],
            'sale_price'        => $input['sale_price'] ?: null,
            'currency_code'     => 'INR',

            'sku'               => $input['sku'],
            'category'          => $input['category'],
            'tags'              => $input['tags'] ?? null,

            'stock_status'      => $input['stock_status'],
            'stock_quantity'    => $input['stock_quantity'] ?: 0,

            'main_image'        => $mainImage,
            'thumbnail_image'   => $thumbImage ?: $mainImage,
            'gallery_images'    => $galleryJson,

            'serving_size'      => $input['serving_size'] ?? null,
            'calories_kcal'     => $input['calories_kcal'] ?: 0,
            'protein_g'         => $input['protein_g'] ?: 0,
            'carbs_g'           => $input['carbs_g'] ?: 0,
            'fat_g'             => $input['fat_g'] ?: 0,
            'fibre_g'           => $input['fibre_g'] ?: 0,
            'sugar_g'           => $input['sugar_g'] ?: 0,
            'sodium_mg'         => $input['sodium_mg'] ?: 0,

            'is_active'         => $isActive,
            'is_featured'       => $isFeatured,
            'subscription_available' => $subAvail,
            'prep_time_minutes' => $input['prep_time_minutes'] ?: null,
            'is_veg'            => $isVeg,
            'max_per_order'     => $input['max_per_order'] ?: null,

            'seo_title'         => $seoTitle !== '' ? $seoTitle : ($input['name'] . ' | HealthySafar'),
            'seo_description'   => $seoDesc !== '' ? $seoDesc : ($input['short_description'] ?? null),
        ];

        $this->products->insert($data);

        return redirect()->to('admin/products')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = $this->products->find($id);
        if (! $product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Product not found');
        }

        // Decode gallery JSON for thumbnails
        if (!empty($product['gallery_images'])) {
            $product['gallery_images_array'] = json_decode($product['gallery_images'], true) ?: [];
        } else {
            $product['gallery_images_array'] = [];
        }

        $data = [
            'title'   => 'Edit Product',
            'mode'    => 'edit',
            'product' => $product,
            'errors'  => session()->getFlashdata('errors') ?? [],
        ];

        return view('admin/products/edit', $data);
    }

    public function update($id)
    {
        helper('text');

        $product = $this->products->find($id);
        if (! $product) {
            return redirect()->to('admin/products')->with('error', 'Product not found.');
        }

        $rules = [
            'name'        => 'required|min_length[3]',
            'sku'         => 'permit_empty|min_length[2]|is_unique[products.sku,id,' . $id . ']',
            'category'    => 'required',
            'price'       => 'required|decimal',
            'stock_status'=> 'required|in_list[in_stock,out_of_stock,preorder]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $input = $this->request->getPost();

        // Auto-SKU if empty
        if (empty($input['sku'] ?? '')) {
            $input['sku'] = $this->generateSku($input['name'] ?? 'PRODUCT');
        }

        $slug = url_title($input['name'], '-', true);

        // Images (keep old if no new upload)
        $mainImage  = $this->handleUpload('main_image', $product['main_image']);
        $thumbImage = $this->handleUpload('thumbnail_image', $product['thumbnail_image']);

        // Merge gallery
        $existingGallery = [];
        if (!empty($product['gallery_images'])) {
            $existingGallery = json_decode($product['gallery_images'], true) ?: [];
        }
        $newGalleryFiles = $this->handleMultipleUpload('gallery_images');
        $galleryMerged   = array_merge($existingGallery, $newGalleryFiles ?: []);
        $galleryJson     = $galleryMerged ? json_encode($galleryMerged) : null;

        $isActive   = isset($input['is_active']) ? 1 : 0;
        $isFeatured = isset($input['is_featured']) ? 1 : 0;
        $subAvail   = isset($input['subscription_available']) ? 1 : 0;
        $isVeg      = isset($input['is_veg']) ? 1 : 0;

        $seoTitle = $input['seo_title'] ?? '';
        $seoDesc  = $input['seo_description'] ?? '';

        $data = [
            'name'              => $input['name'],
            'slug'              => $slug,
            'short_description' => $input['short_description'] ?? null,
            'long_description'  => $input['long_description'] ?? null,

            'price'             => $input['price'],
            'sale_price'        => $input['sale_price'] ?: null,

            'sku'               => $input['sku'],
            'category'          => $input['category'],
            'tags'              => $input['tags'] ?? null,

            'stock_status'      => $input['stock_status'],
            'stock_quantity'    => $input['stock_quantity'] ?: 0,

            'main_image'        => $mainImage,
            'thumbnail_image'   => $thumbImage ?: $mainImage,
            'gallery_images'    => $galleryJson,

            'serving_size'      => $input['serving_size'] ?? null,
            'calories_kcal'     => $input['calories_kcal'] ?: 0,
            'protein_g'         => $input['protein_g'] ?: 0,
            'carbs_g'           => $input['carbs_g'] ?: 0,
            'fat_g'             => $input['fat_g'] ?: 0,
            'fibre_g'           => $input['fibre_g'] ?: 0,
            'sugar_g'           => $input['sugar_g'] ?: 0,
            'sodium_mg'         => $input['sodium_mg'] ?: 0,

            'is_active'         => $isActive,
            'is_featured'       => $isFeatured,
            'subscription_available' => $subAvail,
            'prep_time_minutes' => $input['prep_time_minutes'] ?: null,
            'is_veg'            => $isVeg,
            'max_per_order'     => $input['max_per_order'] ?: null,

            'seo_title'         => $seoTitle !== '' ? $seoTitle : ($input['name'] . ' | HealthySafar'),
            'seo_description'   => $seoDesc !== '' ? $seoDesc : ($input['short_description'] ?? null),
        ];

        $this->products->update($id, $data);

        return redirect()->to('admin/products')->with('success', 'Product updated successfully.');
    }

    public function delete($id)
    {
        $product = $this->products->find($id);
        if ($product) {
            $this->products->delete($id);
        }

        return redirect()->to('admin/products')->with('success', 'Product deleted.');
    }

    public function toggleStatus($id)
    {
        $product = $this->products->find($id);
        if ($product) {
            $this->products->update($id, ['is_active' => $product['is_active'] ? 0 : 1]);
        }

        return redirect()->to('admin/products');
    }

    /**
     * Bulk actions: activate, deactivate, delete
     */
    public function bulkAction()
    {
        $ids    = $this->request->getPost('ids') ?? [];
        $action = $this->request->getPost('action');

        if (empty($ids) || ! $action) {
            return redirect()->back()->with('error', 'No products selected.');
        }

        switch ($action) {
            case 'activate':
                $this->products->whereIn('id', $ids)->set(['is_active' => 1])->update();
                $msg = 'Selected products activated.';
                break;
            case 'deactivate':
                $this->products->whereIn('id', $ids)->set(['is_active' => 0])->update();
                $msg = 'Selected products deactivated.';
                break;
            case 'delete':
                $this->products->whereIn('id', $ids)->delete();
                $msg = 'Selected products deleted.';
                break;
            default:
                $msg = 'Invalid action.';
        }

        return redirect()->to('admin/products')->with('success', $msg);
    }

    // ---------- Helpers ----------

    protected function handleUpload(string $field, ?string $existing = null): ?string
    {
        $file = $this->request->getFile($field);
        if (! $file || ! $file->isValid()) {
            return $existing;
        }

        $uploadPath = FCPATH . 'uploads/products';
        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);

        return 'uploads/products/' . $newName;
    }

    protected function handleMultipleUpload(string $field): ?array
    {
        $files = $this->request->getFiles();

        if (! isset($files[$field])) {
            return null;
        }

        $uploadPath = FCPATH . 'uploads/products';
        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        $saved = [];

        foreach ($files[$field] as $file) {
            if (! $file->isValid()) {
                continue;
            }
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $saved[] = 'uploads/products/' . $newName;
        }

        return $saved ?: null;
    }

    protected function generateSku(string $name): string
    {
        // Base from name: first 8 chars, uppercase, alphanumeric only
        $base = strtoupper(preg_replace('/[^A-Z0-9]/', '', substr($name, 0, 8)));
        if ($base === '') {
            $base = 'HS';
        }

        $model     = new ProductModel();
        $candidate = $base;
        $i         = 1;

        while (true) {
            $existing = $model->where('sku', $candidate)->first();
            if (! $existing) {
                break;
            }
            $candidate = $base . '-' . $i;
            $i++;
        }

        return $candidate;
    }

    protected function db()
    {
        return Database::connect();
    }
}