<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    // 🔥 TAMBAHKAN 'jumlah' DISINI
    protected $fillable = ['user_id', 'equipment_id', 'jumlah', 'tanggal_pinjam', 'status', 'approved_at', 'return_date'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}