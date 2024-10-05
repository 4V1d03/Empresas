<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ //estos son los campos que se arrastran nueva mente (se vuelven a llenar) despues de guardar o editar
        'name',
        'email',
        'password',
        'country_id',
        'state_id',
        'city_id',
        'address',
        'postal_code'
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function country() //crea la relacion entre user y country
    {
        return $this->belongsTo(Country::class);
    }

    public function calendars() //relacion entre usuarios y calendarios
    {
        return $this->belongsToMany(Calendar::class);//relacion de muchos a muchos
    }

    public function departaments() //relacion entre usuarios y demartamentos
    {   //con las relaciones ToMany se puede acceder a las tablas pivotes y sus modelos
        return $this->belongsToMany(Departament::class);//relacion de muchos a muchos
    }

    public function holidays()
    {
        return $this->hasMany(Holiday::class);
    }

    public function timessheets(){
        return $this->hasMany(Timesheet::class);
    }


}
