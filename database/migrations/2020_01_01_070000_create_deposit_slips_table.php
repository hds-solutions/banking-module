<?php

use HDSSolutions\Laravel\Blueprints\BaseBlueprint as Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateDepositSlipsTable extends Migration {

    public function up() {
        // get schema builder
        $schema = DB::getSchemaBuilder();

        // replace blueprint
        $schema->blueprintResolver(fn($table, $callback) => new Blueprint($table, $callback));

        // create table
        $schema->create('deposit_slips', function(Blueprint $table) {
            $table->id();
            $table->foreignTo('Company');
            $table->foreignTo('BankAccount');
            $table->foreignTo('BankAccount', 'to_bank_account_id')->nullable();
            $table->foreignTo('Cash')->nullable();
            $table->char('transaction_type', 2);
            $table->string('document_number');
            $table->timestamp('transacted_at')->useCurrent();
            $table->foreignTo('ConversionRate')->nullable();
            $table->amount('rate', decimals: 10)->nullable();
            $table->amount('cash_amount')->default(0);
            $table->foreignTo('BankAccountMovement')->nullable();
            $table->amount('total')->default(0);
            // use table as document
            $table->asDocument();
        });

        $schema->create('deposit_slip_check', function(Blueprint $table) {
            $table->foreignTo('DepositSlip');
            $table->foreignTo('Check');
            $table->primary([ 'deposit_slip_id', 'check_id' ]);
            $table->foreignTo('BankAccountMovement')->nullable();
        });
    }

    public function down() {
        Schema::dropIfExists('deposit_slip_check');
        Schema::dropIfExists('deposit_slips');
    }

}
