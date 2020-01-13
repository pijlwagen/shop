<?php


namespace App\Http\Controllers\Product;


use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['images', 'seo', 'discounts' => function ($query) {
            return $query->where('active_from', '<', Carbon::now()->format('Y-m-d H:i:s'))->where('active_until', '>', Carbon::now()->format('Y-m-d H:i:s'))->orderBy('active_from', 'ASC');
        }])->paginate(12);
        $categories = Category::with(['products'])->get();
        return view('products.index', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function view($slug, Request $request)
    {
        $product = Product::with(['categories', 'discounts' => function ($query) {
            return $query->where('active_from', '<', Carbon::now()->format('Y-m-d H:i:s'))->where('active_until', '>', Carbon::now()->format('Y-m-d H:i:s'))->orderBy('active_from', 'ASC');
        }, 'seo', 'images', 'options' => function($query) {
            return $query->with(['values']);
        }])->where('slug', $slug)->firstOrFail();
        return view('products.view', [
            'product' => $product
        ]);
    }
}
