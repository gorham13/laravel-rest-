<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Post extends Model
{
    protected $fillable = [
        'title', 'article', 'image', 'user_id'
    ];

    public $sortFields = ['title','created_at'];

    public $rulesForCreate = [
        'title' => 'required|max:255',
        'article' => 'required',
        'image' => 'max:255'
    ];

    public $rulesForUpdate = [
        'title' => 'max:255',
        'image' => 'max:255'
    ];

    public function validateCustom($data, $rules)
    {
        $validator = Validator::make($data, $this->{$rules});

        if ($validator->fails())
        {
            $this->errors = $validator->errors();
            return false;
        }

        return true;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes(){
        return $this->hasMany(Like::class,'post_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'relation_tags_post', 'post_id', 'tag_id');
    }
}
