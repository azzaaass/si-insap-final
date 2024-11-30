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
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rfids as $rfid)
                        <tr class="bg-white border-b">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $rfid->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $rfid->name }}
                            </td>
                            <td class="px-6 py-4 flex">
                                <button id="log-button" onclick="showLog(this)" data-id="{{ $rfid->id }}"
                                    data-name="{{ $rfid->name }}" data-logs="{{ $rfid->logs->toJson() }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Log</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal logs --}}
    <div id="modalLogs" class="fixed inset-0 bg-gray-600 bg-opacity-20 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Log RFID</h2>
            <form id="logsItemForm">
                <div class="mb-4">
                    <label for="name_logs" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" id="name_logs" name=""
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="" readonly />
                </div>
                <div class="mb-4">
                    <label for="logs" class="block text-sm font-medium text-gray-700">Log RFID</label>
                    <div id="daftarLogs" class="mt-1 mb-4 space-y-4">
                        <div id="daftarLogs" class="mb-4">
                            <table class="w-full border-collapse border border-gray-300">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border border-gray-300 px-4 py-2 text-left font-normal text-sm">ID</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left font-normal text-sm">status
                                        </th>
                                        <th class="border border-gray-300 px-4 py-2 text-left font-normal text-sm">waktu
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="daftarLogsBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-4">
                    <button type="button" onclick="closeModal('modalLogs')"
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

        function showLog(element) {
            console.log('test')
            const logs = $(element).data('logs');
            $('#name_logs').val($(element).data('name'));

            $('#daftarLogsBody').empty();
            logs.forEach(element => {
                $('#daftarLogsBody').append(`
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 text-sm">${element.id}</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm">${element.status}</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm">${formatTimestamp(element.created_at)}</td>
                    </tr>`);
            });
            openModal('modalLogs');
        }
    </script>
@endsection
