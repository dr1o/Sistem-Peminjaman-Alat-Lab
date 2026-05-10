<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index() {
        $loans = Loan::where('user_id', Auth::id())->with('equipment')->get();
        return view('loans.index', compact('loans'));
    }

    public function store(Request $request) {
        $request->validate([
            'equipment_id' => 'required',
            'jumlah' => 'required|integer|min:1'
        ]);

        $equipment = Equipment::findOrFail($request->equipment_id);

        if ($equipment->stok < $request->jumlah) {
            return back()->with('error', 'Stok alat tidak mencukupi untuk jumlah yang diminta!');
        }

        Loan::create([
            'user_id' => Auth::id(),
            'equipment_id' => $equipment->id,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => now(),
            'status' => 'Menunggu Persetujuan Pinjam',
        ]);

        return back()->with('success', 'Permintaan pinjam terkirim! Tunggu persetujuan Admin.');
    }

    public function returnEquipment($id) {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'Menunggu Persetujuan Kembali']);
        return back()->with('success', 'Permintaan kembali terkirim! Tunggu pengecekan Admin.');
    }

    public function adminIndex() {
        $loans = Loan::with(['user', 'equipment'])->orderBy('created_at', 'desc')->get();
        return view('loans.admin', compact('loans'));
    }

    public function approveBorrow($id) {
        $loan = Loan::findOrFail($id);
        $equipment = $loan->equipment;

        if ($equipment->stok < $loan->jumlah) {
            return back()->with('error','Stok alat habis atau tidak cukup! Tidak bisa menyetujui.');
        }

        $loan->update([
            'status' => 'Dipinjam',
            'approved_at' => now()
        ]);
        
        // Kurangi stok sesuai jumlah yang dipinjam
        $equipment->decrement('stok', $loan->jumlah);
        
        return back()->with('success', 'Peminjaman disetujui! Stok alat telah berkurang.');
    }

    public function approveReturn($id) {
    $loan = Loan::findOrFail($id);
    $loan->update([
        'status' => 'Dikembalikan',
        'return_date' => now(),
    ]);
    
    $loan->equipment->increment('stok', $loan->jumlah);
    
    return back()->with('success', 'Pengembalian disetujui. Stok telah ditambahkan kembali!');
}

    public function rejectBorrow($id) {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'Ditolak']);
        return back()->with('success', 'Peminjaman ditolak oleh admin.');
    }

    public function rejectReturn($id) {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'Pengembalian Ditolak']);
        return back()->with('success', 'Pengembalian ditolak oleh admin.');
    }
}