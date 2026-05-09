<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tempat Sampah Alat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-bold">Daftar Alat Dihapus</h3>
                    <a href="{{ route('equipments.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali ke Daftar Alat</a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2">Nama Alat</th>
                            <th class="border border-gray-300 px-4 py-2">Kategori</th>
                            <th class="border border-gray-300 px-4 py-2">Waktu Dihapus</th>
                            <th class="border border-gray-300 px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trashedEquipments as $item)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $item->nama_alat }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $item->category->nama_kategori ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $item->deleted_at->format('d M Y, H:i') }}</td>
                            <td class="border border-gray-300 px-4 py-2 flex space-x-2 justify-center">
                                
                                <!-- Restore Button -->
                                <form action="{{ route('equipments.restore', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                        Restore (Pulihkan)
                                    </button>
                                </form>

                                <!-- Hard Delete Button DENGAN CAUTION -->
                                <form action="{{ route('equipments.force_destroy', $item->id) }}" method="POST" onsubmit="return confirm('🚨 CAUTION 🚨\nYakin ingin menghapus permanen alat ini?\n\nData akan dihapus sepenuhnya dari database dan TIDAK BISA DIKEMBALIKAN!');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                        Hapus Permanen
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">Tempat sampah kosong.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>