<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Laporan & Persetujuan Peminjaman</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
                @endif

                <table class="min-w-full border border-gray-300">
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2">Peminjam</th>
                        <th class="border px-4 py-2">Alat</th>
                        <th class="border px-4 py-2">Status</th>
                        <th class="border px-4 py-2">Waktu Disetujui</th>
                        <th class="border px-4 py-2">Tanggal Kembali</th>
                        <th class="border px-4 py-2 text-center">Aksi (Admin)</th>
                    </tr>
                    @foreach ($loans as $loan)
                    <tr>
                        <td class="border px-4 py-2">{{ $loan->user->name }}</td>
                        <td class="border px-4 py-2">{{ $loan->equipment->nama_alat }} (x{{ $loan->jumlah }})</td>
                        <td class="border px-4 py-2 font-bold">{{ $loan->status }}</td>
                        <td class="border px-4 py-2">
                            @if($loan->approved_at)
                                {{ \Carbon\Carbon::parse($loan->approved_at)->format('d M Y H:i') }}
                            @else
                                <span class="text-gray-400">Belum disetujui</span>
                            @endif
                        </td>
                        <td class="border px-4 py-2">
                            @if($loan->return_date)
                                {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y H:i') }}
                            @else
                                <span class="text-gray-400">Belum dikembalikan</span>
                            @endif
                        </td>
                        <td class="border px-4 py-2 text-center">
                            @if ($loan->status == 'Menunggu Persetujuan Pinjam')
                                <form action="{{ route('loans.approve_borrow', $loan->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="bg-blue-600 text-white py-1 px-3 rounded">Setujui Pinjam</button>
                                </form>
                                <form action="{{ route('loans.reject_borrow', $loan->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="bg-red-600 text-white py-1 px-3 rounded ml-2">Tolak Pinjam</button>
                                </form>
                            @elseif($loan->status == 'Menunggu Persetujuan Kembali')
                                <form action="{{ route('loans.approve_return', $loan->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="bg-green-600 text-white py-1 px-3 rounded">Tandai Dikembalikan</button>
                                </form>
                            @else
                                <span class="text-gray-400">Tidak ada aksi</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</x-app-layout>