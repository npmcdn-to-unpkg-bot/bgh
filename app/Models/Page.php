<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $table = 'pages';
    protected $softDelete = true;
    protected $dates = ['deleted_at'];

    public function getTitleAttribute($value)
    {
        return ucfirst($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
