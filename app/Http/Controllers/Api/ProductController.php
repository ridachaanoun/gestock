<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\error;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
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

        $product = Product::create([
            'name' => $validatedData['name'],
            'category_id' => $validatedData['category_id'],
            'supplier_id' => $validatedData['supplier_id'],
            'quantity' => $validatedData['quantity'],
            'price' => $validatedData['price'],
            'user_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Created successfully','product' =>$product], 201);
    }

    public function show($id)
    {
        $product= Product::find($id);
        if(!$product){
            return response()->json(['message'=>'Product not found'],404);
        }
        return $product;
    }

    public function update(Request $request,$id)
    {
        $product= Product::find($id);
        if(!$product){
            return response()->json(['message'=>'Product not found'],404);
        }
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|integer|exists:categories,id',
            'supplier_id' => 'sometimes|required|integer|exists:suppliers,id',
            'quantity' => 'sometimes|required|integer',
            'price' => 'sometimes|required|numeric',
        ]);

        $product->update($validatedData);

        return response()->json(['message' => 'Updated successfully','product' =>$product],200);
    }
    public function destroy($id)
    {
        $product = Product::find($id);
    
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404); // 404 Not Found for non-existent product
        }
    
        $product->delete();
        return response()->json(['message' => 'Deleted successfully'], 200); // 200 OK for successful deletion
    }
}