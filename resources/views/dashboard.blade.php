@extends('layouts.app')

@section('content')
<div class="xl:h-full xl:flex xl:flex-col xl:overflow-hidden">
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#2F3185',
                    secondary: '#F5C23E',
                    light: '#FEFEFE',
                    surface: '#F2F2F2',
                }
            }
        }
    }
</script>

{{-- FLASH MESSAGE --}}
@if (session('success') || session('error'))
<div id="flash-message" class="fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-500 transform translate-x-0
    {{ session('success') ? 'bg-green-100 text-green-800 border-l-4 border-green-500' : 'bg-red-100 text-red-800 border-l-4 border-red-500' }}">
    <div class="flex items-center">
        @if(session('success'))
        <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        @else
        <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        @endif
        <p class="font-medium">{{ session('success') ?? session('error') }}</p>
    </div>
</div>
@endif

{{-- DEBUG --}}
@if (empty($labs))
<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg mb-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-yellow-700">Data lab kosong.</p>
        </div>
    </div>
</div>
@endif

{{-- HEADER AND ACTIONS --}}
<div class="bg-white rounded-lg shadow-sm p-4 md:p-6 mb-6 xl:flex-shrink-0">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div class="flex items-center">
            <h1 class="text-xl md:text-2xl font-semibold text-gray-900">Dashboard</h1>
            <span class="mx-3 text-gray-300">|</span>
            <p class="text-gray-500 text-sm md:text-base">Inventaris Laboratorium</p>
        </div>
        <div class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3">
            <button onclick="showAddItemModal()" id="btn-add-item"
                class="inline-flex items-center justify-center px-3 py-2 md:px-4 md:py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg transition-colors duration-200 cursor-pointer text-sm md:text-base">
                <svg class="w-4 h-4 md:w-5 md:h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Item
            </button>
            <button onclick="showAddProductModal()" id="btn-add-product"
                class="inline-flex items-center justify-center px-3 py-2 md:px-4 md:py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg transition-colors duration-200 cursor-pointer text-sm md:text-base">
                <svg class="w-4 h-4 md:w-5 md:h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Tambah Produk
            </button>
            <button onclick="redirectToExport(this)" data-url="{{ route('export.inventaris') }}" id="btn-export"
                class="inline-flex items-center justify-center px-3 py-2 md:px-4 md:py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200 cursor-pointer text-sm md:text-base">
                <svg class="w-4 h-4 md:w-5 md:h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export Excel
            </button>
        </div>
    </div>
</div>

