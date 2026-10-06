<?php

namespace App\Http\Controllers\Kasira;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    /**
     * Handle incoming Midtrans payment webhook notification.
     */
    public function handleMidtrans(Request $request): JsonResponse
    {
        $payload = $request->all();
        Log::info('Midtrans Webhook Received', $payload);

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (! $orderId) {
            return response()->json(['message' => 'Invalid order ID'], 400);
        }

        // Extract invoice number from order_id (format: {invoice_number}-{timestamp} or {invoice_number})
        $invoiceNumber = explode('-', $orderId)[0] ?? $orderId;
        $transaction = Transaction::where('invoice_number', $invoiceNumber)
            ->orWhere('payment_reference', $orderId)
            ->orWhere('payment_reference', $payload['transaction_id'] ?? '')
            ->first();

        if (! $transaction) {
            Log::warning("Transaction not found for Midtrans order: {$orderId}");

            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if (in_array($transactionStatus, ['capture', 'settlement'], true)) {
            if ($fraudStatus === 'challenge') {
                $transaction->update(['payment_status' => 'pending']);
            } else {
                $transaction->update([
                    'payment_status' => 'paid',
                    'status' => 'completed',
                ]);
            }
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'], true)) {
            $transaction->update(['payment_status' => 'expired']);
        } elseif ($transactionStatus === 'pending') {
            $transaction->update(['payment_status' => 'pending']);
        }

        return response()->json(['status' => 'OK']);
    }
}
