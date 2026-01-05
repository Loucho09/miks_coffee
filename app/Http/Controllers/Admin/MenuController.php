<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the menu items.
     * Includes Search and Pagination support for Admin.
     */
    public function index(Request $request)
    {
        // 🟢 FIXED: Fetch all products with their relationships
        $query = Product::with(['category', 'sizes'])->latest();

        // Admin Search Functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // 🟢 FIXED: Pagination ensured for the UI
        $products = $query->paginate(10)->withQueryString();

        return view('admin.menu.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.menu.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:51200', 
        ]);

        // Slug generation logic (Preserved)
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'price' => $request->has('has_sizes') ? 0 : $request->price,
            'stock_quantity' => $request->stock_quantity,
            'description' => $request->description,
            'is_active' => true,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        // Handle Size Creation (Preserved)
        if ($request->has('has_sizes') && $request->filled('size_prices')) {
            foreach ($request->size_prices as $sizeLabel => $price) {
                if ($price) {
                    $product->sizes()->create([
                        'size' => $sizeLabel,
                        'price' => $price
                    ]);
                }
            }
        }

        return redirect()->route('admin.menu.index')->with('success', 'Menu item added successfully!');
    }

    public function edit($id)
    {
        $product = Product::with('sizes')->findOrFail($id);
        $categories = Category::all();
        return view('admin.menu.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:51200',
        ]);

        $slug = Str::slug($request->name);
        if ($slug !== $product->slug) {
            $originalSlug = $slug;
            $count = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
        } else {
            $slug = $product->slug;
        }

        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'price' => $request->has('has_sizes') ? 0 : $request->price,
            'stock_quantity' => $request->stock_quantity,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        // Handle Sizes Update (Preserved & Fixed Logic)
        if ($request->has('has_sizes') && $request->filled('size_prices')) {
            foreach ($request->size_prices as $sizeLabel => $price) {
                $product->sizes()->updateOrCreate(
                    ['size' => $sizeLabel],
                    ['price' => $price]
                );
            }
        } else {
            $product->sizes()->delete();
        }

        return redirect()->route('admin.menu.index')->with('success', 'Menu item updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('admin.menu.index')->with('success', 'Item deleted successfully.');
    }
}