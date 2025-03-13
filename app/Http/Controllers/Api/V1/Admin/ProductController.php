<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends BaseController
{
    // public function __construct()
    // {
    //     // $this->middleware('auth:sanctum');
    //     // $this->authorizeResource(Product::class, 'product');
    // }

    public function index()
    {
        $products = Product::with(['category', 'images'])->get();
        return $this->sendResponse($products, 'Products retrieved successfully');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:available,out_of_stock',
            'category_id' => 'required|exists:categories,id',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);
        $product = Product::create($validatedData);

        return $this->sendResponse($product->load(['category', 'images']), 'Product created successfully');
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'status' => 'sometimes|in:available,out_of_stock',
            'category_id' => 'sometimes|exists:categories,id',
        ]);


        $validatedData['slug'] = Str::slug($validatedData['name']);


        $product->update($validatedData);

        return $this->sendResponse($product->load(['category', 'images']), 'Product updated successfully');
    }

    public function show(Product $product)
    {
        return $this->sendResponse($product->load(['category', 'images']), 'Product retrieved successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return $this->sendResponse(null, 'Product deleted successfully');
    }
}
