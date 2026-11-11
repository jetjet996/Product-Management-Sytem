<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\BulkDeleteProductsRequest;
use App\Http\Requests\ReadProductsRequest;
use App\Http\Requests\DeleteProductsRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ReadProductsRequest $request)
    {
        $query = Product::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->has('enabled')) {
            $query->where('enabled', $request->boolean('enabled'));
        }
        $page = $request->input('page', 1);
        $query->whereNull('deleted_at');
        $query->orderBy('id','desc');
        return response()->json(
            $query->latest()->paginate(10, ['*'], 'page', $page)
        );
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest  $request)
    {
        $product = Product::create($request->validated());

        return response()->json(
            $product->load('category'),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json(
            $product->load('category')
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request,Product $product)
    {
        $product->update($request->validated());
        return response()->json(
            $product->load('category')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteProductsRequest $request,Product $product)
    {
        $product = Product::findOrFail($request->validated('id'));
        $product->delete();
        return response()->json([
            'message' => 'Product deleted successfully.'
        ]);
    }

    public function bulkDestroy(BulkDeleteProductsRequest $request)
    {
        Product::whereIn('id', $request->validated('ids'))->delete();
        return response()->json([
            'message' => 'Products deleted successfully.'
        ]);

    }

    public function export()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

}
