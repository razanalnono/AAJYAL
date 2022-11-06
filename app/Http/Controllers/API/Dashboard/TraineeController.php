<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Models\Trainee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Mail\Password;
use Illuminate\Support\Facades\Mail;
use Nette\Utils\Random;

class TraineeController extends Controller
{
    //
    public function index(Request $request)
    {
        $trainees = Trainee::with(['groups', 'city', 'achievements'])
            ->filter($request->query())->paginate();

        return $trainees;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Trainee $trainee)
    {
        $request->validate(Trainee::rules());
        // Create password for trainee
        $email = $request->email;
        $trainee = Trainee::where('email', $email)->first();
        $password = Random::generate('5');

        //Add avatar for trainee
        $data = $request->except(['avatar']);
        $data['password'] = Hash::make($password);
        $data['avatar'] = $this->uploadImage($request);

        //Add all data for trainee
        $trainee = Trainee::create($data);
        $trainee->groups()->attach($request->groups);

        //Send the password to email trainee
        Mail::to($trainee->email)->send(new Password($password));

        return response()->json([
            'message' => 'Trainee created successfully',
            'trainee'=>$trainee
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Trainee $trainee)
    {
        return $trainee->load(['groups', 'city', 'achievements']);
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
        $request->validate(Trainee::rules($id));

        $trainee = Trainee::findOrFail($id);
        $old_image = $trainee->avatar;
        $data = $request->except('avatar');
        $new_image = $this->uploadImage($request);
        if ($new_image) {
            $data['avatar'] = $new_image;
        }
        $trainee->update($data);
        $trainee->groups()->sync($request->groups);

        //Remove old image
        if ($old_image && $new_image) {
            Storage::disk('public')->delete($old_image);
        }
        return response()->json([
            'message' => 'Trainee updated successfully'
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
        $trainee = Trainee::findOrFail($id);
        $trainee->delete();
        if ($trainee->avatar) {
            Storage::disk('public')->delete($trainee->avatar);
        }
        return response()->json([
            'message' => 'Trainee deleted successfully'
        ]);
    }


    protected function uploadImage(Request $request)
    {
        if (!$request->hasFile('avatar')) {
            return;
        }
        // Uploaded Image
        $file = $request->file('avatar');
        $path = $file->store('uploads', [
            'disk' => 'public'
        ]);

        return $path;
    }
}