@extends('layouts.kasira')

@section('title', 'Daftar Pelanggan')

@section('content')
<div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#111827] tracking-tight">Data Pelanggan</h1>
            <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">Kelola data pelanggan langganan untuk loyalitas dan kontak promosi toko.</p>
        </div>

        <button type="button" onclick="document.getElementById('add-customer-modal').classList.remove('hidden')"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold shadow-sm shadow-[#5300B7]/25 transition self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Pelanggan</span>
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-700 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-xs">
        <form method="GET" action="{{ route('kasira.pelanggan') }}" class="flex items-center gap-2.5">
            <div class="relative flex-1 max-w-md">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau nomor telepon..."
                       class="w-full pl-9 pr-4 py-2 bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl text-xs text-[#111827] placeholder-[#9CA3AF] focus:border-[#5300B7] outline-none">
                <svg class="w-4 h-4 text-[#9CA3AF] absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <button type="submit" class="px-4 py-2 bg-[#F3F4F6] text-xs font-semibold text-[#374151] rounded-xl hover:bg-[#E5E7EB]">Cari</button>
            @if(request('q'))
                <a href="{{ route('kasira.pelanggan') }}" class="text-xs text-[#5300B7] font-semibold hover:underline">Reset</a>
            @endif
        </form>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-[#E5E7EB] bg-[#F9FAFB] text-[#6B7280] font-bold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-4">Nama Pelanggan</th>
                        <th class="py-3 px-4">Nomor Kontak</th>
                        <th class="py-3 px-4">Alamat</th>
                        <th class="py-3 px-4 text-right">Total Belanja</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3F4F6]">
                    @forelse($customers as $cust)
                        <tr class="hover:bg-[#F9FAFB]/70 transition">
                            <td class="py-3 px-4">
                                <a href="{{ route('kasira.pelanggan.show', $cust) }}"
                                   class="font-bold text-[#111827] hover:text-[#5300B7] hover:underline">
                                    {{ $cust->name }}
                                </a>
                            </td>
                            <td class="py-3 px-4 text-[#4B5563] font-mono">
                                {{ $cust->phone ?? '-' }}
                            </td>
                            <td class="py-3 px-4 text-[#6B7280]">
                                {{ Str::limit($cust->address, 40) ?? '-' }}
                            </td>
                            <td class="py-3 px-4 text-right font-bold text-[#111827]">
                                Rp {{ number_format($cust->total_spent, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('kasira.pelanggan.show', $cust) }}"
                                       class="text-[10px] font-bold text-[#5300B7] hover:underline">Detail</a>
                                    <span class="text-[#E5E7EB]">|</span>
                                    <button type="button"
                                            onclick="openEditModal({{ $cust->id }}, '{{ addslashes($cust->name) }}', '{{ addslashes($cust->phone ?? '') }}', '{{ addslashes($cust->email ?? '') }}', '{{ addslashes($cust->address ?? '') }}')"
                                            class="text-[10px] font-bold text-[#374151] hover:text-[#5300B7]">Edit</button>
                                    <span class="text-[#E5E7EB]">|</span>
                                    <form action="{{ route('kasira.pelanggan.destroy', $cust) }}" method="POST"
                                          onsubmit="return confirm('Hapus pelanggan {{ addslashes($cust->name) }}? Riwayat transaksi tetap tersimpan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[10px] font-bold text-red-500 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-[#9CA3AF]">
                                <svg class="w-10 h-10 mx-auto mb-2 text-[#D1D5DB]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p class="text-sm font-semibold text-[#111827]">Belum ada pelanggan terdaftar</p>
                                <p class="text-xs text-[#6B7280] mt-1">Daftarkan nomor pelanggan Anda untuk program diskon atau langganan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="p-4 border-t border-[#E5E7EB]">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Add Customer -->
<div id="add-customer-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" onclick="document.getElementById('add-customer-modal').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-[#E5E7EB] z-10 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-[#F3F4F6]">
            <h3 class="text-base font-bold text-[#111827]">Tambah Pelanggan Baru</h3>
            <button type="button" onclick="document.getElementById('add-customer-modal').classList.add('hidden')" class="text-[#9CA3AF] hover:text-[#111827]">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('kasira.pelanggan.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                <input type="text" name="name" required placeholder="Contoh: Budi Santoso"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs text-[#111827] focus:border-[#5300B7] outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">No. WhatsApp / Telp</label>
                <input type="text" name="phone" placeholder="081234567890"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs font-mono text-[#111827] focus:border-[#5300B7] outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">Email (Opsional)</label>
                <input type="email" name="email" placeholder="budi@email.com"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs text-[#111827] focus:border-[#5300B7] outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">Alamat</label>
                <textarea name="address" rows="2" placeholder="Komplek A Blok B3"
                          class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs text-[#111827] focus:border-[#5300B7] outline-none resize-none"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-[#F3F4F6]">
                <button type="button" onclick="document.getElementById('add-customer-modal').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl border border-[#E5E7EB] text-xs font-semibold text-[#6B7280]">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold shadow-xs">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Customer -->
<div id="edit-customer-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" onclick="document.getElementById('edit-customer-modal').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-[#E5E7EB] z-10 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-[#F3F4F6]">
            <h3 class="text-base font-bold text-[#111827]">Edit Pelanggan</h3>
            <button type="button" onclick="document.getElementById('edit-customer-modal').classList.add('hidden')" class="text-[#9CA3AF] hover:text-[#111827]">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="edit-customer-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                <input type="text" id="edit-name" name="name" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs text-[#111827] focus:border-[#5300B7] outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">No. WhatsApp / Telp</label>
                <input type="text" id="edit-phone" name="phone"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs font-mono text-[#111827] focus:border-[#5300B7] outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">Email</label>
                <input type="email" id="edit-email" name="email"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs text-[#111827] focus:border-[#5300B7] outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">Alamat</label>
                <textarea id="edit-address" name="address" rows="2"
                          class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs text-[#111827] focus:border-[#5300B7] outline-none resize-none"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-[#F3F4F6]">
                <button type="button" onclick="document.getElementById('edit-customer-modal').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl border border-[#E5E7EB] text-xs font-semibold text-[#6B7280]">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold shadow-xs">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, name, phone, email, address) {
        const baseUrl = '{{ url("/pelanggan") }}';
        document.getElementById('edit-customer-form').action = baseUrl + '/' + id;
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-phone').value = phone;
        document.getElementById('edit-email').value = email;
        document.getElementById('edit-address').value = address;
        document.getElementById('edit-customer-modal').classList.remove('hidden');
    }
</script>
@endsection
