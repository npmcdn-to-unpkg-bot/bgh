<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Profiled extends Model
{
    // use SoftDeletes;

    // extiende el foreignkey a profiles
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // se fija si el usuario auth tiene como perfiles disponibles el perfil del objeto en cuestion
    public function canHandle(){
        if( auth()->check() && (auth()->user()->isSuper() || auth()->user()->profiles->contains($this->profile->id)) ){
            return true;
        }
        return false;
    }

}
