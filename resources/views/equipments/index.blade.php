<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Katalog Alat Laboratorium</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
                @endif

                @if(auth()->user()?->role == 'admin')
                <div class="mb-4 flex gap-2">
                    <a href="{{ route('equipments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">+ Tambah Alat Baru</a>
                    <!-- TOMBOL MENUJU TEMPAT SAMPAH -->
                    <a href="{{ route('equipments.trash') }}" class="bg-red-500 text-white px-4 py-2 rounded shadow hover:bg-red-600">🗑️ Tempat Sampah</a>
                </div>
                @endif
                
                <form method="GET" class="mb-4 flex gap-2">
                    <input type="text" name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama alat..."
                        class="border p-2 rounded w-1/3">

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                        Cari
                    </button>

                    @if(request('search'))
                    <a href="{{ route('equipments.index') }}" class="text-gray-600 underline self-center">
                        Reset
                    </a>
                    @endif
                </form>
                
                <table class="min-w-full border border-gray-300">
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2">Nama</th>
                        <th class="border px-4 py-2">Stok</th>
                        <th class="border px-4 py-2">Aksi</th>
                    </tr>
                    @foreach($all_equipment as $item)
                    <tr>
                        <td class="border px-4 py-2">{{ $item->nama_alat }}</td>
                        <td class="border px-4 py-2 text-center">{{ $item->stok == 0 ? 'Habis' : $item->stok }}</td>
                        <td class="border px-4 py-2">
                            <div class="flex gap-4 justify-center">

                                {{-- ADMIN ACTIONS --}}
                                @if(auth()->user()?->role == 'admin')
                                <a href="{{ route('equipments.edit', $item->id) }}" class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('equipments.increase', $item->id) }}" method="POST">@csrf <button class="text-green-600">+ Stok</button></form>
                                @if($item->stok > 0)
                                <form action="{{ route('equipments.decrease', $item->id) }}" method="POST">@csrf <button class="text-yellow-600">- Stok</button></form>
                                @endif
                                
                                <!-- TOMBOL SOFT DELETE DENGAN CAUTION -->
                                <form action="{{ route('equipments.destroy', $item->id) }}" method="POST" onsubmit="return confirm('PERHATIAN: Yakin ingin memindahkan alat ini ke tempat sampah? (Data masih bisa di-restore nantinya)');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700 font-semibold">Hapus (Ke Sampah)</button>
                                </form>
                                @endif

                                {{-- USER ACTIONS --}}
                                @if(auth()->user()?->role == 'user')
                                @php
                                $alreadyBorrowed = \App\Models\Loan::where('user_id', auth()->id())
                                ->where('equipment_id', $item->id)
                                ->whereIn('status',['Menunggu Persetujuan Pinjam','Dipinjam'])
                                ->exists();
                                @endphp

                                @if($alreadyBorrowed)
                                <button class="bg-gray-400 text-white py-1 px-3 rounded cursor-not-allowed" disabled>Sedang Dipinjam</button>
                                @elseif($item->stok > 0)
                                <form action="{{ route('loans.store') }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="equipment_id" value="{{ $item->id }}">
                                    <input type="number" name="jumlah" min="1" max="{{ $item->stok }}" value="1" class="w-16 border-gray-300 rounded text-sm py-1 px-2" required title="Jumlah Pinjam">
                                    <button class="bg-blue-600 text-white py-1 px-3 rounded hover:bg-blue-700">Pinjam</button>
                                </form>
                                @else
                                <span class="text-gray-400">Habis</span>
                                @endif
                                @endif

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>

            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal Dihapus!',
            text: "{{ session('error') }}",
        });
    </script>
@endif