<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Models\Trainee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TraineeController extends Controller
{
    //
    public function index()
    {
        //
        return Trainee::select('firstName', 'lastName', 'gender', 'avatar', 'email')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $data = $request->except('avatar');
        $data['avatar'] = $this->uploadImage($request);

        Trainee::create($request->post());
        return response()->json([
            'message' => 'trainee Created'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trainee $trainee)
    {
        //
        $old_image = $trainee->avatar;
        $data = $request->except('avatar');
        $new_image = $this->uploadImage($request);
        if ($new_image) {
            $data['avatar'] = $new_image;
        }

        $trainee->update($data);

        if ($old_image && $new_image) {
            Storage::disk('public')->delete($old_image);
        }

        return response()->json([
            'message' => 'Updated Successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trainee $trainee)
    {
        //
        $trainee->delete();
        return response()->json([
            'message' => 'Deleted Successfully'
        ]);
    }


    protected function uploadImage(Request $request)
    {
        if (!$request->hasFile('image')) {
            return;
        }

        $file = $request->file('image'); // UploadedFile Object

        $path = $file->store('uploads', [
            'disk' => 'public'
        ]);
        return $path;
    }
}