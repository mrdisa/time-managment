<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function users_on_project(){
        return $this->belongsToMany(User::class);
    }

    public function users_project_time(){
        return $this->belongsToMany(User::class, 'project_time');
    }

    protected $fillable = [
        'name',
    ];
}
