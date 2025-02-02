<?php

namespace App\Http\Controllers\API;

use App\Commands\Product\CreateProductCommand;
use App\Commands\Product\CreateProductCommandHandler;
use App\Commands\Product\DeleteProductCommand;
use App\Commands\Product\DeleteProductCommandHandler;
use App\Commands\Product\UpdateProductCommand;
use App\Commands\Product\UpdateProductCommandHandler;
use App\Events\Product\CreateProductEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\StoreUpdateProductRequest;
use App\Queries\Product\GetAllProductsQuery;
use App\Queries\Product\GetAllProductsQueryHandler;
use App\Queries\Product\GetProductQuery;
use App\Queries\Product\GetProductQueryHandler;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    // List all products
    public function index()
    {
        $query = new GetAllProductsQuery();
        $products = app(GetAllProductsQueryHandler::class)->handle($query);

        return response()->json($products);
    }

    // Store a new product
    public function store(StoreProductRequest $request)
    {
        $productData = $request->validated(); // داده‌های معتبر از درخواست
        $command = new CreateProductCommand($productData); // ارسال داده‌ها به فرمان
        $product = app(CreateProductCommandHandler::class)->handle($command);

        // Cache the product
        Cache::tags(['products'])->put("product_{$product->id}", $product, now()->addMinutes(10));
        event(new CreateProductEvent($product));
        return response()->json($product, 201);
    }

    // Show a specific product
    public function show(string $id)
    {
        $query = new GetProductQuery($id);
        $product = app(GetProductQueryHandler::class)->handle($query);

        return response()->json($product);
    }

    // Update a specific product
    public function update(StoreUpdateProductRequest $request, string $id)
    {
        $productData = $request->validated();
        $command = new UpdateProductCommand($productData, $id);
       
        $product = app(UpdateProductCommandHandler::class)->handle($command);

        // Clear and update cache
        Cache::tags(['products'])->forget("product_{$id}");
        Cache::tags(['products'])->forget('products');
        Cache::tags(['products'])->put("product_{$product->id}", $product, now()->addMinutes(10));

        return response()->json($product);
    }

    // Delete a specific product
    public function destroy(string $id)
    {
        $command = new DeleteProductCommand($id);
        app(DeleteProductCommandHandler::class)->handle($command);

        // Remove from cache
        Cache::tags(['products'])->forget("product_{$id}");
        Cache::tags(['products'])->forget('products');

        return response()->json(['message' => 'Product deleted successfully'], 204);
    }
}
