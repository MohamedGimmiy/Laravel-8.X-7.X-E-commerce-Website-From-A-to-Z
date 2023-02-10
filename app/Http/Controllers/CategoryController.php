<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function addcategory(){
        return view('admin.addcategory');
    }

    public function categories(){
        $categories = Category::all();
        return view('admin.categories',compact('categories'));
    }

    public function savecategory(Request $request){
        $validated = $request->validate([
            'category_name' => 'required|unique:categories',
        ]);

        Category::create($validated);

        return back()->with('success', 'Category created successfully');
    }

    public function edit_category($id){
        $category = Category::findOrFail($id);
        return view('admin.edit_category', compact('category'));
    }
    public function updatecategory(Request $request, $id){
        $validated = $request->validate([
            'category_name' => 'required|unique:categories',
        ]);
        $category = Category::findOrFail($id);
        $category->update($validated);
        return redirect('/categories')->with('success', 'Category updated successfully');

    }

    public function delete_category($id){
        Category::findOrFail($id)->delete();
        return redirect('/categories')->with('success', 'Category deleted successfully');

    }
}
