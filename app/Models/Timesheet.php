<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{

    use HasFactory;
    protected $guarded = []; //desactiva la funcion de mass assignment

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }
}
