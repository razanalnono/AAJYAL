<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class InfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $info = Info::paginate();

        return $info;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:infos,email'],
            'address' => ['nullable', 'max:255'],
            'mobile' => ['string', 'max:255', 'unique:infos,mobile', 'nullable'],
            'telephone' => ['string', 'max:255', 'unique:infos,telephone', 'nullable'],
            'fax' => ['nullable', 'max:255', 'unique:infos,fax'],
        ]);

        $info = Info::create($request->all());

        return Response::json($info, 201);
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
        $request->validate([
            'email' => ['required', 'email', 'max:255', "unique:infos,email,$id"],
            'address' => ['nullable', 'max:255'],
            'mobile' => ['string', 'max:255', "unique:infos,mobile,$id", 'nullable'],
            'telephone' => ['string', 'max:255', "unique:infos,telephone,$id", 'nullable'],
            'fax' => ['nullable', 'max:255', "unique:infos,fax,$id"],
        ]);

        $info = Info::findOrFail($id);
        $info->update($request->all());

        return Response::json($info);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Info::destroy($id);

        return [
            'message' => 'Deleted Successfully.',
        ];
    }
}
