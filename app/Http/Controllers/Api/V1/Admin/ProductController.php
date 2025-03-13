<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends BaseController
{


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
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);
        $product = Product::create($validatedData);

       
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('products', $filename, 'public');
                
                $product->images()->create([
                    'image_url' => $path,
                    'is_primary' => $key === 0 
                ]);
            }
        }

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
            'images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_images' => 'sometimes|array',
            'delete_images.*' => 'exists:product_images,id'
        ]);

        if (isset($validatedData['name'])) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        }

        $product->update($validatedData);

        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = $product->images()->find($imageId);
                if ($image) {
                    
                    $isPrimary = $image->is_primary;

                    Storage::disk('public')->delete($image->image_url);

                    $image->delete();

                    if ($isPrimary) {
                        $firstImage = $product->images()->first();
                        if ($firstImage) {
                            $firstImage->update(['is_primary' => true]);
                        }
                    }
                }
            }
        }

        if ($request->hasFile('images')) {
            $hasPrimaryImage = $product->images()->where('is_primary', true)->exists();
            
            foreach ($request->file('images') as $key => $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('products', $filename, 'public');
                
                $product->images()->create([
                    'image_url' => $path,
                    'is_primary' => !$hasPrimaryImage && $key === 0 
                ]);
            }
        }

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
