<?php

namespace App\Http\Controllers\Api\LandingPage;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Page::select('bio', 'vision', 'goals', 'logo')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(Page::rules());
        $data = $request->except('logo');
        $data['logo'] = $this->uploadImage($request);
        $page = Page::create($data);

        return response()->json([
            'message' => 'Added Successfully',
            'data' => $page
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $request->validate(Page::rules());
        $old_image = $page->logo;
        $data = $request->except('logo');
        $new_image = $this->uploadImage($request);
        if ($new_image) {
            $data['logo'] = $new_image;
        }
        $page->update($data);
        if ($old_image && $new_image) {
            Storage::disk('public')->delete($old_image);
        }

        return response()->json([
            'message' => 'Updated Successfully',
            'data' => $page
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();
        if ($page->logo) {
            Storage::disk('public')->delete($page->logo);
        }

        return response()->json([
            'message' => 'Deleted Successfully'
        ]);
    }


    protected function uploadImage(Request $request)
    {
        if (!$request->hasFile('logo')) {
            return;
        }
        $file = $request->file('logo');
        $path = $file->store('uploads', [
            'disk' => 'public'
        ]);
        return $path;
    }
}
