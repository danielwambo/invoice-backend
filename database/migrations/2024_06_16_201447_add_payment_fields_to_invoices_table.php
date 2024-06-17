<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentFieldsToInvoicesTable extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Only add 'transaction_id' if it does not already exist
            if (!Schema::hasColumn('invoices', 'transaction_id')) {
                $table->string('transaction_id')->nullable();
            }

            // Add 'payment_status' if it does not already exist
            if (!Schema::hasColumn('invoices', 'payment_status')) {
                $table->string('payment_status')->default('Pending');
            }
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('payment_status');
            $table->dropColumn('transaction_id');
        });
    }
}
