<?php


namespace App\Http\Controllers\Product;


use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = $category->products()->get();

        return view('categories.index', [
            'category' => $category,
            'products' => $products
        ]);
    }
}
