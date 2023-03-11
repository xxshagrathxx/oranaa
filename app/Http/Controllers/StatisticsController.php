<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function productsCount()
    {
        $countProducts = Product::get()->count();

        return response()->json([
            "total_products" => "Total number of products is " . $countProducts,
        ]);
    }

    public function productsCountWebsite()
    {
        $domains = Product::distinct()->count('domain');

        return response()->json([
            "total_products_website" => "The total count of products per each website is " . $domains,
        ]);
    }

    public function productsAveragePrice()
    {
        $productsAvg = Product::avg('price');

        return response()->json([
            "avg_products" => "The average price of a products is " . number_format((float)$productsAvg, 2, '.', ''),
        ]);
    }

    public function productsHighestPrice()
    {
        $productsHighestPrice = Product::groupBy('domain')->selectRaw('SUM(price) as sum_per_website, domain')->get();

        $highestPrice = -1;
        $domain = '';

        foreach($productsHighestPrice as $product) {
            if((float) $product->sum_per_website > $highestPrice) {
                $highestPrice = $product->sum_per_website;
                $domain = $product->domain;
            }
        }

        return response()->json([
            "highest_price" => "The website that has the highest total price of its products is " . $domain . " and the highest total price is " . number_format((float)$highestPrice, 2, '.', ''),
        ]);
    }
    
    public function productsTotalMonth()
    {
        $sumThisMonth = Product::selectRaw('SUM(price) as sum_this_month')
                        ->whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->get();

        return response()->json([
            "products_this_month" => "The total price of products added this month is " . number_format((float)$sumThisMonth[0]->sum_this_month, 2, '.', ''),
        ]);
    }
}
