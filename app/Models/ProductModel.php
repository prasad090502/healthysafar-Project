<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'name', 'slug',
        'short_description', 'long_description',
        'price', 'sale_price', 'currency_code',
        'sku', 'category', 'tags',
        'stock_status', 'stock_quantity',
        'main_image', 'thumbnail_image', 'gallery_images',
        'average_rating', 'rating_count', 'review_count',
        'serving_size', 'calories_kcal', 'protein_g', 'carbs_g',
        'fat_g', 'fibre_g', 'sugar_g', 'sodium_mg',
        'is_active', 'is_featured', 'subscription_available',
        'prep_time_minutes', 'is_veg', 'max_per_order',
        'seo_title', 'seo_description',
        'created_at', 'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getHomeProducts(int $limit = 12): array
    {
        return $this->where('stock_status', 'in_stock')
            ->where('is_active', 1)
            ->orderBy('created_at', 'DESC')
            ->findAll($limit);
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)
            ->where('is_active', 1)
            ->first();
    }
}