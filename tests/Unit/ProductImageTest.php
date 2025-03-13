<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product_image()
    {
        $product = Product::factory()->create();
        
        $imageData = [
            'product_id' => $product->id,
            'image_url' => 'images/test-image.jpg',
            'is_primary' => true
        ];

        $productImage = ProductImage::create($imageData);

        $this->assertDatabaseHas('product_images', $imageData);
        $this->assertEquals('images/test-image.jpg', $productImage->image_url);
        $this->assertTrue($productImage->is_primary);
    }

    public function test_product_image_has_product_relationship()
    {
        $product = Product::factory()->create();
        $productImage = ProductImage::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $productImage->product);
        $this->assertEquals($product->id, $productImage->product->id);
    }

    public function test_is_primary_is_stored_as_boolean()
    {
        $productImage = ProductImage::factory()->create(['is_primary' => true]);

        $this->assertIsBool($productImage->is_primary);
        $this->assertTrue($productImage->is_primary);

        $productImage->is_primary = false;
        $productImage->save();

        $this->assertFalse($productImage->fresh()->is_primary);
    }

    public function test_can_set_primary_image()
    {
        $product = Product::factory()->create();
        
        // Create multiple images for the same product
        $image1 = ProductImage::factory()->create([
            'product_id' => $product->id,
            'is_primary' => true
        ]);
        
        $image2 = ProductImage::factory()->create([
            'product_id' => $product->id,
            'is_primary' => false
        ]);

        // Set image2 as primary
        $image2->is_primary = true;
        $image2->save();

        // Refresh both models from database
        $image1->refresh();
        $image2->refresh();

        $this->assertFalse($image1->is_primary);
        $this->assertTrue($image2->is_primary);
    }
}
