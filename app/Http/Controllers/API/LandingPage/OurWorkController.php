<?php

namespace App\Http\Controllers\Api\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\OurWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\OurWorkController as ModelsOurWorkController;

class OurWorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return OurWork::with('images')->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,OurWork $Work)
    {
        //

        
        $request->validate([
            'report' => 'required',
        ]);

        $data =  $request->except('images');
        $work = OurWork::create($data);

        if (!$request->images) {
            $request->offsetSet('images', []);
        }

        if (!is_array($request->images)) {
            if (is_array(json_decode($request->images))) {
                $request->offsetSet('images', json_decode($request->images));
            } else {
                $request->offsetSet('images', []);
            }
        }
        
        foreach ($request->images as $image) {
         // $data=[];
            $data['images'] = $this->uploadImage($image);
            $work->images()->create($data);

        }


        return response()->json([
            'message' => 'Work Added Successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OurWork $work)
    {
        //
        return $work->load('report');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,OurWork $work)
    {
        //

        //
        $request->validate([
            'report' => 'required',
      
            'deleted_images' => 'array',
        ]);


        $data =  $request->except(['images', 'deleted_images']);
        $work->update($data);

        $old_image = $work->images;
        $data = $request->except(['images', 'deleted_images']);


        if ($request->deleted_images) {
            foreach ($work->images()->whereIn('id', $request->deleted_images)->get() as $img) {
                $img->delete();
                Storage::disk('public')->delete($img);
            }
        }
        if ($request->images) {
            foreach ($request->images as $image) {
                $data = [];
                $data['images'] = $this->uploadImage($image);

                $work->images()->create($data);

      
            }
        }


        return response()->json([
            'message' => 'Updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OurWork $ourWork)
    {
        //
        //return $ourWork;
        $ourWork->delete();

        return response()->json([
            'message' => 'ًWork Deleted Successfully'
        ]);
    }

    protected function uploadImage($file)
    {

        $path = $file->store('uploads', [
            'disk' => 'public'
        ]);
        return $path;
    }
}