<?php

namespace App\Models\Content;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes, Sluggable;

    protected $guarded = ['id'];

    protected $casts = ['image' => 'array'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function postCategory()
    {
       return $this->belongsTo(PostCategory::class, 'category_id'); 
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }


}
