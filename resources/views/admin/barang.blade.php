@extends('template.sidebarAdmin')

@section('container')
    <div class="grid grid-cols-4 gap-6">
        @foreach ($barangs as $barang)
            <div class="bg-white rounded-lg shadow-md p-4">
                <img src="{{ asset($barang->image_url) }}" alt="{{ $barang->name }}" class="w-32 h-32 object-cover">
                <p class="font-bold">{{ $barang->name }}</p>
                <p>Tersedia: {{ $barang->stock_in - $barang->stock_out }}</p>
                <p>Stock masuk: {{ $barang->stock_in }}</p>
                <p>Stock keluar: {{ $barang->stock_out }}</p>
                <div class="flex gap-4">
                    {{-- Edit button --}}
                    <button class="edit-btn bg-yellow-600 text-white h-fit py-1 px-5 rounded mt-2"
                        data-id="{{ $barang->id }}" data-image-url="{{ $barang->image_url }}"
                        data-name="{{ $barang->name }}" data-stock-in="{{ $barang->stock_in }}"
                        data-stock-out="{{ $barang->stock_out }}">
                        Edit
                    </button>
                    {{-- Delete button --}}
                    <button class="delete-btn bg-red-700 text-white h-fit py-1 px-5 rounded mt-2"
                        data-id="{{ $barang->id }}" data-name="{{ $barang->name }}">
                        Delete
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Floating create button --}}
    <button type="button" onclick="openModal('modalAddBarang')"
        class="fixed flex items-center gap-3 bottom-10 right-10 bg-blue-500 text-white p-4 rounded-full shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"></path>
        </svg>
        <p class="font-semibold">Tambah barang</p>
    </button>

    {{-- Modal Add --}}
    <div id="modalAddBarang" class="fixed inset-0 bg-gray-600 bg-opacity-20 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Tambah Barang</h2>
            <form action="/barang" method="POST" id="createItemForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700">Gambar</label>
                    <input type="file" id="image" name="image"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                    <input type="text" id="name" name="name"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Masukkan nama barang"
                        required />
                </div>
                <div class="mb-4">
                    <label for="stock_in" class="block text-sm font-medium text-gray-700">Stock</label>
                    <input type="number" id="stock_in" name="stock_in"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Masukkan jumlah stock"
                        required />
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

    <!-- Edit Modal Box -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-20 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Edit Barang</h2>
            <form action="" method="POST" id="editForm" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <input type="hidden" id="id_edit" name="id" />
                <img id="image_edit_preview" alt="Preview" class="mb-4 w-32 h-32 object-cover">
                <div class="mb-4">
                    <label for="image_edit" class="block text-sm font-medium text-gray-700">Gambar</label>
                    <input type="file" id="image_edit" name="image"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                </div>
                <div class="mb-4">
                    <label for="name_edit" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                    <input type="text" id="name_edit" name="name"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div class="mb-4">
                    <label for="stock_in_edit" class="block text-sm font-medium text-gray-700">Stock Masuk</label>
                    <input type="number" id="stock_in_edit" name="stock_in"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div class="mb-4">
                    <label for="stock_out_edit" class="block text-sm font-medium text-gray-700">Stock Keluar</label>
                    <input type="number" id="stock_out_edit" name="stock_out"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div class="flex justify-end gap-4">
                    <button type="button" onclick="closeModal('editModal')"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Tutup</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-30 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/2 lg:w-1/4">
            <h2 class="text-xl font-semibold mb-4">Konfirmasi Penghapusan</h2>
            <p>Apakah Anda yakin ingin menghapus barang <span class="text-red-800" id="barang_name"></span>?</p>
            <form action="" method="POST" id="deleteForm">
                @method('DELETE')
                @csrf
                <div class="flex justify-end gap-4 mt-4">
                    <button type="button" onclick="closeModal('deleteModal')"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Hapus</button>
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

        $(document).ready(function() {
            // Open the modal when the Edit button is clicked
            $('.edit-btn').on('click', function() {

                // Populate the modal with the current data
                $('#id_edit').val($(this).data('id'));
                $('#editForm').attr('action', '/barang/' + $(this).data('id'));
                $('#image_edit_preview').attr('src', $(this).data('image-url'));
                $('#name_edit').val($(this).data('name'));
                $('#stock_in_edit').val($(this).data('stock-in'));
                $('#stock_out_edit').val($(this).data('stock-out'));

                // Show the modal
                $('#editModal').removeClass('hidden');
            });

            $('.delete-btn').on('click', function() {
                var barangId = $(this).data('id');
                var barangName = $(this).data('name');

                $('#barang_name').text(barangName);
                $('#deleteForm').attr('action', '/barang/' + barangId);
                $('#deleteModal').removeClass('hidden');
            });


        });
    </script>
@endsection
