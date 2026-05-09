<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Riwayat Peminjaman Anda</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                <table class="min-w-full border border-gray-300">
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2">Alat</th>
                        <th class="border px-4 py-2">Tanggal</th>
                        <th class="border px-4 py-2">Status</th>
                    </tr>

                    @foreach($loans as $loan)
                        <tr>
                            <td class="border px-4 py-2">
                                {{ $loan->equipment->nama_alat }} (x{{ $loan->jumlah }})
                            </td>
                            <td class="border px-4 py-2">
                                {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y') }}
                            </td>
                            <td class="border px-4 py-2 font-bold">
                                {{ $loan->status }}
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

</x-app-layout>