{{-- CARD LABS --}}
<div class="xl:flex-1 xl:overflow-y-auto">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 pb-6">
    @foreach ($labsWithCounts as $lab)
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold text-[#2F3185]">{{ $lab['name'] }}</h2>
                <p class="text-sm text-gray-500">{{ $lab['location'] }}</p>
            </div>
            <div class="text-[#2F3185]">
                @if($lab['name'] === 'LAB FKI')
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                @elseif($lab['name'] === 'LAB LABORAN')
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                @elseif($lab['name'] === 'LAB SI')
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3s3.5-1 3.5-3V7c0-1.5-.5-3-2-3s-2 1.5-2 3m14-3v10c0 2-1.5 3-3.5 3s-3.5-1-3.5-3V7c0-1.5.5-3 2-3s2 1.5 2 3M12 8v8" />
                </svg>
                @elseif($lab['name'] === 'LAB RPL')
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
                @elseif($lab['name'] === 'LAB JARKOM')
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                </svg>
                @elseif($lab['name'] === 'LAB SIC')
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                @else
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                @endif
            </div>
        </div>
        <div class="space-y-3">
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600">Total Item</p>
                <p class="text-xl font-bold text-[#2F3185]">{{ $lab['item_count'] }}</p>
            </div>
            <div class="bg-[#FEF9E7] rounded-lg p-4">
                <p class="text-sm text-gray-600">Total Produk</p>
                <p class="text-xl font-bold text-[#F5C23E]">{{ $lab['product_count'] }}</p>
            </div>
        </div>
    </div>
    @endforeach
    </div>
</div>

</div> {{-- Close main container --}}

{{-- MODALS --}}
{{-- Modal Tambah Item --}}
<div id="modal-add-item" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden overflow-y-auto p-4">
    <div class="bg-white w-full max-w-2xl mx-auto my-4 md:my-8 p-4 md:p-6 rounded-xl shadow-xl relative max-h-[95vh] md:max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-primary">Tambah Item</h2>
            <button onclick="resetModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="item-form" action="{{ route('items.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Pilih Lab</label>
                    <select name="lab_id" required class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-secondary transition-all duration-200">
                        @foreach ($labs as $lab)
                        <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Nama Item</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-secondary transition-all duration-200">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Keterangan</label>
                    <textarea name="description" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-secondary transition-all duration-200 min-h-[100px]"></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Jumlah Unit</label>
                    <input type="number" id="unit-count" min="1" max="100" value="1" onchange="generateUnitFields()"
                        class="px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-secondary transition-all duration-200 w-32">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Kode Inventaris & Kondisi Unit</label>
                    <div id="unit-scroll-wrapper" class="max-h-[250px] overflow-y-auto border border-gray-200 rounded-lg p-4">
                        <div id="unit-fields" class="space-y-3"></div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="resetModal()"
                    class="px-6 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 transition-all duration-200">
                    Batal
                </button>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 cursor-pointer">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Tambah Produk --}}
<div id="modal-add-product" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden overflow-y-auto p-4">
    <div class="bg-white w-full max-w-lg mx-auto my-4 md:my-8 p-4 md:p-6 rounded-xl shadow-xl relative max-h-[95vh] md:max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-primary">Tambah Produk</h2>
            <button onclick="resetProductModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="product-form" action="{{ route('products.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Pilih Lab</label>
                    <select name="lab_id" required class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-secondary transition-all duration-200">
                        @foreach ($labs as $lab)
                        <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Nama Produk</label>
                    <input type="text" name="product_name" required class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-secondary transition-all duration-200">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Keterangan</label>
                    <textarea name="description" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-secondary transition-all duration-200 min-h-[100px]"></textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="resetProductModal()"
                    class="px-6 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 transition-all duration-200">
                    Batal
                </button>
                <button type="submit"
                    class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-yellow-300 focus:ring-offset-2 cursor-pointer">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Debug: Check if functions are being loaded
    console.log('Dashboard scripts loaded');

    // Make functions globally accessible
    window.showAddItemModal = function() {
        console.log('showAddItemModal called');
        resetModal();
        const modal = document.getElementById('modal-add-item');
        console.log('Modal element:', modal);
        if (modal) {
            document.body.style.overflow = 'hidden'; // Prevent body scroll
            modal.classList.remove('hidden');
            console.log('Modal should be visible now');
        } else {
            console.error('Modal not found!');
        }
    };

    window.showAddProductModal = function() {
        console.log('showAddProductModal called');
        resetProductModal();
        const modal = document.getElementById('modal-add-product');
        console.log('Product Modal element:', modal);
        if (modal) {
            document.body.style.overflow = 'hidden'; // Prevent body scroll
            modal.classList.remove('hidden');
            console.log('Product Modal should be visible now');
        } else {
            console.error('Product Modal not found!');
        }
    };

    window.redirectToExport = function(button) {
        console.log('redirectToExport called');
        const url = button.getAttribute('data-url');
        console.log('Export URL:', url);
        if (url) {
            window.location.href = url;
        } else {
            console.error('Export URL not found');
            alert('Error: Export URL not found');
        }
    };

    // Initialize the unit fields when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded');
        generateUnitFields();

        // Add event listeners as fallback
        const btnAddItem = document.getElementById('btn-add-item');
        const btnAddProduct = document.getElementById('btn-add-product');
        const btnExport = document.getElementById('btn-export');

        if (btnAddItem) {
            btnAddItem.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Add Item button clicked via event listener');
                window.showAddItemModal();
            });
        }

        if (btnAddProduct) {
            btnAddProduct.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Add Product button clicked via event listener');
                window.showAddProductModal();
            });
        }

        if (btnExport) {
            btnExport.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Export button clicked via event listener');
                window.redirectToExport(this);
            });
        }

        // Add click outside to close modal
        document.getElementById('modal-add-item').addEventListener('click', function(e) {
            if (e.target === this) {
                resetModal();
            }
        });

        document.getElementById('modal-add-product').addEventListener('click', function(e) {
            if (e.target === this) {
                resetProductModal();
            }
        });

        // Auto-hide flash message after 5 seconds
        const flashMessage = document.getElementById('flash-message');
        if (flashMessage) {
            setTimeout(() => {
                flashMessage.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => flashMessage.remove(), 500);
            }, 5000);
        }
    });

    function generateUnitFields() {
        const container = document.getElementById('unit-fields');
        let count = parseInt(document.getElementById('unit-count').value || 1);

        if (count < 1) {
            document.getElementById('unit-count').value = 1;
            count = 1;
        }

        container.innerHTML = "";

        for (let i = 0; i < count; i++) {
            const div = document.createElement('div');
            div.className = "flex gap-3";

            div.innerHTML = `
                <input type="text" name="units[${i}][code]" placeholder="Kode (boleh kosong)" 
                    class="w-1/2 px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-secondary transition-all duration-200">
                <select name="units[${i}][condition]" required 
                    class="w-1/2 px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-secondary transition-all duration-200">
                    <option value="Baik">Baik</option>
                    <option value="Rusak">Rusak</option>
                </select>
            `;
            container.appendChild(div);
        }
    }

    function resetModal() {
        const form = document.getElementById('item-form');
        form.reset();
        document.getElementById('unit-count').value = 1;
        generateUnitFields();
        document.getElementById('modal-add-item').classList.add('hidden');
        document.body.style.overflow = ''; // Restore body scroll
    }

    function resetProductModal() {
        const form = document.getElementById('product-form');
        form.reset();
        document.getElementById('modal-add-product').classList.add('hidden');
        document.body.style.overflow = ''; // Restore body scroll
    }


</script>
@endpush