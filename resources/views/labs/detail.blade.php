@extends('layouts.app')

@section('content')
<div x-data="itemActions()" class="max-w-[1600px] mx-auto px-4 py-6">
    {{-- 🔔 Flash Messages --}}
    <div class="fixed top-4 right-4 z-50">
        @if(session('success'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-8"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-8"
            class="bg-green-50 text-green-800 px-6 py-3 rounded-lg shadow-lg border border-green-200 flex items-center mb-4">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-8"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-8"
            class="bg-red-50 text-red-800 px-6 py-3 rounded-lg shadow-lg border border-red-200 flex items-center mb-4">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            {{ session('error') }}
        </div>
        @endif
    </div>

    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $lab['lab_name'] ?? $lab['name'] }}</h1>
            <p class="text-gray-600 mt-1">Manajemen Inventaris Laboratorium</p>
        </div>

        {{-- Search bar with icon --}}
        <div class="relative max-w-xl w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text"
                placeholder="Cari nama item, kode unit, atau produk..."
                x-model="searchQuery"
                class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm text-gray-600 placeholder-gray-400">
        </div>
    </div> {{-- Daftar Item --}}
    <div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Daftar Item</h2>
            <div class="flex gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-blue-700">
                    Total: <span x-text="filteredItems.length" class="ml-1"></span>
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left px-6 py-3 text-sm font-semibold text-gray-600">Nama</th>
                        <th class="text-left px-6 py-3 text-sm font-semibold text-gray-600">Keterangan</th>
                        <th class="text-left px-6 py-3 text-sm font-semibold text-gray-600">Kode Inventaris</th>
                        <th class="text-center px-6 py-3 text-sm font-semibold text-gray-600">Jumlah</th>
                        <th class="text-center px-6 py-3 text-sm font-semibold text-gray-600">Status</th>
                        <th class="text-left px-6 py-3 text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="item in filteredItems" :key="item.id">
                        <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800" x-text="item.name"></div>
                            </td>
                            <td class="px-6 py-4">
                                <button @click="openTextModal('Keterangan Item', item.description)"
                                    class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat Detail
                                </button>
                            </td>
                            <td class="px-6 py-4">
                                <button @click="openKodeModal((item.item_units && Array.isArray(item.item_units)) ? item.item_units.map(u => (u && u.code) || '').join('\n') : '')"
                                    class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    Lihat Kode
                                </button>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-50 text-blue-700">
                                        <span x-text="(item.item_units && Array.isArray(item.item_units)) ? item.item_units.length : 0"></span>
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                        <span x-text="(item.item_units && Array.isArray(item.item_units)) ? item.item_units.filter(u => u && u.condition === 'Baik').length : 0"></span>
                                        <span class="ml-1">Baik</span>
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700">
                                        <span x-text="(item.item_units && Array.isArray(item.item_units)) ? item.item_units.filter(u => u && u.condition === 'Rusak').length : 0"></span>
                                        <span class="ml-1">Rusak</span>
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <button @click="openEditModal(item)"
                                        class="text-blue-600 hover:text-blue-800 transition-colors"
                                        title="Edit Item">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="openAddUnitModal(item.id)"
                                        class="text-green-600 hover:text-green-800 transition-colors"
                                        title="Tambah Unit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </button>
                                    <button @click="openManageUnit(item.item_units)"
                                        class="text-gray-600 hover:text-gray-800 transition-colors"
                                        title="Kelola Unit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                    <button @click="confirmDeleteItem(item.id)"
                                        class="text-red-600 hover:text-red-800 transition-colors"
                                        title="Hapus Item">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div> {{-- Daftar Produk --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Daftar Produk</h2>
            <div class="flex gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-50 text-purple-700">
                    Total: <span x-text="filteredProducts.length" class="ml-1"></span>
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left px-6 py-3 text-sm font-semibold text-gray-600">Nama Produk</th>
                        <th class="text-left px-6 py-3 text-sm font-semibold text-gray-600">Keterangan</th>
                        <th class="text-left px-6 py-3 text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="product in filteredProducts" :key="product.id">
                        <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800" x-text="product.product_name"></div>
                            </td>
                            <td class="px-6 py-4">
                                <button @click="openTextModal('Keterangan Produk', product.description)"
                                    class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat Detail
                                </button>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <button @click="openEditProduct(product)"
                                        class="text-blue-600 hover:text-blue-800 transition-colors"
                                        title="Edit Produk">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="confirmDeleteProduct(product.id)"
                                        class="text-red-600 hover:text-red-800 transition-colors"
                                        title="Hapus Produk">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Edit Item --}}
    <div x-show="open" x-cloak class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
        <div class="bg-white rounded p-6 w-full max-w-lg shadow">
            <h2 class="text-xl font-bold mb-4">Edit Item</h2>
            <form method="POST" action="#" @submit.prevent="submitEditForm($event)">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block font-medium">Nama</label>
                    <input type="text" name="name" :value="item.name" x-model="item.name"
                        class="w-full border rounded px-4 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block font-medium">Keterangan</label>
                    <textarea name="description" :value="item.description" x-model="item.description"
                        class="w-full border rounded px-4 py-2" rows="3"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="open = false"
                        class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Tambah Unit --}}
    <div x-show="showAddUnit" x-cloak class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
        <div class="bg-white rounded p-6 w-full max-w-2xl shadow">
            <h2 class="text-xl font-bold mb-4">Tambah Unit Barang</h2>
            <form method="POST" :action="`{{ route('items.units.store', ['item' => '__ITEM_ID__']) }}`.replace('__ITEM_ID__', selectedItemId)">
                @csrf

                <div class="max-h-[300px] overflow-y-auto space-y-4 mb-4 pr-1">
                    <template x-for="(unit, index) in newUnits" :key="index">
                        <div class="border p-4 rounded bg-white">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-semibold">Unit ke-<span x-text="index + 1"></span></span>
                                <button type="button" @click="newUnits.splice(index, 1)" class="text-red-600">🗑️ Hapus</button>
                            </div>
                            <label>Kode (opsional)</label>
                            <input type="text" :name="`units[${index}][code]`" x-model="unit.code"
                                class="w-full border rounded px-4 py-2 mb-2" placeholder="Misal: *000000001111*">

                            <label>Kondisi</label>
                            <select :name="`units[${index}][condition]`" x-model="unit.condition"
                                class="w-full border rounded px-4 py-2">
                                <option value="Baik">Baik</option>
                                <option value="Rusak">Rusak</option>
                            </select>
                        </div>
                    </template>
                </div>


                <div class="flex justify-between items-center">
                    <button type="button" @click="addNewUnit()" class="px-4 py-2 bg-gray-200 rounded">+ Tambah Unit</button>
                    <div class="space-x-2">
                        <button type="button" @click="showAddUnit = false" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Kelola Unit --}}
    <div x-show="showManageModal" x-cloak class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
        <div class="bg-white rounded p-6 w-full max-w-2xl shadow">
            <h2 class="text-xl font-bold mb-4">Kelola Unit Barang</h2>

            <form method="POST" action="{{ route('item-units.mass-update') }}">
                @csrf
                @method('PUT')

                <div class="max-h-[60vh] overflow-y-auto space-y-4 pr-1">
                    <template x-for="(unit, index) in manageUnits" :key="unit.id">
                        <div class="border p-4 rounded bg-white">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-semibold">Unit #<span x-text="index + 1"></span></span>
                                <button type="button" @click="confirmDeleteUnit(unit.id)" class="text-red-600">🗑️ Hapus</button>
                            </div>

                            <label>Kode</label>
                            <input type="text" :name="`unit_updates[${unit.id}][code]`" x-model="unit.code"
                                class="w-full border rounded px-4 py-2 mb-2">

                            <label>Kondisi</label>
                            <select :name="`unit_updates[${unit.id}][condition]`" x-model="unit.condition"
                                class="w-full border rounded px-4 py-2">
                                <option value="Baik">Baik</option>
                                <option value="Rusak">Rusak</option>
                            </select>
                        </div>
                    </template>
                </div>

                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" @click="showManageModal = false" class="px-4 py-2 bg-gray-300 rounded">Tutup</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Unit --}}
    <div x-show="showDeleteUnitModal" x-cloak class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
        <div class="bg-white rounded p-6 w-full max-w-sm shadow">
            <h2 class="text-xl font-semibold mb-4">Konfirmasi Hapus Unit</h2>
            <p class="mb-4">Yakin ingin menghapus unit ini? Tindakan ini tidak dapat dibatalkan.</p>

            <form method="POST" :action="`{{ route('item-units.destroy', ['id' => '__UNIT_ID__']) }}`.replace('__UNIT_ID__', deleteUnitId)">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showDeleteUnitModal = false" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Item --}}
    <div x-show="showDeleteItemModal" x-cloak class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
        <div class="bg-white rounded p-6 w-full max-w-sm shadow">
            <h2 class="text-xl font-semibold mb-4">Hapus Item?</h2>
            <p class="mb-4">Yakin ingin menghapus item ini beserta semua unit-nya?</p>

            <form method="POST" :action="`{{ route('items.destroy', ['id' => '__ITEM_ID__']) }}`.replace('__ITEM_ID__', deleteItemId)">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showDeleteItemModal = false" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Produk --}}
    <div x-show="showEditProduct" x-cloak class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
        <div class="bg-white rounded p-6 w-full max-w-lg shadow">
            <h2 class="text-xl font-bold mb-4">Edit Produk</h2>
            <form method="POST" action="#" @submit.prevent="submitEditProductForm($event)">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label>Nama Produk</label>
                    <input type="text" name="product_name" x-model="product.product_name" class="w-full border px-4 py-2 rounded">
                </div>

                <div class="mb-4">
                    <label>Deskripsi</label>
                    <textarea name="description" x-model="product.description" class="w-full border px-4 py-2 rounded"></textarea>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showEditProduct = false" class="bg-gray-300 px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Produk --}}
    <div x-show="showDeleteProduct" x-cloak class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
        <div class="bg-white rounded p-6 w-full max-w-sm shadow">
            <h2 class="text-xl font-semibold mb-4">Hapus Produk?</h2>
            <p class="mb-4">Yakin ingin menghapus produk ini?</p>

            <form method="POST" :action="`{{ route('products.destroy', ['id' => '__PRODUCT_ID__']) }}`.replace('__PRODUCT_ID__', deleteProductId)">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showDeleteProduct = false" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Detail Keterangan --}}
    <div x-show="showTextModal" x-cloak class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
        <div class="bg-white rounded-lg w-full max-w-lg p-6 shadow">
            <h2 class="text-xl font-bold mb-4" x-text="textModalTitle"></h2>
            <div class="max-h-[60vh] overflow-y-auto">
                <pre class="whitespace-pre-wrap text-gray-800" x-text="textModalContent"></pre>
            </div>
            <div class="flex justify-end mt-6">
                <button class="px-4 py-2 bg-blue-600 text-white rounded" @click="showTextModal = false">Tutup</button>
            </div>
        </div>
    </div>

    {{-- Modal Detail Kode Inventaris --}}
    <div x-show="showKodeModal" x-cloak class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
        <div class="bg-white rounded-lg w-full max-w-lg p-6 shadow">
            <h2 class="text-xl font-bold mb-4">Kode Inventaris</h2>
            <div class="max-h-[60vh] overflow-y-auto">
                <ul class="pl-2 space-y-1">
                    <template x-for="(line, index) in kodeLines" :key="index">
                        <li x-text="`Unit #${index + 1}: ${line || '-'}`" class="text-gray-800"></li>
                    </template>
                </ul>
            </div>
            <div class="flex justify-end mt-6">
                <button class="px-4 py-2 bg-blue-600 text-white rounded" @click="showKodeModal = false">Tutup</button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
