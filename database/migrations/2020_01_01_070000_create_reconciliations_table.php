<?php

use HDSSolutions\Laravel\Blueprints\BaseBlueprint as Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateReconciliationsTable extends Migration {

    public function up() {
        // get schema builder
        $schema = DB::getSchemaBuilder();

        // replace blueprint
        $schema->blueprintResolver(fn($table, $callback) => new Blueprint($table, $callback));

        // create table
        $schema->create('reconciliations', function(Blueprint $table) {
            $table->id();
            $table->foreignTo('Company');
            $table->string('document_number');
            $table->timestamp('transacted_at')->useCurrent();
            // use table as document
            $table->asDocument();
        });

        $schema->create('reconciliation_check', function(Blueprint $table) {
            $table->foreignTo('Reconciliation');
            $table->foreignTo('Check');
            $table->primary([ 'reconciliation_id', 'check_id' ]);
        });
    }

    public function down() {
        Schema::dropIfExists('reconciliation_check');
        Schema::dropIfExists('reconciliations');
    }

}
