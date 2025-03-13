<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product()
    {
        $category = Category::factory()->create();
        
        $productData = [
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 99.99,
            'stock' => 10,
            'status' => 'available',
            'category_id' => $category->id
        ];

        $product = Product::create($productData);

        $this->assertDatabaseHas('products', $productData);
        $this->assertEquals('Test Product', $product->name);
        $this->assertEquals(99.99, $product->price);
        $this->assertEquals(10, $product->stock);
    }

    public function test_product_has_category_relationship()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
    }

    public function test_product_has_images_relationship()
    {
        $product = Product::factory()->create();
        $image = ProductImage::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $product->images);
        $this->assertCount(1, $product->images);
        $this->assertInstanceOf(ProductImage::class, $product->images->first());
    }

    public function test_product_uses_soft_deletes()
    {
        $product = Product::factory()->create();
        
        $product->delete();

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_product_price_is_stored_as_decimal()
    {
        $product = Product::factory()->create(['price' => 99.99]);

        $this->assertIsString($product->price);
        $this->assertEquals('99.99', $product->price);
    }

    public function test_product_stock_is_stored_as_integer()
    {
        $product = Product::factory()->create(['stock' => '10']);

        $this->assertIsInt($product->stock);
        $this->assertEquals(10, $product->stock);
    }
}
