<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Product;
use App\Models\ProductImage;

class ProductImageController extends BaseController
{
    public function index(Product $product)
    {
        $images = $product->images;
        return $this->sendResponse($images, 'Product images retrieved successfully');
    }



    public function setPrimary(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            return $this->sendError('Image does not belong to this product', [], 403);
        }

        if ($image->is_primary) {
            return $this->sendResponse($image, 'Image is already primary');
        }

        $product->images()->where('id', '!=', $image->id)->update(['is_primary' => false]);

        $image->update(['is_primary' => true]);
        
        return $this->sendResponse($image, 'Primary image set successfully');
    }
} 