<?php

use HDSSolutions\Laravel\Blueprints\BaseBlueprint as Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountsTable extends Migration {

    public function up() {
        // get schema builder
        $schema = DB::getSchemaBuilder();

        // replace blueprint
        $schema->blueprintResolver(fn($table, $callback) => new Blueprint($table, $callback));

        // create table
        $schema->create('bank_accounts', function(Blueprint $table) {
            $table->id();
            $table->foreignTo('Company');
            $table->foreignTo('Bank');
            $table->string('account_number');
            $table->string('iban')->nullable();
            $table->string('description')->nullable();
            $table->char('account_type', 2);
            $table->foreignTo('Currency');
            $table->amount('pending_balance')->default(0);
            $table->amount('current_balance')->default(0);
            $table->amount('credit_limit')->default(0);
            $table->boolean('default')->default(false);
        });

        $schema->create('bank_account_movements', function(Blueprint $table) {
            $table->id();
            $table->foreignTo('Company');
            $table->foreignTo('BankAccount');
            $table->char('movement_type', 2);
            $table->string('description');
            $table->timestamp('transacted_at')->useCurrent();
            $table->amount('amount', signed: true);
            $table->morphable('refer')->nullable();
            $table->boolean('confirmed')->default(false);
        });
    }

    public function down() {
        Schema::dropIfExists('bank_account_movements');
        Schema::dropIfExists('bank_accounts');
    }

}
