<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Surfsidemedia\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function index(){
        
        $items=Cart::instance('cart')->content();
        return view('cart',compact('items'));
    }

    public function addToCart(Request $request)
{
    Cart::instance('cart')->add($request->id,$request->name,$request->quantity,$request->price)->associate('App\Models\Product');        
    return redirect()->back();
} 

public function increase_item_quantity($rowId)
{
    $product = Cart::instance('cart')->get($rowId);
    $qty = $product->qty + 1;
    Cart::instance('cart')->update($rowId,$qty);
    return redirect()->back();
}

public function reduce_item_quantity($rowId){
    $product = Cart::instance('cart')->get($rowId);
    $qty = $product->qty - 1;
    Cart::instance('cart')->update($rowId,$qty);
    return redirect()->back();
}

    public function remove_item_from_cart($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    
public function empty_cart()
{
    Cart::instance('cart')->destroy();
    return redirect()->back();
}
}
