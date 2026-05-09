<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // Tambahin baris ini untuk mematikan fitur otomatis created_at & updated_at
    public $timestamps = false; 

    protected $fillable = ['nama_kategori'];

    // Relasi: Satu kategori punya banyak alat
    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }
}