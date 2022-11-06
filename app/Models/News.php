<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
  use HasFactory;

  protected $fillable = ['title', 'description', 'in_slider', 'deleted_images','images'];

  public function images()
  {
    return $this->morphMany(Image::class, 'reference');
  }

  protected static function boot()
  {
    parent::boot();

    static::deleted(function ($news) {
      $news->images()->delete();
      foreach ($news->images() as $img) {

        Storage::disk('public')->delete($img);
   } });
  }
}