<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class ProductInfo extends Model
{
    // reb activando, el delete en vez de borrar el registro, le pone fecha de baja (baja logica)
    // use SoftDeletes;

    protected $table = 'products_info';

    protected $fillable = ['cover_image', 'is_microsite'];

    public function getDates()
    {
        return ['created_at', 'updated_at', 'deleted_at', 'taken_at'];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}