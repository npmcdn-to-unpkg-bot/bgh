<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class MediaInfo extends Model
{
    // reb activando, el delete en vez de borrar el registro, le pone fecha de baja (baja logica)
    // use SoftDeletes;

    protected $table = 'media_info';

    protected $fillable = [
            'camera',
            'focal_length',
            'shutter_speed',
            'aperture',
            'iso',
            'width',
            'height',
            'mime_type',
            'original',
            'copyright',
            'resolution',
            'software',
            'taken_at',
            'orientation'
            ];

    public function getDates()
    {
        return ['created_at', 'updated_at', 'deleted_at', 'taken_at'];
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

}