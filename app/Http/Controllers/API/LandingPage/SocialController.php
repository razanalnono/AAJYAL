<?php

namespace App\Http\Controllers\Api\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\Social;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class SocialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $social = Social::all();

        return $social;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(Social::rules());
        $data = $request->except('icon');
        $data['icon'] = $this->uploadImage($request);
        $social = Social::create($data);

        return Response::json($social, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate(Social::rules());
        $social = Social::findOrFail($id);
        $old_icon = $social->icon;
        $data = $request->except('icon');
        $new_icon = $this->uploadImage($request);
        if ($new_icon) {
            $data['image'] = $new_icon;
        }
        $social->update($data);
        if ($old_icon && $new_icon) {
            Storage::disk('public')->delete($old_icon);
        }

        return Response::json($social);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $social = Social::findOrFail($id);
        if ($social->icon) {
            Storage::disk('public')->delete($social->icon);
        }
        Social::destroy($id);

        return [
            'message' => 'Deleted Successfully.',
        ];
    }

    protected function uploadImage(Request $request)
    {
        if (!$request->hasFile('icon')) {
            return;
        }
        $file = $request->file('icon');
        $path = $file->store('uploads', [
            'disk' => 'public'
        ]);
        return $path;
    }
}
