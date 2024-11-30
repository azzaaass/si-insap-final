@extends('template.sidebarStaff')

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
                            <td class="px-6 py-4">
                                <button id="edit-button" onclick="showEdit(this)" data-id="{{ $peminjamanBarang->id }}"
                                    data-name="{{ $peminjamanBarang->name }}"
                                    data-catatan-peminjaman="{{ $peminjamanBarang->catatan_peminjaman }}"
                                    data-image-url-pengembalian="{{ $peminjamanBarang->image_url_pengembalian ?? null }}"
                                    data-details="{{ $peminjamanBarang->detailPeminjamans->toJson() }}"
                                    class="font-medium text-blue-600 hover:underline">Preview</button>
                                @if ($peminjamanBarang->status == 'Proses')
                                    <span>|</span>
                                    <button id="selesaikan-button" onclick="showSelesaikan(this)" data-id="{{ $peminjamanBarang->id }}"
                                        data-name="{{ $peminjamanBarang->name }}"
                                        class="font-medium text-green-600 hover:underline">Selesaikan</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Floating create button --}}
    <button type="button" onclick="openModal('modalAddBarang')"
        class="fixed flex items-center gap-3 bottom-10 right-10 bg-blue-500 text-white p-4 rounded-full shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"></path>
        </svg>
        <p class="font-semibold">Buat peminjaman</p>
    </button>

    {{-- Modal Add --}}
    <div id="modalAddBarang" class="fixed inset-0 bg-gray-600 bg-opacity-20 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Buat peminjaman</h2>
            <form action="/peminjaman" method="POST" id="createItemForm">
                @csrf
                <input type="text" name="staff_id" value="{{ auth()->user()->id }}" class="hidden">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama peminjam</label>
                    <input type="text" id="name" name="name"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Masukkan nama peminjam"
                        required />
                </div>
                <div class="mb-4">
                    <label for="catatan_peminjaman" class="block text-sm font-medium text-gray-700">Catatan
                        peminjaman</label>
                    <textarea type="text" id="catatan_peminjaman" name="catatan_peminjaman"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Masukkan catatan peminjaman" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Pilih barang</label>
                    <select name="" id="barangDropdown"
                        class="mt-1 px-2 py-2.5 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="0" selected disabled>Pilih barang</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}" data-name="{{ $barang->name }}"
                                data-stock="{{ $barang->stock_in - $barang->stock_out }}">{{ $barang->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="barangs" class="block text-sm font-medium text-gray-700">Daftar barang dipinjam</label>
                    <div id="daftarBarang" class="mb-4 space-y-4"></div>
                </div>

                <div class="flex justify-end gap-4">
                    <button type="button" onclick="closeModal('modalAddBarang')"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Tutup</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Simpan</button>
                </div>
            </form>
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
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        placeholder="Masukkan nama peminjam" readonly />
                </div>
                <div class="mb-4">
                    <label for="catatan_peminjaman_edit" class="block text-sm font-medium text-gray-700">Catatan
                        peminjaman</label>
                    <textarea type="text" id="catatan_peminjaman_edit" name=""
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Masukkan catatan peminjaman" readonly></textarea>
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

    {{-- Modal selesaikan --}}
    <div id="modalSelesaikanBarang"
        class="fixed inset-0 bg-gray-600 bg-opacity-20 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Selesaikan peminjaman</h2>
            <form action="" method="POST" id="selesaikanItemForm" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="mb-4">
                    <label for="name_selesaikan" class="block text-sm font-medium text-gray-700">Nama peminjam</label>
                    <input type="text" id="name_selesaikan" name=""
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        placeholder="Masukkan nama peminjam" readonly />
                </div>
                <div class="mb-4">
                    <label for="catatan_pengembalian_edit" class="block text-sm font-medium text-gray-700">Catatan
                        pengembalian</label>
                    <textarea type="text" id="catatan_pengembalian_edit" name="catatan_pengembalian"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Masukkan catatan peminjaman"></textarea>
                </div>
                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700">Gambar</label>
                    <input type="file" id="image" name="image"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                </div>
                <div class="flex justify-end gap-4">
                    <button type="button" onclick="closeModal('modalSelesaikanBarang')"
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

        // generate barang
        $('#barangDropdown').on('change', function() {
            const barangId = $(this).val(); // ID barang
            const barangName = $(this).find(':selected').data('name'); // Nama barang
            const barangStock = $(this).find(':selected').data('stock'); // Nama barang

            if (barangId) {
                // Cek apakah barang sudah ada dalam daftar
                if ($(`#barang-${barangId}`).length) {
                    alert('Barang ini sudah ada dalam daftar!');
                    return;
                }

                // Buat elemen baru untuk barang
                const idInput = `<input type="text" name="barang_id[]" value="${barangId}" class="hidden">`;
                const nameInput =
                    `<input type="text" value="${barangName}" class="w-1/2 border-gray-300 rounded-md shadow-sm bg-gray-100" readonly>`;
                const jumlahInput =
                    `<input type="number" min="0" max="${barangStock}" name="barang_jumlah[]" placeholder="Jumlah" class="w-1/4 border-gray-300 rounded-md shadow-sm" required>`;
                const removeButton =
                    `<button type="button" class="px-2 py-1 bg-red-500 text-white rounded-md remove-item" data-id="${barangId}">Hapus</button>`;

                // Tambahkan elemen ke kontainer
                const itemContainer =
                    `<div id="barang-${barangId}" class="flex items-center gap-4">${idInput}${nameInput}${jumlahInput}${removeButton}</div>`;
                $('#daftarBarang').append(itemContainer);
            }
        });

        // Hapus barang dari daftar
        $(document).on('click', '.remove-item', function() {
            const barangId = $(this).data('id');
            $(`#barang-${barangId}`).remove();
        });

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

        // selesaikan peminjaman
        // $('#selesaikan-button').on('click', function() {
        function showSelesaikan(element){
            const details = $(element).data('details');
            $('#name_selesaikan').val($(element).data('name'));
            $('#selesaikanItemForm').attr('action', '/peminjaman/' + $(element).data('id'));
            // $('#catatan_peminjaman_selesaikan').text($(element).data('catatan-peminjaman'));

            openModal('modalSelesaikanBarang');
        }
        // });
    </script>
@endsection
