<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Shop extends BaseController
{
    protected $session;
    protected string $cartKey = 'cart';

    public function __construct()
    {
        $this->session = session();
    }

    /* ==============================================
     * SHOP LISTING
     * URL: /shop
     * Optional GET params: ?q=search&category=Fruits&orderby=price&page=2
     * ============================================== */
    public function index()
    {
        $productModel = new ProductModel();

        // Total active products (for "Showing X of Y" etc.)
        $totalProducts = (new ProductModel())
            ->where('is_active', 1)
            ->countAllResults();

        // Fresh instance for listing query
        $productModel = new ProductModel();

        $perPage = 12;
        $page    = (int)($this->request->getGet('page') ?? 1);

        $q        = trim((string)$this->request->getGet('q'));
        $category = trim((string)$this->request->getGet('category'));
        $orderby  = trim((string)$this->request->getGet('orderby'));

        // Base query: only active products
        $builder = $productModel->where('is_active', 1);

        // Search by name or category
        if ($q !== '') {
            $builder->groupStart()
                ->like('name', $q)
                ->orLike('category', $q)
                ->groupEnd();
        }

        // Filter by category (exact match)
        if ($category !== '') {
            $builder->where('category', $category);
        }

        // Sorting like the Frutin HTML options
        switch ($orderby) {
            case 'date': // latest
                $builder->orderBy('created_at', 'DESC');
                break;
            case 'price': // low to high
                $builder->orderBy('final_price', 'ASC'); // if you have final_price
                $builder->orderBy('price', 'ASC');
                break;
            case 'price-desc': // high to low
                $builder->orderBy('final_price', 'DESC');
                $builder->orderBy('price', 'DESC');
                break;
            // 'popularity' / 'rating' can be added if your table has columns
            default:
                $builder->orderBy('created_at', 'DESC');
                break;
        }

        // Paginate
        $products = $builder->paginate($perPage, 'default', $page);
        $pager    = $productModel->pager;

        // Normalise pricing & image for each product
        foreach ($products as &$p) {
            $pPrice     = (float)($p['price'] ?? 0);
            $pSale      = (float)($p['sale_price'] ?? 0);
            if ($pSale > 0 && $pSale < $pPrice) {
                $p['final_price'] = $pSale;
            } else {
                $p['final_price'] = $pPrice;
                $p['sale_price']  = null;
            }

            $p['average_rating'] = $p['average_rating'] ?? 5.0;
            $p['rating_count']   = $p['rating_count']   ?? 1;
            $p['review_count']   = $p['review_count']   ?? $p['rating_count'];

            $img = trim((string)($p['main_image'] ?? ''));
            if ($img === '' || strtolower($img) === 'null') {
                $p['main_image'] = 'assets/img/product/product_1_1.jpg';
            }
        }
        unset($p); // break ref

        return view('shop/index', [
            'products'        => $products,
            'pager'           => $pager,
            'totalProducts'   => $totalProducts,
            'perPage'         => $perPage,
            'currentPage'     => $page,
            'q'               => $q,
            'currentCategory' => $category,
            'currentOrderby'  => $orderby,
            'cartCount'       => $this->getCartItemCount(),
        ]);
    }

    /* ==============================================
     * PRODUCT DETAILS
     * URL: /product/{slug}
     * ============================================== */
    public function details(string $slug)
    {
        $productModel = new ProductModel();

        $product = $productModel->findBySlug($slug);

        if (!$product) {
            throw PageNotFoundException::forPageNotFound('Product not found');
        }

        // Normalise price
        $price     = (float)($product['price'] ?? 0);
        $salePrice = (float)($product['sale_price'] ?? 0);

        if ($salePrice > 0 && $salePrice < $price) {
            $product['final_price'] = $salePrice;
        } else {
            $product['final_price'] = $price;
            $product['sale_price']  = null;
        }

        // Rating defaults
        $product['average_rating'] = $product['average_rating'] ?? 5.0;
        $product['rating_count']   = $product['rating_count']   ?? 1;
        $product['review_count']   = $product['review_count']   ?? $product['rating_count'];

        // Image guard
        $img = trim((string)($product['main_image'] ?? ''));
        if ($img === '' || strtolower($img) === 'null') {
            $product['main_image'] = 'assets/img/product/product_details_1_1.jpg';
        }

        // Related products (same category, different id, in stock)
        $related = $productModel
            ->where('id !=', $product['id'])
            ->where('category', $product['category'])
            ->where('stock_status', 'in_stock')
            ->where('is_active', 1)
            ->orderBy('id', 'DESC')
            ->findAll(8);

        // Normalise related products
        foreach ($related as &$r) {
            $rp     = (float)($r['price'] ?? 0);
            $rs     = (float)($r['sale_price'] ?? 0);
            $r['final_price'] = ($rs > 0 && $rs < $rp) ? $rs : $rp;

            $r['average_rating'] = $r['average_rating'] ?? 5.0;
            $r['rating_count']   = $r['rating_count']   ?? 1;
            $r['review_count']   = $r['review_count']   ?? $r['rating_count'];

            $rImg = trim((string)($r['main_image'] ?? ''));
            if ($rImg === '' || strtolower($rImg) === 'null') {
                $r['main_image'] = 'assets/img/product/product_1_1.jpg';
            }
        }
        unset($r);

        return view('shop/details', [
            'product'         => $product,
            'relatedProducts' => $related,
            'cartCount'       => $this->getCartItemCount(),
        ]);
    }

    /* ==============================================
     * CART PAGE
     * URL: /cart
     * ============================================== */
    public function cart()
    {
        $cart  = $this->getCart();   // session cart (products + subscriptions)
        $items = [];
        $subTotal  = 0;
        $totalQty  = 0;

        foreach ($cart as $rowId => $line) {
            $qty = (int)($line['qty'] ?? 1);
            if ($qty < 1) {
                $qty = 1;
            }

            // Decide final price
            $price     = (float)($line['price'] ?? 0);
            $salePrice = (float)($line['sale_price'] ?? 0);
            $final     = (float)($line['final_price'] ?? 0);

            if ($final <= 0) {
                if ($salePrice > 0 && $salePrice < $price) {
                    $final = $salePrice;
                } else {
                    $final = $price;
                }
            }

            $rowTotal = $final * $qty;
            $subTotal += $rowTotal;
            $totalQty += $qty;

            $img = $line['main_image'] ?? $line['image'] ?? '';

            $items[] = [
                'id'          => $rowId,                     // row id (can be product id or 'sub_xxx')
                'row_id'      => $rowId,
                'name'        => $line['name'] ?? 'Item',
                'slug'        => $line['slug'] ?? '',
                'main_image'  => $img,
                'price'       => $price,
                'sale_price'  => $salePrice ?: null,
                'final_price' => $final,
                'qty'         => $qty,
                'row_total'   => $rowTotal,

                // Optional flags, if present
                'is_subscription'   => $line['is_subscription']   ?? 0,
                'subscription_meta' => $line['subscription_meta'] ?? null,
            ];
        }

        $data = [
            'cartItems'           => $items,
            'totalQty'            => $totalQty,
            'totalAmount'         => $subTotal,
            'cartNotice'          => $this->session->getFlashdata('cart_notice'),
            'cartCount'           => $this->getCartItemCount(),
            'isCustomerLoggedIn'  => $this->session->get('isCustomerLoggedIn') === true,
        ];

        return view('shop/cart', $data);
    }

    /* ==============================================
     * ADD TO CART
     * URL: POST /cart/add/{id}   or GET (if you like)
     * ============================================== */
    public function addToCart(int $id)
    {
        $productModel = new ProductModel();
        $product      = $productModel->find($id);

        if (! $product || ! $product['is_active']) {
            throw PageNotFoundException::forPageNotFound('Product not available');
        }

        // quantity from request (default 1)
        $qty = (int)($this->request->getPost('qty')
            ?? $this->request->getPost('quantity')
            ?? 1);
        if ($qty < 1) {
            $qty = 1;
        }

        // 🔍 is it AJAX?
        $isAjax = $this->request->isAJAX()
            || strtolower($this->request->getHeaderLine('X-Requested-With')) === 'xmlhttprequest';

        $cart = $this->getCart();

        // If already in cart, increment quantity
        if (isset($cart[$id])) {
            $cart[$id]['qty'] += $qty;
        } else {
            $cart[$id] = $this->buildCartItemFromProduct($product, $qty);
        }

        // Recalculate row_total
        $final = (float)$cart[$id]['final_price'];
        $cart[$id]['row_total'] = $final * $cart[$id]['qty'];

        // Save back to session
        $this->setCart($cart);

        // updated numbers
        $itemQty   = (int)$cart[$id]['qty'];
        $cartCount = $this->getCartItemCount();

        // Flash for non-AJAX (cart page)
        $this->session->setFlashdata('cart_notice', 'Product added to cart.');

        if ($isAjax) {
            // ✅ JSON response used by JS on listing page
            return $this->response->setJSON([
                'success'    => true,
                'product_id' => $id,
                'item_qty'   => $itemQty,
                'cart_count' => $cartCount,
            ]);
        }

        // Normal browser flow
        $redirectUrl = $this->request->getServer('HTTP_REFERER') ?: site_url('shop');
        return redirect()->to($redirectUrl);
    }

    /* ==============================================
     * UPDATE CART
     * URL: POST /cart/update
     * ============================================== */
    public function updateCart()
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            $this->session->setFlashdata('cart_notice', 'Your cart is empty.');
            return redirect()->to(site_url('cart'));
        }

        $quantities = (array)$this->request->getPost('quantities');

        foreach ($quantities as $rowId => $qty) {
            $qty = (int)$qty;

            if (!isset($cart[$rowId])) {
                continue;
            }

            if ($qty <= 0) {
                unset($cart[$rowId]);
                continue;
            }

            $cart[$rowId]['qty'] = $qty;

            $final = (float)$cart[$rowId]['final_price'];
            $cart[$rowId]['row_total'] = $final * $qty;
        }

        $this->setCart($cart);
        $this->session->setFlashdata('cart_notice', 'Cart updated successfully.');

        return redirect()->to(site_url('cart'));
    }

    /* ==============================================
     * REMOVE ONE LINE
     * URL: GET /cart/remove/{rowId}
     * ============================================== */
    public function removeFromCart($rowId)
    {
        $cart  = $this->getCart();

        if (isset($cart[$rowId])) {
            unset($cart[$rowId]);
            $this->setCart($cart);
            $this->session->setFlashdata('cart_notice', 'Item removed from cart.');
        }

        return redirect()->to(site_url('cart'));
    }

    /* ==============================================
     * HELPER METHODS (SESSION CART)
     * ============================================== */

    /**
     * Build a cart line from product row.
     */
    protected function buildCartItemFromProduct(array $product, int $qty = 1): array
    {
        $price     = (float)($product['price'] ?? 0);
        $salePrice = (float)($product['sale_price'] ?? 0);

        if ($salePrice > 0 && $salePrice < $price) {
            $final = $salePrice;
        } else {
            $final = $price;
            $salePrice = null;
        }

        $img = trim((string)($product['main_image'] ?? ''));
        if ($img === '' || strtolower($img) === 'null') {
            $img = 'assets/img/product/product_thumb_1_1.jpg';
        }

        return [
            // IDs
            'product_id'   => $product['id'],
            'id'           => $product['id'],     // 🔥 for consistency
            'row_id'       => $product['id'],     // same as key

            // Basic info
            'slug'         => $product['slug'] ?? null,
            'name'         => $product['name'] ?? 'Product',
            'category'     => $product['category'] ?? null,

            // Image
            'main_image'   => $img,
            'image'        => $img,               // 🔥 so both keys are available

            // Price
            'price'        => $price,
            'sale_price'   => $salePrice,
            'final_price'  => $final,

            // Qty + row total
            'qty'          => $qty,
            'row_total'    => $final * $qty,
        ];
    }

    /**
     * Get full cart from session.
     */
    protected function getCart(): array
    {
        return (array)$this->session->get($this->cartKey);
    }

    /**
     * Save cart to session.
     */
    protected function setCart(array $cart): void
    {
        // Clean empty
        $cart = array_filter($cart, static function ($item) {
            return isset($item['qty']) && $item['qty'] > 0;
        });

        $this->session->set($this->cartKey, $cart);
    }

    /**
     * Total number of items (sum of quantities).
     */
    protected function getCartItemCount(): int
    {
        $cart = $this->getCart();
        $count = 0;
        foreach ($cart as $item) {
            $count += (int)($item['qty'] ?? 0);
        }
        return $count;
    }
}
