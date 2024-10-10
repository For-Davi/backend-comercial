<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    use HasFactory;

    protected $table = 'enterprises';

    protected $fillable = [
        'id',
        'name',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function users()
    {
        return $this->hasMany(User::class, 'enterprise_id');
    }
}
