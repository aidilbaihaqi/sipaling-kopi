<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApiKey extends Model
{
    use HasFactory;
    protected $fillable = ['api_key', 'app_name', 'status'];
}
