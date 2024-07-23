<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\error;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'supplier')->get();

        // Append image_url to each product
        $products = $products->map(function ($product) {
            $product->image_url = $product->image_url;
            return $product;
        });

        return response()->json([
            'products' => $products
        ], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
        }

        $product = Product::create([
            'name' => $validatedData['name'],
            'category_id' => $validatedData['category_id'],
            'supplier_id' => $validatedData['supplier_id'],
            'quantity' => $validatedData['quantity'],
            'price' => $validatedData['price'],
            'image' => $imagePath,
            'user_id' => Auth::id(),
        ]);
        $product ->load('category', 'supplier');
        $product->image_url = $product->image_url;

        return response()->json([
            'message' => 'Created successfully',
            'product' => $product

        ], 201);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        // Load related category and supplier and append image_url to the product
        $product->load('category', 'supplier');
        $product->image_url = $product->image_url;
        return response()->json([
            'product' => $product,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|integer|exists:categories,id',
            'supplier_id' => 'sometimes|required|integer|exists:suppliers,id',
            'quantity' => 'sometimes|required|integer',
            'price' => 'sometimes|required|numeric',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
    
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
    
            // Store new image
            $imagePath = $request->file('image')->store('product_images', 'public');
            $validatedData['image'] = $imagePath;
        }
    
        $product->update($validatedData);
    
        $product->image_url = $product->image_url;
        $product->load('category', 'supplier');
        
        return response()->json([
            'message' => 'Updated successfully',
            'product' => $product,
        ], 200);
    }
    
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404); // 404 Not Found for non-existent product
        }

        $product->delete();
        return response()->json(['message' => 'Deleted successfully'], 200); // 200 OK for successful deletion
    }
}
