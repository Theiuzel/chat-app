<?php

// app/Models/Chatroom.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chatroom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_group']; // Add other fillable fields as needed

    // Define relationships or other methods as needed
}

