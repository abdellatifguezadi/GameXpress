<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends BaseController
{
    public function __construct()
    {
        $this->authorizeResource(ProductImage::class, 'image');
    }

    public function index(Product $product)
    {
        $this->authorize('view', $product);
        $images = $product->images;
        return $this->sendResponse($images, 'Product images retrieved successfully');
    }

    public function store(Request $request, Product $product)
    {
        $this->authorize('create', ProductImage::class);
        
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_primary' => 'sometimes|boolean'
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            if ($request->boolean('is_primary')) {
                ProductImage::where('product_id', $product->id)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }

            foreach ($request->file('images') as $key => $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('products', $filename, 'public');
                
                $productImage = ProductImage::create([
                    'image_url' => $path,
                    'product_id' => $product->id,
                    'is_primary' => $key === 0 && $request->boolean('is_primary', false)
                ]);

                $images[] = $productImage;
            }
        }

        return $this->sendResponse($images, 'Product images uploaded successfully');
    }

    public function show(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            return $this->sendError('Image does not belong to this product', [], 403);
        }
        return $this->sendResponse($image, 'Product image retrieved successfully');
    }

    public function destroy(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            return $this->sendError('Image does not belong to this product', [], 403);
        }

        Storage::disk('public')->delete($image->image_url);

        $image->delete();

        if ($image->is_primary) {
            $firstImage = ProductImage::where('product_id', $product->id)->first();
            if ($firstImage) {
                $firstImage->update(['is_primary' => true]);
            }
        }
        
        return $this->sendResponse(null, 'Product image deleted successfully');
    }

    public function setPrimary(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            return $this->sendError('Image does not belong to this product', [], 403);
        }

        ProductImage::where('product_id', $product->id)
            ->where('is_primary', true)
            ->update(['is_primary' => false]);

        $image->update(['is_primary' => true]);

        return $this->sendResponse($image, 'Primary image set successfully');
    }
} 