<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;

class ProductController extends Controller
{
    // 1. Show the form to create a new product
    public function create()
    {
        return view('products.create');
    }

    // 2. Store the data in the database
    public function store(Request $request)
    {
        // Validate the input
        $data = $request->validate([
            'name' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
            'description' => 'nullable',
        ]);

        // Create the product
        Product::create($data);

        // Go back to dashboard
        return redirect()->route('dashboard');
    }
}