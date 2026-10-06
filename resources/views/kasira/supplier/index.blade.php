@extends('layouts.kasira')

@section('title', 'Supplier')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#111827] tracking-tight">Manajemen Supplier</h1>
            <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">Kelola pemasok produk barang dagang toko dan informasi kontak distribusi.</p>
        </div>

        <button type="button" onclick="document.getElementById('add-supplier-modal').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold transition shadow-sm self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Supplier</span>
        </button>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
        <form action="{{ route('kasira.supplier.index') }}" method="GET" class="w-full sm:max-w-md relative">
            <input type="text" name="q" placeholder="Cari nama supplier, telepon, alamat..."
                   value="{{ request('q') }}"
                   class="w-full pl-9 pr-4 py-2 bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl text-xs text-[#111827] placeholder-[#9CA3AF] focus:bg-white focus:border-[#5300B7] outline-none">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#9CA3AF]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </form>

        <span class="text-xs text-[#6B7280] font-medium">Total: <strong>{{ $suppliers->total() }}</strong> Supplier</span>
    </div>

    <!-- Supplier Table -->
    <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F9FAFB] text-[#6B7280] font-bold border-b border-[#E5E7EB]">
                    <tr>
                        <th class="py-3 px-4">Nama Supplier</th>
                        <th class="py-3 px-4">Kontak / Telepon</th>
                        <th class="py-3 px-4">Alamat Gudang</th>
                        <th class="py-3 px-4 text-center">Produk Terkait</th>
                        <th class="py-3 px-4">Catatan</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3F4F6]">
                    @forelse($suppliers as $supplier)
                        <tr class="hover:bg-[#F9FAFB]/50 transition">
                            <td class="py-3.5 px-4 font-bold text-[#111827]">
                                {{ $supplier->name }}
                            </td>
                            <td class="py-3.5 px-4 text-[#374151]">
                                @if($supplier->phone)
                                    <div class="flex items-center gap-1.5">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $supplier->phone) }}" target="_blank"
                                           class="text-[#5300B7] hover:underline font-medium">
                                            {{ $supplier->phone }}
                                        </a>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-[#6B7280] max-w-xs truncate">
                                {{ $supplier->address ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-[#5300B7]">
                                    {{ $supplier->products_count }} item
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[#6B7280] max-w-xs truncate">
                                {{ $supplier->notes ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button"
                                            onclick='openEditSupplierModal(@json($supplier))'
                                            class="p-1.5 text-gray-500 hover:text-[#5300B7] rounded-lg hover:bg-purple-50 transition"
                                            title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('kasira.supplier.destroy', $supplier->id) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus supplier {{ $supplier->name }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">Belum ada data supplier. Klik "Tambah Supplier" untuk memasukkan data pemasok pertama Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($suppliers->hasPages())
            <div class="p-4 border-t border-[#E5E7EB]">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Supplier -->
<div id="add-supplier-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs hidden">
    <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="text-base font-bold text-[#111827]">Tambah Supplier Baru</h3>
            <button type="button" onclick="document.getElementById('add-supplier-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('kasira.supplier.store') }}" method="POST" class="space-y-3.5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-[#374151] mb-1">Nama Supplier / Agen *</label>
                <input type="text" name="name" required placeholder="Contoh: PT Sumber Sembako Makmur"
                       class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-xs focus:border-[#5300B7] outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#374151] mb-1">No. WhatsApp / Telepon</label>
                <input type="text" name="phone" placeholder="0812xxxxxxxx"
                       class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-xs focus:border-[#5300B7] outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#374151] mb-1">Alamat Gudang / Kantor</label>
                <textarea name="address" rows="2" placeholder="Jl. Pergudangan Indah No. 12..."
                          class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-xs focus:border-[#5300B7] outline-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#374151] mb-1">Catatan Tambahan</label>
                <textarea name="notes" rows="2" placeholder="Minimal order, jadwal kirim..."
                          class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-xs focus:border-[#5300B7] outline-none"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" onclick="document.getElementById('add-supplier-modal').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold transition shadow-xs">
                    Simpan Supplier
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Supplier -->
<div id="edit-supplier-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs hidden">
    <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="text-base font-bold text-[#111827]">Edit Data Supplier</h3>
            <button type="button" onclick="document.getElementById('edit-supplier-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="edit-supplier-form" method="POST" class="space-y-3.5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-[#374151] mb-1">Nama Supplier *</label>
                <input type="text" id="edit-supplier-name" name="name" required
                       class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-xs focus:border-[#5300B7] outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#374151] mb-1">No. WhatsApp / Telepon</label>
                <input type="text" id="edit-supplier-phone" name="phone"
                       class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-xs focus:border-[#5300B7] outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#374151] mb-1">Alamat Gudang</label>
                <textarea id="edit-supplier-address" name="address" rows="2"
                          class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-xs focus:border-[#5300B7] outline-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#374151] mb-1">Catatan</label>
                <textarea id="edit-supplier-notes" name="notes" rows="2"
                          class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-xs focus:border-[#5300B7] outline-none"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" onclick="document.getElementById('edit-supplier-modal').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold transition shadow-xs">
                    Perbarui Supplier
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditSupplierModal(supplier) {
        const form = document.getElementById('edit-supplier-form');
        form.action = `/supplier/${supplier.id}`;
        document.getElementById('edit-supplier-name').value = supplier.name || '';
        document.getElementById('edit-supplier-phone').value = supplier.phone || '';
        document.getElementById('edit-supplier-address').value = supplier.address || '';
        document.getElementById('edit-supplier-notes').value = supplier.notes || '';
        document.getElementById('edit-supplier-modal').classList.remove('hidden');
    }
</script>
@endsection
