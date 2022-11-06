<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Admin as Authenticatable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Admin extends User
{
    use HasFactory, HasRoles, HasApiTokens,Notifiable;


    protected $guard = "admin";
    protected $guard_name = "admin";

    protected $fillable = [
        'firstName', 'lastName',
        'nationalID', 'gender',
        'mobile', 'avatar',
        'email', 'password',
    ];

    public static function rules($id = 0)
    {
        return [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', "unique:trainees,email,$id"],
            'nationalID' => ['required', 'numeric'],
            'gender' => ['required', 'in:female,male'],
            'mobile' => ['required', 'string', 'max:255', "unique:trainees,mobile,$id"],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg'],
            'groups' => ['required'],
            'carriage_price' => ['nullable', 'numeric'],
            'address' => ['nullable', 'string'],
            'city_id' => ['nullable', 'int', 'exists:cities,id'],
        ];
    }

    public function setfullNameAttribute()
    {
        return ($this->firstName) . ' ' . ($this->lastName);
    }
}