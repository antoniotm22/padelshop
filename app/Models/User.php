<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'puntos',
        'admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tipos(){
        return $this->belongsToMany(Tipo::class)->withTimestamps();
    }

    public function tiposAutorizados($tipos){
        abort_unless($this->hasAnyTipo($tipos),401);
        return true;
    }

    public function hasAnyTipo($tipos){
        if (is_array($tipos)){
            foreach ($tipos as $tipo){
                if ($this->hasTipo($tipo)){
                    return true;
                }
            }
        }
    }

    public function hasTipo($tipo){
        if ($this->tipos()->where('name',$tipo)->first()){
            return true;
        }
        return  false;
    }

}
