<?php

namespace App\Http\Controllers;

use App\Models\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImagesController extends Controller
{
    //

    public function store(Request $request)
    {

        $data['images'] = $this->uploadImage($request);
        $images = Images::create($data);
        return response()->json(['message'=>'Image Added Successfully']);

    }

    public function update(Request $request, Images $images)
    {
        $old_image = $images->images;
        $new_image = $this->uploadImage($request);
        $data=[];
        if ($new_image) {
            $data['images'] = $new_image;
        }

        $images->update($data);

        if ($old_image && $new_image) {
            Storage::disk('public')->delete($old_image);
        }

        return response()->json([
            'message' => 'Updated Successfully'
        ]);
    }


    public function destroy(Images $images)
    {
        $images->delete();
        return response()->json([
            'message' => 'Image Deleted Successfully'
        ]);
    }

    protected function uploadImage(Request $request)
    {
        if (!$request->hasFile('images')) {
            return;
        }

        $file = $request->file('images'); // UploadedFile Object

        $path = $file->store('uploads', [
            'disk' => 'public'
        ]);
        return $path;
    }
}