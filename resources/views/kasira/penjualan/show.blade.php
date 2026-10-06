@extends('layouts.kasira')

@section('title', 'Detail Transaksi ' . $transaction->invoice_number)

@section('content')
<div class="max-w-xl mx-auto space-y-5">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('kasira.penjualan') }}" class="p-2 rounded-xl bg-white border border-[#E5E7EB] text-[#6B7280] hover:text-[#111827] hover:bg-[#F9FAFB] transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#111827]">Detail Transaksi</h1>
                <p class="text-xs font-mono text-[#5300B7]">{{ $transaction->invoice_number }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="shareReceipt()"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-xs font-bold text-emerald-800 shadow-2xs transition">
                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.971.53 1.77.72 2.796.72 3.181 0 5.767-2.586 5.768-5.766 0-3.18-2.586-5.766-5.768-5.766zm3.385 8.163c-.144.405-.837.774-1.17.824-.312.045-.698.075-2.221-.555-1.944-.805-3.176-2.775-3.272-2.905-.097-.129-.785-1.042-.785-1.987 0-.945.497-1.411.674-1.603.177-.193.386-.241.514-.241.129 0 .257.002.37.008.118.006.277-.045.433.33.161.386.547 1.334.595 1.431.048.097.08.209.016.338-.064.129-.097.209-.193.322-.097.113-.204.252-.291.338-.097.097-.198.203-.085.397.113.193.501.826 1.077 1.339.742.66 1.368.864 1.562.961.193.097.306.08.419-.048.113-.129.483-.563.612-.756.129-.193.258-.161.434-.097.177.064 1.127.531 1.32.628.193.097.322.145.37.225.048.08.048.467-.096.872z"/>
                </svg>
                <span>Bagikan Struk</span>
            </button>

            <button type="button" onclick="window.print()"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-[#E5E7EB] hover:bg-[#F9FAFB] text-xs font-bold text-[#111827] shadow-2xs transition">
                <svg class="w-4 h-4 text-[#6B7280]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak Struk</span>
            </button>
        </div>
    </div>

    <!-- Printable Receipt Card -->
    <div class="bg-white p-6 rounded-2xl border border-[#E5E7EB] shadow-xs space-y-5 print:shadow-none print:border-none">
        <!-- Store Header -->
        <div class="text-center pb-4 border-b border-[#F3F4F6]">
            <h2 class="text-lg font-bold text-[#111827]">{{ $business->name }}</h2>
            <p class="text-xs text-[#6B7280]">{{ $business->business_type }}</p>
            @if($business->phone)
                <p class="text-[11px] text-[#6B7280]">Telp/WA: {{ $business->phone }}</p>
            @endif
            @if($business->address)
                <p class="text-[11px] text-[#9CA3AF] mt-0.5">{{ $business->address }}</p>
            @endif
        </div>

        <!-- Meta Info -->
        <div class="text-xs space-y-1 text-[#6B7280] pb-3 border-b border-[#F3F4F6]">
            <div class="flex justify-between">
                <span>No. Invoice</span>
                <span class="font-mono font-bold text-[#111827]">{{ $transaction->invoice_number }}</span>
            </div>
            <div class="flex justify-between">
                <span>Waktu</span>
                <span class="font-medium text-[#111827]">{{ $transaction->created_at->translatedFormat('d M Y, H:i:s') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Kasir</span>
                <span class="font-medium text-[#111827]">{{ $transaction->user?->name ?? 'Kasir' }}</span>
            </div>
            <div class="flex justify-between">
                <span>Metode Pembayaran</span>
                <span class="font-bold text-[#111827] uppercase">{{ $transaction->payment_method }}</span>
            </div>
        </div>

        <!-- Line Items -->
        <div class="space-y-2.5 pb-4 border-b border-[#F3F4F6]">
            <span class="text-[10px] font-bold text-[#9CA3AF] uppercase tracking-wider block">Daftar Barang</span>
            @foreach($transaction->items as $item)
                <div class="flex items-center justify-between text-xs">
                    <div class="min-w-0 flex-1">
                        <span class="font-bold text-[#111827] block truncate">{{ $item->product_name }}</span>
                        <span class="text-[11px] text-[#6B7280]">{{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    </div>
                    <span class="font-bold text-[#111827] shrink-0">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </span>
                </div>
            @endforeach
        </div>

        <!-- Payment Calculations -->
        <div class="space-y-1.5 text-xs">
            <div class="flex justify-between text-sm font-bold text-[#111827]">
                <span>Total Belanja</span>
                <span class="text-base text-[#5300B7]">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-[#6B7280]">
                <span>Uang Diterima</span>
                <span class="font-semibold text-[#111827]">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-[#6B7280]">
                <span>Kembalian</span>
                <span class="font-bold text-emerald-600">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center pt-4 border-t border-[#F3F4F6] text-[11px] text-[#9CA3AF]">
            <p>{{ $business->receipt_footer ?? 'Terima kasih telah berbelanja di toko kami!' }}</p>
            <p class="text-[10px] text-[#D1D5DB] mt-1">Dikelola dengan Kasira POS</p>
        </div>
    </div>
</div>

@php
    $receiptItemsText = $transaction->items
        ->map(fn ($item) => $item->product_name.' ('.$item->quantity.'x) : Rp '.number_format($item->subtotal, 0, ',', '.'))
        ->join("\n");
@endphp

<div id="receipt-data"
     data-business-name="{{ $business->name }}"
     data-invoice-number="{{ $transaction->invoice_number }}"
     data-transaction-time="{{ $transaction->created_at->format('d M Y H:i') }}"
     data-payment-method="{{ strtoupper($transaction->payment_method) }}"
     data-total="{{ number_format($transaction->total_amount, 0, ',', '.') }}"
     data-items='@json($receiptItemsText)'
     hidden></div>

<script>
    function shareReceipt() {
        const receiptData = document.getElementById('receipt-data');
        const receiptItemsText = JSON.parse(receiptData.dataset.items);
        const text = `*STRUK PEMBELIAN - ${receiptData.dataset.businessName}*\n` +
            `No. Invoice: ${receiptData.dataset.invoiceNumber}\n` +
            `Waktu: ${receiptData.dataset.transactionTime}\n` +
            `Metode: ${receiptData.dataset.paymentMethod}\n` +
            `--------------------------------\n` +
            receiptItemsText +
            `\n--------------------------------\n` +
            `*TOTAL: Rp ${receiptData.dataset.total}*\n` +
            `Terima kasih telah berbelanja!`;

        if (navigator.share) {
            navigator.share({
                title: 'Struk Pembelian ' + receiptData.dataset.invoiceNumber,
                text: text
            }).catch(console.error);
        } else {
            const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
            window.open(url, '_blank');
        }
    }
</script>
@endsection
