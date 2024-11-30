@extends('template.sidebarAdmin')

@section('container')
    <div class="p-4">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Peminjam
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Staff
                        </th>
                        <th scope="col" class="px-6 py-3">
                            RFID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jam
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peminjamanBarangs as $peminjamanBarang)
                        <tr class="bg-white border-b">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $peminjamanBarang->name }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $peminjamanBarang->staff?->name ?? 'Belum ada' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $peminjamanBarang->rfid?->name ?? 'Belum ada' }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($peminjamanBarang->status == 'Selesai')
                                    <p class="bg-green-700 px-4 py-1 w-fit rounded-md text-xs text-white font-semibold">
                                        {{ $peminjamanBarang->status }}
                                    </p>
                                @elseif($peminjamanBarang->status == 'Proses')
                                    <p class="bg-blue-700 px-4 py-1 w-fit rounded-md text-xs text-white font-semibold">
                                        {{ $peminjamanBarang->status }}
                                    </p>
                                @elseif($peminjamanBarang->status == 'Admin')
                                    <p class="bg-yellow-700 px-4 py-1 w-fit rounded-md text-xs text-white font-semibold">
                                        {{ $peminjamanBarang->status }}
                                    </p>
                                @else
                                    <p class="bg-red-700 px-4 py-1 w-fit rounded-md text-xs text-white font-semibold">
                                        {{ $peminjamanBarang->status }}
                                    </p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                {{ $peminjamanBarang->created_at }}
                            </td>
                            <td class="px-6 py-4 flex">
                                @if ($peminjamanBarang->status != 'Selesai')
                                    <button id="edit-button" onclick="showEdit(this)" data-id="{{ $peminjamanBarang->id }}"
                                        data-name="{{ $peminjamanBarang->name }}"
                                        data-catatan-peminjaman="{{ $peminjamanBarang->catatan_peminjaman }}"
                                        data-image-url-pengembalian="{{ $peminjamanBarang->image_url_pengembalian ?? null }}"
                                        data-details="{{ $peminjamanBarang->detailPeminjamans->toJson() }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal edit --}}
    <div id="modalEditBarang" class="fixed inset-0 bg-gray-600 bg-opacity-20 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Buat peminjaman</h2>
            <form action="" method="POST" id="editItemForm">
                @method('PUT')
                @csrf
                <input type="text" name="admin_id" value="{{ auth()->user()->id }}" class="hidden">
                <div class="mb-4">
                    <label for="name_edit" class="block text-sm font-medium text-gray-700">Nama peminjam</label>
                    <input type="text" id="name_edit" name=""
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Masukkan nama peminjam"
                        readonly />
                </div>
                <div class="mb-4">
                    <label for="catatan_peminjaman_edit" class="block text-sm font-medium text-gray-700">Catatan
                        peminjaman</label>
                    <textarea type="text" id="catatan_peminjaman_edit" name=""
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Masukkan catatan peminjaman" readonly></textarea>
                </div>
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status"
                        class="mt-1 px-2 py-2.5 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="Proses" selected>Proses</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="rfid_edit" class="block text-sm font-medium text-gray-700">RFID</label>
                    <select name="rfid_id" id="rfid_edit"
                        class="mt-1 px-2 py-2.5 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="0" selected disabled>Pilih kartu</option>
                        @foreach ($rfids as $rfid)
                            <option value="{{ $rfid->id }}">{{ $rfid->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="barangs" class="block text-sm font-medium text-gray-700">Daftar barang dipinjam</label>
                    <div id="daftarBarang" class="mt-1 mb-4 space-y-4">
                        <div id="daftarBarang" class="mb-4">
                            <table class="w-full border-collapse border border-gray-300">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border border-gray-300 px-4 py-2 text-left font-normal text-sm">ID</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left font-normal text-sm">Nama
                                            Barang</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left font-normal text-sm">Jumlah
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="daftarBarangBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="image_url_pengembalian" class="block text-sm font-medium text-gray-700">Gambar pengembalian</label>
                    <img id="image_url_pengembalian" src="" alt="gambar belum diupload" class="h-32 object-cover">
                </div>
                <div class="flex justify-end gap-4">
                    <button type="button" onclick="closeModal('modalEditBarang')"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Tutup</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(idModal) {
            $('#' + idModal).removeClass('hidden');
        }

        function closeModal(idModal) {
            $('#' + idModal).addClass('hidden');
        }

        // edit barang
        function showEdit(element) {
            const details = $(element).data('details');
            $('#name_edit').val($(element).data('name'));
            $('#editItemForm').attr('action', '/peminjaman/' + $(element).data('id'));
            $('#catatan_peminjaman_edit').text($(element).data('catatan-peminjaman'));
            $('#image_url_pengembalian').attr('src', $(element).data('image-url-pengembalian'));

            if($(element).data('image-url-pengembalian') == ''){
                $('#image_url_pengembalian').addClass('hidden');
            }

            $('#daftarBarangBody').empty();
            details.forEach(element => {
                $('#daftarBarangBody').append(`
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 text-sm">${element.barang_id}</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm">${element.barang.name}</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm">${element.jumlah}</td>
                    </tr>`);
            });
            openModal('modalEditBarang');
        }
    </script>
@endsection
