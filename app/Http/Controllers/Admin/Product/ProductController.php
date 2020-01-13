<?php


namespace App\Http\Controllers\Admin\Product;


use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductDiscount;
use App\Models\ProductImage;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductSeo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['seo', 'categories'])->paginate($request->get('max') ?? 50);
        $outOfStock = Product::where('stock', '<', 5)->get();
        return view('admin.products.index', [
            'products' => $products,
            'outOfStock' => $outOfStock,
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:256|string',
            'price' => 'required|numeric|min:0',
            'slug' => 'required|regex:/[a-z-0-9]+/|max:256',
            'stock' => 'numeric',
            'seo-image' => 'image',
            'images.*' => 'image',
            'discount.*.type' => 'required',
            'discount.*.start.date' => 'required',
            'discount.*.start.time' => 'required',
            'discount.*.end.date' => 'required',
            'discount.*.end.time' => 'required',
            'option*.title' => 'required',
            'option.*.values.*' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $product = Product::create([
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'slug' => $request->input('slug'),
                'stock' => $request->input('stock'),
                'description' => $request->input('description')
            ]);

            foreach ($request->input('categories') ?? [] as $category) {
                ProductCategory::create([
                    'product_id' => $product->id,
                    'category_id' => $category
                ]);
            }

            if ($request->has('seo-title') || $request->has('seo-keywords') || $request->has('seo-description') || $request->has('seo-image')) {
                $file = null;
                if ($request->has('seo-image')) {
                    $file = $request->file('seo-image')->store('uploads/products/', 'public');
                }
                ProductSeo::create([
                    'product_id' => $product->id,
                    'title' => $request->input('seo-title'),
                    'keywords' => $request->input('seo-keywords'),
                    'description' => $request->input('seo-description'),
                    'image' => $file,
                ]);
            }

            if ($request->has('discount')) {
                foreach ($request->input('discount') as $discount) {
                    ProductDiscount::create([
                        'product_id' => $product->id,
                        'type' => $discount['type'],
                        'amount' => $discount['amount'] ?? 0,
                        'active_from' => Carbon::parse("{$discount['start']['date']} {$discount['start']['time']}")->format('Y-m-d H:i:s'),
                        'active_until' => Carbon::parse("{$discount['end']['date']} {$discount['end']['time']}")->format('Y-m-d H:i:s'),
                    ]);
                }
            }

            if ($request->has('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('uploads/products/', 'public');
                    ProductImage::create([
                        'path' => $path,
                        'product_id' => $product->id,
                    ]);
                }
            }

            if ($request->has('option')) {
                foreach ($request->input('option') as $option) {
                    $parent = ProductOption::create([
                        'product_id' => $product->id,
                        'type' => $option['type'] ?? 'select',
                        'title' => $option['title'] ?? 'Unnamed option',
                    ]);

                    if (array_key_exists('values', $option)) {
                        foreach ($option['values'] as $value) {
                            ProductOptionValue::create([
                                'product_option_id' => $parent->id,
                                'value' => $value['value'],
                                'increment' => $value['increment'] ?? null,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('admin.products.index')->with('success', "Successfully created product <b>{$product->name}</b>.");
    }

    public function edit($id)
    {
        $product = Product::with(['images', 'discounts', 'seo', 'categories', 'options' => function ($query) {
            return $query->with('values');
        }])->findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required|max:256|string',
            'price' => 'required|numeric|min:0',
            'slug' => 'required|regex:/[a-z-0-9]+/|max:256',
            'stock' => 'numeric',
            'seo-image' => 'image',
            'images.*' => 'image',
            'discount.*.type' => 'required',
            'discount.*.start.date' => 'required',
            'discount.*.start.time' => 'required',
            'discount.*.end.date' => 'required',
            'discount.*.end.time' => 'required',
            'option*.title' => 'required',
            'option.*.values.*' => 'required'
        ]);

//        DB::beginTransaction();
//
//        try {
            $product = Product::with(['images', 'discounts', 'seo', 'categories', 'options' => function ($query) {
                return $query->with('values');
            }])->findOrFail($id);

            $product->update([
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'slug' => $request->input('slug'),
                'stock' => $request->input('stock'),
                'description' => $request->input('description')
            ]);

            ProductCategory::where('product_id', $product->id)->delete();

            foreach ($request->input('categories') ?? [] as $category) {
                ProductCategory::create([
                    'product_id' => $product->id,
                    'category_id' => $category
                ]);
            }

            if ($request->has('seo-title') || $request->has('seo-keywords') || $request->has('seo-description') || $request->has('seo-image')) {
                $file = null;
                if ($request->has('seo-image')) {
                    $file = $request->file('seo-image')->store('uploads/products/', 'public');
                }
                if ($product->seo) {
                    $product->seo()->update([
                        'title' => $request->input('seo-title'),
                        'keywords' => $request->input('seo-keywords'),
                        'description' => $request->input('seo-description'),
                        'image' => $file,
                    ]);
                } else {
                    ProductSeo::create([
                        'product_id' => $product->id,
                        'title' => $request->input('seo-title'),
                        'keywords' => $request->input('seo-keywords'),
                        'description' => $request->input('seo-description'),
                        'image' => $file,
                    ]);
                }
            }

            ProductDiscount::where('product_id', $product->id)->delete();

            if ($request->has('discount')) {
                foreach ($request->input('discount') as $discount) {
                    ProductDiscount::create([
                        'product_id' => $product->id,
                        'type' => $discount['type'],
                        'amount' => $discount['amount'] ?? 0,
                        'active_from' => Carbon::parse("{$discount['start']['date']} {$discount['start']['time']}")->format('Y-m-d H:i:s'),
                        'active_until' => Carbon::parse("{$discount['end']['date']} {$discount['end']['time']}")->format('Y-m-d H:i:s'),
                    ]);
                }
            }

            if ($request->has('delete')) {
                ProductImage::whereIn('id', array_keys($request->input('delete')))->delete();
            }

            if ($request->has('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('uploads/products/', 'public');
                    ProductImage::create([
                        'path' => $path,
                        'product_id' => $product->id,
                    ]);
                }
            }

            ProductOption::where('product_id', $product->id)->delete();

            if ($request->has('option')) {
                foreach ($request->input('option') as $option) {
                    $parent = ProductOption::create([
                        'product_id' => $product->id,
                        'type' => $option['type'] ?? 'select',
                        'title' => $option['title'] ?? 'Unnamed option',
                    ]);

                    if (array_key_exists('values', $option)) {
                        foreach ($option['values'] as $value) {
                            ProductOptionValue::create([
                                'product_option_id' => $parent->id,
                                'value' => $value['value'],
                                'increment' => $value['increment'] ?? null,
                            ]);
                        }
                    }
                }
            }

//            DB::commit();
//
//        } catch (\Exception $e) {
//            DB::rollBack();
//            die($e);
//        }

        return redirect()->route('admin.products.index')->with('success', "Successfully updated product <b>{$product->name}</b>.");
    }

    public function search(Request $request)
    {
        $products = Product::with(['seo', 'categories'])->where('name', 'like', "%" . $request->get('search') . "%")->limit(50)->get();
        return view('snippets.product-table', [
            'products' => $products
        ]);
    }
}
