<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusUser extends Model
{
    protected $table ='user_type';
    protected $primaryKey = 'usertypeid';
    protected $fillable = [
        'usertypeid','typename'
    ];
}
