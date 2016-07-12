<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{

    protected $table = 'blogs';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
