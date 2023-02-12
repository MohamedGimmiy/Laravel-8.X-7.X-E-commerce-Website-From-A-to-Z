<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function addslider(){
        return view('admin\addslider');
    }

    public function sliders()
    {
        $sliders = Slider::all();
        return view('admin\sliders', compact('sliders'));
    }
    public function saveslider(Request $request){
        $validated = $request->validate([
            'description1' => 'required',
            'description2' => 'required',
            'slider_image' => 'required|image|max:1999'
        ]);

            $fileNameWithext = $request->file('slider_image')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithext, PATHINFO_FILENAME);
            $extension = $request->file('slider_image')->getClientOriginalExtension();
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            $path = $request->file('slider_image')->storeAs('public/slider_images', $fileNameToStore);

        Slider::create([
            'description1' => $validated['description1'],
            'description2' => $validated['description2'],
            'slider_image' => $fileNameToStore,
        ]);

        return redirect('/sliders')->with('success', 'Slider created successfully!');
    }
    public function editslider($id){
        $slider = Slider::findOrFail($id);
        return view('admin.editslider',compact('slider'));
    }
    public function updateslider(Request $request, $id){
        $validated = $request->validate([
            'description1' => 'required',
            'description2' => 'required',
        ]);

            //remove the old image
            if( $request->hasFile('slider_image') && $request->slider_image != $request->old_image)
                unlink('storage/slider_images/' . $request->old_image);

            $slider = Slider::findOrFail($id);

            // setting the new image
            if($request->hasFile('slider_image')){

                $fileNameWithext = $request->file('slider_image')->getClientOriginalName();
                $fileName = pathinfo($fileNameWithext, PATHINFO_FILENAME);
                $extension = $request->file('slider_image')->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
                $path = $request->file('slider_image')->storeAs('public/slider_images', $fileNameToStore);
                $slider->update([
                    'description1' => $validated['description1'],
                    'description2' => $validated['description2'],
                    'slider_image' => $fileNameToStore
                ]);
            }

            $slider->update([
                'description1' => $validated['description1'],
                'description2' => $validated['description2'],
            ]);
            return redirect('/sliders')->with('success', 'Product updated successfully');

    }
    public function deleteslider($id){
        $slider = Slider::findOrFail($id);
        unlink('storage/slider_images/'.$slider->slider_image);
        $slider->delete();
        return redirect('/sliders')->with('success', 'Product deleted successfully');
    }

    public function unactivateslider($id){
        $slider = Slider::findOrFail($id);
        $slider->status = 0;
        $slider->save();
        return redirect('/sliders')->with('success', 'Slider unactivated successfully');
    }
    public function activateslider($id){
        $slider = Slider::findOrFail($id);
        $slider->status = 1;
        $slider->save();
        return redirect('/sliders')->with('success', 'Slider activated successfully');
    }
}
