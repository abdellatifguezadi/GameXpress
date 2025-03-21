<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends BaseController
{


    public function index()
    {
        $category = Category::all();
        return $this->sendResponse($category, 'Category retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' =>  'required|string|max:255',
        ]);
        $validateData['slug'] = Str::slug($validateData['name']);
        $category = Category::create($validateData);
        
        return $this->sendResponse($category, 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validateData['slug'] = Str::slug($validateData['name']);
        $category->update($validateData);


        


        return $this->sendResponse($category, 'Category updated successfully.');
    }

    public function show(Category $category)
    {
        return $this->sendResponse($category, 'Category retrieved successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return $this->sendResponse($category, 'Category deleted successfully.');
    }
}
