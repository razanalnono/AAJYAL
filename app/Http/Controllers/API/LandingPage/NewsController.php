<?php

namespace App\Http\Controllers\API\LandingPage;

use App\Http\Controllers\Controller;

use App\Models\News;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return News::with('images')->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, News $news)
    {
        //
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $data =  $request->except('images');
        $news = News::create($data);

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
       // return $request;
        foreach ($request->images as $image) {
            $data = [];
            $data['images'] = $this->uploadImage($image);

           $news->images()->create($data);

            // $data['reference_id'] = $news->id;
            // $data['reference_type'] = $news->getMorphClass();

            // Image::create($data);
        }


        return response()->json([
            'message' => 'News Added Successfully',
            'news' => $news,
            'code' => '201'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        //
        return $news->load('title');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $news)
    {
        //
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'in_slider' => 'boolean',
            'deleted_images' => 'array',
        ]);


        $data =  $request->except(['images', 'deleted_images']);
        $news->update($data);

        $old_image = $news->images;
        $data = $request->except(['images', 'deleted_images']);

        // $new_image = $this->uploadImage($request);

        if ($request->deleted_images) {
            foreach ($news->images()->whereIn('id', $request->deleted_images)->get() as $img) {
                $img->delete();
                Storage::disk('public')->delete($img);
            }
        }
        if ($request->images) {
            foreach ($request->images as $image) {
                $data = [];
                $data['images'] = $this->uploadImage($image);

                $news->images()->create($data);

                // $data['reference_id'] = $news->id;
                // $data['reference_type'] = $news->getMorphClass();
                // Image::create($data);
            }
        }
        // if ($new_image) {
        //     $data = [];
        //     $data['images'] = $new_image;

        //     $data['reference_id'] = $news->id;
        //     $data['reference_type'] = $news->getMorphClass();

        // لا تنسي موضوع أن يحذفها من الفايل


        //     if ($old_image && $new_image) {
        //         Storage::disk('public')->delete($old_image);
        //     }

        //     Image::create($data);
        // }


        return response()->json([
            'message' => 'Updated',
            'news' => $news,
            'code' => '201'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        //
        $news->delete();
        //$news->images()->delete();

        return response()->json([
            'message' => 'News Deleted Successfully'
        ]);
    }

    protected function uploadImage($file)
    {

        $path = $file->store('uploads', [
            'disk' => 'public'
        ]);
        return $path;
    }


    // protected function uploadImage(Request $request)
    // {
    //     if (!$request->hasFile('images')) {
    //         return;
    //     }

    //     $file = $request->file('images'); // UploadedFile Object

    //     $path = $file->store('uploads', [
    //         'disk' => 'public'
    //     ]);
    //     return $path;
    // }
}