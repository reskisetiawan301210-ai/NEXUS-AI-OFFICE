<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Users table: Add single-product locking field
        Schema::table('users', function (Blueprint $table) {
            $table->string('product', 32)->default('kasira')->after('email');
        });

        // Sync existing data to product column
        if (Schema::hasColumn('users', 'has_lifehub') && Schema::hasColumn('users', 'has_kasira')) {
            DB::table('users')->where('has_lifehub', true)->where('has_kasira', false)->update(['product' => 'lifehub']);
            DB::table('users')->where('has_kasira', true)->update(['product' => 'kasira']);
        }

        // 2. Businesses table: Subscription tier & QRIS config
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('subscription_tier', 32)->default('pro')->after('currency');
            $table->timestamp('subscription_expires_at')->nullable()->after('subscription_tier');
        });

        // 3. Suppliers table
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('business_id');
        });

        // 4. Products: link to supplier
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('supplier_id')->nullable()->after('category_id')->constrained('suppliers')->nullOnDelete();
        });

        // 5. Debts / Piutang Pelanggan table
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('total_amount', 14, 2);
            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->decimal('remaining_amount', 14, 2);
            $table->date('due_date')->nullable();
            $table->string('status', 32)->default('unpaid'); // unpaid, partial, paid
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['business_id', 'status']);
            $table->index('customer_id');
        });

        // 6. Cashier Shifts table
        Schema::create('cashier_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->dateTime('opened_at');
            $table->dateTime('closed_at')->nullable();
            $table->decimal('initial_cash', 14, 2)->default(0);
            $table->decimal('total_sales', 14, 2)->default(0);
            $table->decimal('cash_sales', 14, 2)->default(0);
            $table->decimal('qris_sales', 14, 2)->default(0);
            $table->decimal('expected_cash', 14, 2)->default(0);
            $table->decimal('actual_cash', 14, 2)->nullable();
            $table->decimal('difference', 14, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('status', 32)->default('open'); // open, closed
            $table->timestamps();

            $table->index(['business_id', 'status']);
            $table->index('user_id');
        });

        // 7. Transactions table: link to shift & real QRIS payment fields
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('shift_id')->nullable()->after('user_id')->constrained('cashier_shifts')->nullOnDelete();
            $table->string('payment_status', 32)->default('paid')->after('status'); // pending, paid, expired, failed
            $table->string('payment_reference')->nullable()->after('payment_status');
            $table->text('qris_payload')->nullable()->after('payment_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['shift_id']);
            $table->dropColumn(['shift_id', 'payment_status', 'payment_reference', 'qris_payload']);
        });

        Schema::dropIfExists('cashier_shifts');
        Schema::dropIfExists('debts');

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');
        });

        Schema::dropIfExists('suppliers');

        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['subscription_tier', 'subscription_expires_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('product');
        });
    }
};
