<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        // return auth('sanctum')->user()->id;
    }

    public function addToWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'qty' => 'required'
        ],[
            'qty.required' => 'The quantity field is required'
        ]);

        $wishlist = Wishlist::create([
            'user_id' => auth('sanctum')->user()->id,
            'product_id' => $request->product_id,
            'qty' => $request->qty,
        ]);

        if($wishlist)
            return response()->json("Added to wishlist successfully");
        else
            return response()->json(["message" => "Something went wrong"], status: 422);
    }

    public function updateQuantity(Request $request, $wishListId)
    {
        $request->validate([
            'qty' => 'required'
        ],[
            'qty.required' => 'The quantity field is required'
        ]);

        $wishList = Wishlist::findOrFail($wishListId);

        if(auth('sanctum')->user()->id == $wishList->user_id) {
            $wishList->update([
                'qty' => $request->qty,
            ]);
            return response()->json("Quantity of the wishlist successfully");
        } else {
            return response()->json(["message" => "You are not authorized to perform this action"], status: 401);
        }
    }

    public function removeFromWishlist($wishListId)
    {
        $wishList = Wishlist::findOrFail($wishListId);

        if(auth('sanctum')->user()->id == $wishList->user_id) {
            $wishList->delete();
            return response()->json("Wishlist deleted successfully");
        } else {
            return response()->json(["message" => "You are not authorized to perform this action"], status: 401);
        }
    }
}
