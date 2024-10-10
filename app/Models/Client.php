<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'address',
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string'
    ];

    public function employees()
    {
        return $this->belongsToMany(User::class);
    }
    public function prospects()
    {
        return $this->hasMany(Prospect::class);
    }
    public static function getClientsByEnterpriseId($enterprise_id)
    {
        return self::whereHas('employees', function ($query) use ($enterprise_id) {
            $query->whereHas('enterprise', function ($query) use ($enterprise_id) {
                $query->where('id', $enterprise_id);
            });
        })->get();
    }
}
