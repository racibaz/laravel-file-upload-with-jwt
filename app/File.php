<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{

    public $timestamps = true;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'str_id', 'title', 'slug', 'image', 'file',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
