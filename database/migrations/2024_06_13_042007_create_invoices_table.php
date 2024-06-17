<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->string('description');
                $table->decimal('amount', 10, 2);
                $table->timestamps();
            });
        }

        // Check if 'payment_status' column does not exist before adding it
        if (!Schema::hasColumn('invoices', 'payment_status')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->string('payment_status')->default('Pending');
            });
        }

        // Check if 'transaction_id' column does not exist before adding it
        if (!Schema::hasColumn('invoices', 'transaction_id')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->string('transaction_id')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
