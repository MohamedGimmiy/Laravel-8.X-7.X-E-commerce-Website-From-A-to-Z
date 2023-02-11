<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function addproduct(){
        $categories = Category::all();
        return view('admin\addproduct',compact('categories'));
    }

    public function products(){
        $products = Product::all();
        return view('admin\products', compact('products'));
    }

    public function saveproduct(Request $request){
        $validated = $request->validate([
            'product_name' => 'required',
            'product_price' => 'required',
            'product_category' => 'required',
            'product_image' => 'image|nullable'
        ]);
        
        if($request->hasFile('product_image')){
            $fileNameWithext = $request->file('product_image')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithext, PATHINFO_FILENAME);
            $extension = $request->file('product_image')->getClientOriginalExtension();
            $fileNameToStore = $fileName. '_'. time(). '.'. $extension;
            $path = $request->file('product_image')->storeAs('public/product_images',$fileNameToStore);
        } else{
            $fileNameToStore = 'noimage.png';
        }
        Product::create([
            'product_name' => $validated['product_name'],
            'product_price' => $validated['product_price'],
            'product_category' => $validated['product_category'],
            'product_image' => $fileNameToStore,
        ]);

        return back()->with('success','Product created successfully!');
    }   
}
