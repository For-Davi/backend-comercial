<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'mark',
        'model',
        'year',
        'price',
        'enterprise_id',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid()->toString();
            }
        });
    }

    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class, 'enterprise_id');
    }
    public static function getCarsByEnterpriseId($enterprise_id)
    {
        return self::where('enterprise_id', $enterprise_id)->get();
    }
}
