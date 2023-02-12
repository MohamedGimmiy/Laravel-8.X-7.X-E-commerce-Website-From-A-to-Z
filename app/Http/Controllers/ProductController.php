<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function addproduct()
    {
        $categories = Category::all();
        return view('admin\addproduct', compact('categories'));
    }

    public function products()
    {
        $products = Product::all();
        return view('admin\products', compact('products'));
    }

    public function saveproduct(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required',
            'product_price' => 'required',
            'product_category' => 'required',
            'product_image' => 'image|nullable'
        ]);

        if ($request->hasFile('product_image')) {
            $fileNameWithext = $request->file('product_image')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithext, PATHINFO_FILENAME);
            $extension = $request->file('product_image')->getClientOriginalExtension();
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            $path = $request->file('product_image')->storeAs('public/product_images', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.png';
        }
        Product::create([
            'product_name' => $validated['product_name'],
            'product_price' => $validated['product_price'],
            'product_category' => $validated['product_category'],
            'product_image' => $fileNameToStore,
        ]);

        return back()->with('success', 'Product created successfully!');
    }
    public function editproduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin\editproduct', compact('product', 'categories'));
    }
    public function updateproduct(Request $request, $id)
    {
        $validated = $request->validate([
            'product_name' => 'required',
            'product_price' => 'required',
            'product_category' => 'required',
        ]);

        if ($request->hasFile('product_image')) {
            //remove the old image
            if ($request->old_image != 'noimage.png')
                unlink('storage/product_images/' . $request->old_image);

            // setting the new image
            $fileNameWithext = $request->file('product_image')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithext, PATHINFO_FILENAME);
            $extension = $request->file('product_image')->getClientOriginalExtension();
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            $path = $request->file('product_image')->storeAs('public/product_images', $fileNameToStore);

            $product = Product::findOrFail($id);
            $product->update([
                'product_name' => $validated['product_name'],
                'product_price' => $validated['product_price'],
                'product_category' => $validated['product_category'],
                'product_image' => $fileNameToStore
            ]);
            return redirect('/products')->with('success', 'Product updated successfully');
        }

        $product = Product::findOrFail($id);
        $product->update($validated);
        return redirect('/products')->with('success', 'Product updated successfully');
    }

    public function deleteproduct($id)
    {
        $product = Product::findOrFail($id);
        if ($product->product_image != 'noimage.png')
            unlink('storage/product_images/' . $product->product_image);
        $product->delete();
        return redirect('/products')->with('success', 'Product deleted successfully');

    }

    public function activate_product($id)
    {
        $product = Product::findOrFail($id);
        $product->status = 1;
        $product->update();

        return redirect('/products')->with('success', 'Product has been activated successfully');

    }
    public function unactivate_product($id)
    {
        $product = Product::findOrFail($id);
        $product->status = 0;
        $product->update();

        return redirect('/products')->with('success', 'Product has been unactivated successfully');

    }
    public function view_product_by_category_name($category_name){
        $products = Product::where('product_category',$category_name)->where('status',1)->get();
        $categories = Category::all();
        return view('client.shop',compact('products','categories'));
    }
}
