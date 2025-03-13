<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_can_create_category()
    {
         $categoryData = [
            'name' => 'Test Category',
            // 'slug' => 'test-category'
        ];  
        $categoryData['slug'] = Str::slug($categoryData['name']);
        $category = Category::create($categoryData);
        
        $this->assertDatabaseHas('categories', $categoryData);
        $this->assertEquals('Test Category', $category->name);
        $this->assertEquals('test-category', $category->slug);
    }

    public function test_category_has_products_relationship()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);        
        
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $category->products);
        $this->assertCount(1, $category->products);
        $this->assertInstanceOf(Product::class, $category->products->first());
    }
}