@php
echo "<script>
    window.ITEMS_DATA = " . json_encode($items) . ";
</script>";
echo "<script>
    window.PRODUCTS_DATA = " . json_encode($products) . ";
</script>";
@endphp

<script>
    function itemActions() {
        return {
            // Modal Edit
            open: false,
            item: {},
            editItemId: null, // Store ID separately

            // Modal Tambah Unit
            showAddUnit: false,
            selectedItemId: null,
            newUnits: [],

            // Modal Manage Unit
            manageUnits: [],
            showManageModal: false,
            deleteUnitId: null,
            showDeleteUnitModal: false,

            //Modal Hapus Item
            deleteItemId: null,
            showDeleteItemModal: false,

            // Modal Edit&Hapus Produk
            product: {},
            editProductId: null, // Store product ID separately
            showEditProduct: false,
            showDeleteProduct: false,
            deleteProductId: null,

            // Modal Detail Keterangan&KodeInventaris
            showTextModal: false,
            textModalTitle: '',
            textModalContent: '',
            showKodeModal: false,
            kodeLines: [],

            //Search Item
            searchQuery: '',

            openEditModal(data) {
                console.log('Raw data passed to openEditModal:', data);
                console.log('Data keys:', data ? Object.keys(data) : 'No data');

                // Store ID separately to prevent loss during reactive updates
                this.editItemId = data.id;

                // Create a copy of the item data
                this.item = {
                    id: data.id,
                    name: data.name || '',
                    description: data.description || ''
                };

                console.log('Opening edit modal for item:', this.item);
                console.log('Stored editItemId:', this.editItemId);
                this.open = true;
            },

            // Handle edit form submission manually
            submitEditForm(event) {
                console.log('Submitting edit form for item:', this.item);
                console.log('Item ID from item object:', this.item?.id);
                console.log('Stored editItemId:', this.editItemId);

                // Use the stored ID as fallback
                const itemId = this.editItemId || this.item?.id;

                if (!itemId) {
                    console.error('No item ID available for submission');
                    console.error('Item object:', this.item);
                    console.error('Stored editItemId:', this.editItemId);
                    alert('Error: Item ID not found. Check console for details.');
                    return;
                }

                // Create form data
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                formData.append('_method', 'PUT');
                formData.append('name', this.item.name);
                formData.append('description', this.item.description || '');

                // Submit via fetch
                const actionUrl = `{{ route('items.update', ['id' => '__ID__']) }}`.replace('__ID__', itemId);
                console.log('Submitting to URL:', actionUrl);

                fetch(actionUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            console.log('Form submitted successfully');
                            this.open = false;
                            window.location.reload();
                        } else {
                            console.error('Form submission failed:', response.status);
                            alert('Error updating item');
                        }
                    })
                    .catch(error => {
                        console.error('Form submission error:', error);
                        alert('Error updating item');
                    });
            },

            // Add method to handle form submission success
            handleEditSuccess() {
                this.open = false;
                // Optionally refresh the page or update the items list
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            },

            openAddUnitModal(itemId) {
                console.log('ITEM ID:', itemId);
                this.selectedItemId = itemId;
                this.newUnits = [{
                    code: '',
                    condition: 'Baik'
                }];
                this.showAddUnit = true;
            },

            addNewUnit() {
                this.newUnits.push({
                    code: '',
                    condition: 'Baik'
                });
            },

            openManageUnit(units) {
                this.manageUnits = JSON.parse(JSON.stringify(units))
                this.showManageModal = true
            },

            confirmDeleteUnit(unitId) {
                this.deleteUnitId = unitId;
                this.showDeleteUnitModal = true;
            },

            confirmDeleteItem(id) {
                this.deleteItemId = id;
                this.showDeleteItemModal = true;
            },

            openEditProduct(data) {
                console.log('Raw product data passed to openEditProduct:', data);
                console.log('Product data keys:', data ? Object.keys(data) : 'No data');

                // Store ID separately to prevent loss during reactive updates
                this.editProductId = data.id;

                this.product = {
                    id: data.id,
                    product_name: data.product_name || '',
                    description: data.description || ''
                };

                console.log('Opening edit product modal for:', this.product);
                console.log('Stored editProductId:', this.editProductId);
                this.showEditProduct = true;
            },

            // Handle product edit form submission manually
            submitEditProductForm(event) {
                console.log('Submitting edit product form for:', this.product);
                console.log('Product ID from product object:', this.product?.id);
                console.log('Stored editProductId:', this.editProductId);

                // Use the stored ID as fallback
                const productId = this.editProductId || this.product?.id;

                if (!productId) {
                    console.error('No product ID available for submission');
                    console.error('Product object:', this.product);
                    console.error('Stored editProductId:', this.editProductId);
                    alert('Error: Product ID not found. Check console for details.');
                    return;
                }

                // Create form data
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                formData.append('_method', 'PUT');
                formData.append('product_name', this.product.product_name);
                formData.append('description', this.product.description || '');

                // Submit via fetch
                const actionUrl = `{{ route('products.update', ['id' => '__ID__']) }}`.replace('__ID__', productId);
                console.log('Submitting product to URL:', actionUrl);

                fetch(actionUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            console.log('Product form submitted successfully');
                            this.showEditProduct = false;
                            window.location.reload();
                        } else {
                            console.error('Product form submission failed:', response.status);
                            alert('Error updating product');
                        }
                    })
                    .catch(error => {
                        console.error('Product form submission error:', error);
                        alert('Error updating product');
                    });
            },

            confirmDeleteProduct(id) {
                this.deleteProductId = id;
                this.showDeleteProduct = true;
            },

            openTextModal(title, content) {
                this.textModalTitle = title;
                this.textModalContent = content;
                this.showTextModal = true;
            },

            openKodeModal(content) {
                this.kodeLines = typeof content === 'string' && content.includes('\n') ?
                    content.split('\n') : [];
                this.showKodeModal = true;
            },

            items: window.ITEMS_DATA || [],
            products: window.PRODUCTS_DATA || [],

            // Debug data on initialization
            init() {
                console.log('Items data:', this.items);
                console.log('Products data:', this.products);
                if (this.items.length > 0) {
                    console.log('First item structure:', this.items[0]);
                    console.log('First item keys:', Object.keys(this.items[0]));
                }
                if (this.products.length > 0) {
                    console.log('First product structure:', this.products[0]);
                    console.log('First product keys:', Object.keys(this.products[0]));
                }
            },

            get filteredItems() {
                if (!this.items || !Array.isArray(this.items)) return [];
                if (!this.searchQuery.trim()) return this.items;

                const query = this.searchQuery.toLowerCase();
                return this.items.filter(item => {
                    if (!item) return false;

                    // Check item name
                    const itemName = (item.name || '').toLowerCase();
                    if (itemName.includes(query)) return true;

                    // Check item description
                    const itemDescription = (item.description || '').toLowerCase();
                    if (itemDescription.includes(query)) return true;

                    // Check item units codes
                    if (item.item_units && Array.isArray(item.item_units)) {
                        return item.item_units.some(unit => {
                            if (!unit) return false;
                            const unitCode = (unit.code || '').toLowerCase();
                            return unitCode.includes(query);
                        });
                    }

                    return false;
                });
            },
            get filteredProducts() {
                if (!this.products || !Array.isArray(this.products)) return [];
                if (!this.searchQuery.trim()) return this.products;

                const query = this.searchQuery.toLowerCase();
                return this.products.filter(product => {
                    if (!product) return false;

                    // Check product name
                    const productName = (product.product_name || '').toLowerCase();
                    if (productName.includes(query)) return true;

                    // Check product description
                    const productDescription = (product.description || '').toLowerCase();
                    if (productDescription.includes(query)) return true;

                    return false;
                });
            },



        }
    }
</script>
@endpush