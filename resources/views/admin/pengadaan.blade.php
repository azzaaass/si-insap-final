@extends('template.sidebarAdmin')

@section('container')
    <div class="p-4">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Pesan
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengadaans as $pengadaan)
                        <tr class="bg-white border-b">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $pengadaan->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $pengadaan->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $pengadaan->message }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Floating create button --}}
    <button type="button" onclick="openModal('modalAddPengadaan')"
        class="fixed flex items-center gap-3 bottom-10 right-10 bg-blue-500 text-white p-4 rounded-full shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"></path>
        </svg>
        <p class="font-semibold">Buat pengadaan</p>
    </button>

    {{-- Modal Add --}}
    <div id="modalAddPengadaan" class="fixed inset-0 bg-gray-600 bg-opacity-20 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Buat Pengadaan</h2>
            <form action="/pengadaan" method="POST" id="createItemForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" id="name" name="name"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Masukkan nama barang"
                        required />
                </div>
                <div class="mb-4">
                    <label for="message" class="block text-sm font-medium text-gray-700">Pesan</label>
                    <textarea type="number" id="message" name="message" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        placeholder="Masukkan jumlah stock" required></textarea>
                </div>
                <div class="flex justify-end gap-4">
                    <button type="button" onclick="closeModal('modalAddPengadaan')"
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
    </script>
@endsection
