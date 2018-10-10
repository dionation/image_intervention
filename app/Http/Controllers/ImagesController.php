<?php

namespace App\Http\Controllers;


use ImageIntervention;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class ImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items= Image::all();
        return view('home',compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $image = $request->file('image');
        $filename = time().$image->hashName();
        
        

        // original
        $original = ImageIntervention::make($image);
        $original->save('storage/images/originals/'.$filename);
        // Storage::put('public/images/originals/'.$filename, $original);

        // resizer
        $resize = ImageIntervention::make($image)->resize(100, 100);
        $resize->save('storage/images/thumbnails/'.$filename);
        // Storage::put('public/images/thumbnails/'.$filename, $resize);
        // record in db
        $table = new Image;
        $table->name = $filename;
        $table->save();
      

        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Image $image)
    {
        $item = Image::find($id);
        Storage::disk('local')->delete('public/images/thumbnails/'.$item->name);
        Storage::disk('local')->delete('public/images/originals/'.$item->name);
        $item->delete();
        return redirect()->back();
    }
}
