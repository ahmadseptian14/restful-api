<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductSingleResource;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $products = ProductResource::collection(Product::paginate(16));

         return response()->json([
            'message' => 'Berhasil menampilkan seluruh produk',
            'data' => $products
         ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $this->authorize('if_moderator');

        if($request->price < 10000) {
            throw ValidationException::withMessages([
                'price' => 'Price tidak boleh kurang dari 10000'
            ]);
        }

        $product = Product::create($request->toArray());

        return response()->json([
            'message' => 'Product berhasil di tambahkan',
            'data' => new ProductSingleResource($product)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product =  new ProductSingleResource($product);

        return response()->json([
            'message' => 'Berhasil menampilkan produk',
            'data' => $product
         ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('if_admin');

        if($request->price < 10000) {
            throw ValidationException::withMessages([
                'price' => 'Price tidak boleh kurang dari 10000'
            ]);
        }

        $data = $request->toArray();
        $data['slug'] = Str::slug($request->name . '-' . Str::random(9));

        $product->update($data);

        return response()->json([
            'message' => 'Product berhasil di update',
            'data' => new ProductSingleResource($product)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Produk berhasil di hapus'
        ]);
    }
}
