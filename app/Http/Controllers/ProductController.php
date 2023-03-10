<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Serializers\ProductSerializer;
use App\Serializers\ProductsSerializer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\CommonMark\CommonMarkConverter;

class ProductController extends Controller
{
    protected JsonResponse $response;

    public function __construct(JsonResponse $response)
    {
        $this->response = $response;
    }

    public function index()
    {
        $products = Product::all();

        return $this->response->setData(['products' => (new ProductsSerializer($products))->getData()]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'url' => 'required|url',
            'description' => 'required|string',
        ];

        $this->validate($request, $rules);

        $converter = new CommonMarkConverter(['html_input' => 'escape', 'allow_unsafe_links' => false]);

        $product = Product::create([
            'name' => $request->get('name'),
            'price' => $request->get('price'),
            'url' => $request->get('url'),
            // To save just the domain in database for some more comparisions
            'domain' => getDomainName($request->get('url')),
            'description' => $converter->convert($request->get('description'))->getContent(),
        ]);

        $serializer = new ProductSerializer($product);

        return $this->response->setData(['product' => $serializer->getData()]);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        $serializer = new ProductSerializer($product);

        return new JsonResponse(['product' => $serializer->getData()]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $rules = [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'url' => 'required|url',
            'description' => 'required|string',
        ];

        $this->validate($request, $rules);

        $converter = new CommonMarkConverter(['html_input' => 'escape', 'allow_unsafe_links' => false]);

        $product = Product::findOrFail($id);
        $product->name = $request->get('name');
        $product->url = $request->get('url');
        // To save just the domain in database for some more comparisions
        $product->domain = getDomainName($request->get('url'));
        $product->price = $request->get('price');
        $product->description = $converter->convert($request->get('description'))->getContent();
        $product->save();

        return new JsonResponse(
            [
                'product' => (new ProductSerializer($product))->getData(),
            ]
        );
    }
}
