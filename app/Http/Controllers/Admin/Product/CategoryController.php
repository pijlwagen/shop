<?php


namespace App\Http\Controllers\Admin\Product;


use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategorySeo;
use App\Models\ProductSeo;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::paginate($request->get('max') ?? 25);
        return view('admin.categories.index', [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:256',
        ]);

        if ($request->has('image')) {
            $image = $request->file('image')->store('uploads/products/', 'public');
        }

        $category = Category::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'hidden' => !!$request->input('hide'),
            'image' => $image ?? null,
        ]);

        if ($request->has('seo-title') || $request->has('seo-keywords') || $request->has('seo-description') || $request->has('seo-image')) {
            $file = null;
            if ($request->has('seo-image')) {
                $file = $request->file('seo-image')->store('uploads/products/', 'public');
            }
            CategorySeo::create([
                'product_id' => $category->id,
                'title' => $request->input('seo-title'),
                'keywords' => $request->input('seo-keywords'),
                'description' => $request->input('seo-description'),
                'image' => $file,
            ]);
        }

        return redirect()->route('admin.categories.index')->with('success', "Successfully created category <b>{$category->name}</b>.");
    }

    public function edit($id, Request $request)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', [
            'category' => $category
        ]);
    }

    public function update($id, Request $request)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|max:256',
        ]);

        $category->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'hidden' => !!$request->input('hide'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', "Successfully saved category <b>{$category->name}</b>.");
    }

    public function hide($id)
    {
        $category = Category::findOrFail($id);
        $category->update([
            'hidden' => $category->hidden ? false : true,
        ]);
        return response()->json(['hidden' => $category->hidden]);
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('info', "The category <b>{$category->name}</b> has been deleted.");
    }

    public function search(Request $request)
    {
        $categories = Category::where('name', 'like', "%" . $request->get('search') . "%")->limit(50)->get();
        return view('snippets.category-table', [
            'categories' => $categories
        ]);
    }
}
