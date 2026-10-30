<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\JsonResponse;

class SubCategoryController extends Controller
{
    /**
     * Menampilkan subkategori berdasarkan kategori.
     */
    public function index(int $categoryId): JsonResponse
    {
        $category = Category::query()
            ->where('is_active', true)
            ->findOrFail($categoryId);

        $subCategories = SubCategory::query()
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('name')
            ->get([
                'id',
                'category_id',
                'name',
                'slug',
                'icon',
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Daftar subkategori berhasil diambil.',
            'data' => [
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ],
                'sub_categories' => $subCategories,
            ],
        ]);
    }
}