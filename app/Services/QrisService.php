<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QrisService
{
    /**
     * Check if payment gateway credentials are configured.
     */
    public function isConfigured(): bool
    {
        return ! empty(config('services.midtrans.server_key'));
    }

    /**
     * Generate dynamic QRIS charge via Midtrans Core API.
     *
     * @return array{success: bool, qr_url?: string, qr_string?: string, reference?: string, message?: string}
     */
    public function generateQris(Transaction $transaction): array
    {
        if (! $this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Konfigurasi QRIS Payment Gateway belum diatur di Pengaturan atau file .env (MIDTRANS_SERVER_KEY).',
            ];
        }

        $serverKey = config('services.midtrans.server_key');
        $isProduction = config('services.midtrans.is_production', false);
        $baseUrl = $isProduction
            ? 'https://api.midtrans.com/v2/charge'
            : 'https://api.sandbox.midtrans.com/v2/charge';

        try {
            $payload = [
                'payment_type' => 'qris',
                'transaction_details' => [
                    'order_id' => $transaction->invoice_number.'-'.time(),
                    'gross_amount' => (int) $transaction->total_amount,
                ],
                'customer_details' => [
                    'first_name' => $transaction->customer_name ?: 'Pelanggan Kasira',
                    'phone' => $transaction->customer_phone ?: '',
                ],
            ];

            $response = Http::withBasicAuth($serverKey, '')
                ->timeout(10)
                ->post($baseUrl, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $qrString = $data['qr_string'] ?? null;
                $actions = $data['actions'] ?? [];
                $qrUrl = null;

                foreach ($actions as $action) {
                    if (($action['name'] ?? '') === 'generate-qr-code') {
                        $qrUrl = $action['url'] ?? null;
                        break;
                    }
                }

                $transaction->update([
                    'payment_status' => 'pending',
                    'payment_reference' => $data['transaction_id'] ?? $payload['transaction_details']['order_id'],
                    'qris_payload' => $qrString,
                ]);

                return [
                    'success' => true,
                    'qr_url' => $qrUrl,
                    'qr_string' => $qrString,
                    'reference' => $transaction->payment_reference,
                    'status' => 'pending',
                ];
            }

            return [
                'success' => false,
                'message' => $response->json('status_message') ?? 'Gagal menghubungi payment gateway QRIS.',
            ];
        } catch (\Throwable $e) {
            Log::error('QRIS Generation Error: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat QRIS: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Check payment status from payment gateway.
     */
    public function checkStatus(Transaction $transaction): string
    {
        if (! $this->isConfigured() || ! $transaction->payment_reference) {
            return $transaction->payment_status ?? 'pending';
        }

        $serverKey = config('services.midtrans.server_key');
        $isProduction = config('services.midtrans.is_production', false);
        $baseUrl = $isProduction
            ? 'https://api.midtrans.com/v2/'.$transaction->payment_reference.'/status'
            : 'https://api.sandbox.midtrans.com/v2/'.$transaction->payment_reference.'/status';

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->timeout(6)
                ->get($baseUrl);

            if ($response->successful()) {
                $status = $response->json('transaction_status');

                if (in_array($status, ['settlement', 'capture'], true)) {
                    $transaction->update(['payment_status' => 'paid', 'status' => 'completed']);

                    return 'paid';
                }

                if (in_array($status, ['expire', 'cancel'], true)) {
                    $transaction->update(['payment_status' => 'expired']);

                    return 'expired';
                }

                if (in_array($status, ['deny'], true)) {
                    $transaction->update(['payment_status' => 'failed']);

                    return 'failed';
                }
            }
        } catch (\Throwable $e) {
            Log::warning('QRIS Status Check Error: '.$e->getMessage());
        }

        return $transaction->payment_status;
    }
}
