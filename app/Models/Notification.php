<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
class Notification extends Model
{
    protected $table = 'notifications';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function image()
    {
        return $this->belongsTo(Image::class, 'on_id');
    }
}
