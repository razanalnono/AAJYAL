<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;

class Trainee extends User
{
    use HasFactory, HasApiTokens,Notifiable;
    protected $fillable = [
        'firstName', 'lastName',
        'national_id', 'gender',
        'mobile', 'avatar',
        'email',
        'carriage_price', 'address',
        'city_id', 'activation_code',
        'status',
    ];

    public function scopeFilter(Builder $builder, $filters)
    {
        $builder->when($filters['trainee_name'] ?? false, function ($builder, $value) {
            $builder->where('firstName', 'Like', '%' . $value . '%')
                ->orWhere('lastName', 'Like', '%' . $value . '%');
        });
    }

    public static function rules($id = 0)
    {
        return [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', "unique:trainees,email,$id"],
            'national_id' => ['required', 'string', "unique:trainees,national_id,$id"],
            'gender' => ['required', 'in:female,male'],
            'mobile' => ['required', 'string', 'max:255', "unique:trainees,mobile,$id"],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg'],
            'groups' => ['required'],
            'carriage_price' => ['nullable', 'numeric'],
            'address' => ['nullable', 'string'],
            'city_id' => ['nullable', 'int', 'exists:cities,id'],
        ];
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_trainee', 'trainee_id', 'group_id', 'id', 'id');
    }
    public function achievements()
    {
        return $this->hasMany(Achievement::class, 'trainee_id', 'id');
    }
    public function city()
    {
        return $this->belongsTo(city::class, 'city_id', 'id');
    }
    public function presence_absence()
    {
        return $this->hasMany(PresenceAbsence::class, 'trainee_id');
    }
    public function rates()
    {
        return $this->hasMany(Rate::class, 'course_id', 'id')->withDefault();
    }
    public function setfullNameAttribute()
    {
        return ($this->firstName) . ' ' . ($this->lastName);
    }
}