<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Add this line

class CategoryController extends Controller
{
    use AuthorizesRequests; // Add this line

    public function index()
    {
        $categories = Category::with('user')->get();
        return response()->json($categories, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        $category = Category::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'user_id' => Auth::id(), // Set the user_id
        ]);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    public function show(Category $category)
    {
        $this->authorize('view', $category);
        return response()->json($category->load('user'), 200);
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validatedData);

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category->load('user'),
        ], 200);
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
    
        if ($category->products()->count() > 0) {
            return response()->json(['message' => 'Cannot delete category with products'], 400);
        }
    
        $category->delete();
    
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
