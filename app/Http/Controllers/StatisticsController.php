<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function productsCount()
    {
        $countProducts = Product::get()->count();

        return response()->json([
            "total_products" =>"Total number of products is " . $countProducts,
        ]);
    }

    public function productsCountWebsite()
    {
        $products = Product::get();

        foreach($products as $product) {
            
        }

        return response()->json([
            "total_products" =>"Total number of products is " . $countProducts,
        ]);
    }
}
