<?php

use HDSSolutions\Laravel\Blueprints\BaseBlueprint as Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends Migration {

    public function up() {
        // get schema builder
        $schema = DB::getSchemaBuilder();

        // replace blueprint
        $schema->blueprintResolver(fn($table, $callback) => new Blueprint($table, $callback));

        // create table
        $schema->create('banks', function(Blueprint $table) {
            $table->id();
            $table->foreignTo('Company');
            $table->string('name');
        });
    }

    public function down() {
        Schema::dropIfExists('banks');
    }

}
