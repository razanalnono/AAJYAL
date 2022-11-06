<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Trainer extends User
{
    use HasFactory, HasApiTokens,Notifiable;

    protected $fillable = [
        'firstName', 'lastName',
        'nationalID', 'gender',
        'mobile', 'avatar',
        'email', 'password',
        'salary'
    ];
    public static function rules($id = 0)
    {
        return [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', "unique:trainees,email,$id"],
            'nationalID' => ['required', 'string', "unique:trainees,national_id,$id"],
            'gender' => ['required', 'in:female,male'],
            'mobile' => ['required', 'string', 'max:255', "unique:trainees,mobile,$id"],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg'],
            'groups' => ['required'],
            'carriage_price' => ['nullable', 'numeric'],
            'address' => ['nullable', 'string'],
            'city_id' => ['nullable', 'int', 'exists:cities,id'],
        ];
    }
    protected $appends = ['full_name'];

    public function course()
    {
        $this->hasOne(Course::class, 'trainer_id', 'id');
    }

    public function getfullNameAttribute()
    {
        return ($this->firstName) . ' ' . ($this->lastName);
    }
}