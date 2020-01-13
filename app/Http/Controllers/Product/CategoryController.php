<?php


namespace App\Http\Controllers\Product;


use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index($slug)
    {
        $category = Category::with(['products' => function ($query) {
            return $query->with(['images']);
        }])->where('slug', $slug)->firstOrFail();

        return view('categories.index', [
            'category' => $category,
        ]);
    }
}
