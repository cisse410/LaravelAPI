<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::latest()->get();

        if (is_null($product->first())) {
            return response()->json([
                'status' => 'Failed to find product',
                'message' => 'Product not found',
            ], 201);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Produit recuperated successfully',
            'data' => $product
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:250',
            'description' => 'required|string|max:255',
            'price' => 'required|integer',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'data' => $validated->errors(),
            ], 403);
        }
        $product = Product::create($request->all());
        return response()->json([
            'status' => 'Success',
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if (is_null($product)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Product not found',
            ], 201);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Produit recuperated successfully',
            'data' => $product
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'data' => $validated->errors(),
            ], 403);
        }

        if (is_null($product)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Product not found',
            ], 201);
        }
        $product->update($request->all());
        return response()->json([
            'status' => 'Success',
            'message' => 'Product updated successfully',
            'data' => $product
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if (is_null($product)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Product not found',
            ], 201);
        }
        $product->delete();
        return response()->json([
            'status' => 'Success',
            'message' => 'Product deleted successfully',
            'data' => $product
        ], 201);
    }

    /**
     * Rechercher un produit
     *
     * @param string $name
     *
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        $product = Product::where('name', 'like', '%' . $name . '%')->get();
        if (is_null($product->first())) {
            return response()->json([
                'status' => 'Failed to find product',
                'message' => 'Product not found',
            ], 201);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Produit recuperated successfully',
            'data' => $product
        ], 201);
    }
}