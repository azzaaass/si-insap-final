@extends('template.sidebarStaff')

@section('container')
    <div class="grid grid-cols-4 gap-6">
        @foreach ($barangs as $barang)
            <div class="bg-white rounded-lg shadow-md p-4">
                <img src="{{ asset($barang->image_url) }}" alt="{{ $barang->name }}" class="w-32 h-32 object-cover">
                <p class="font-bold">{{ $barang->name }}</p>
                <p>Tersedia: {{ $barang->stock_in - $barang->stock_out }}</p>
                <p>Stock masuk: {{ $barang->stock_in }}</p>
                <p>Stock keluar: {{ $barang->stock_out }}</p>
            </div>
        @endforeach
    </div>
@endsection
