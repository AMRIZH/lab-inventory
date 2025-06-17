@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="text-center px-6">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-4">
            Sistem Inventaris Lab<br class="hidden sm:block"> Teknik Informatika
        </h1>
        <p class="text-gray-600 text-base mb-6">
            Selamat datang di sistem manajemen inventaris laboratorium jurusan Teknik Informatika.
        </p>
        <a href="{{ route('dashboard') }}"
            class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded text-lg font-medium shadow">
            Masuk ke Dashboard
        </a>
    </div>
</div>
@endsection