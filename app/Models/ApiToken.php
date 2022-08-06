<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApiToken extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'value',
    ];

    /**
     * The attributes that should be casted.
     *
     * @var array<int, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the parent user model.
     */
    public function user()
    {
        return $this->morphTo();
    }

    /**
     * Set hashed value attribute.
     */
    public function setValueAttribute(string $value)
    {
        $this->attributes['value'] = \hash('sha256', $value);
    }
}
