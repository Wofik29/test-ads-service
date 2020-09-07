<?php

use Illuminate\Database\Migrations\Migration;
use \Illuminate\Database\Schema\Blueprint;

class AdsTable extends Migration
{
    public function up()
    {
        /** @var Illuminate\Database\Schema\Builder $schema */
        $schema = \Core\App::getInstance()['db.connection']->getSchemaBuilder();
        $schema->create('ads', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->text('text');
            $table->decimal('price');
            $table->unsignedInteger('amount')->default(0)->comment('Кол-во показов');
            $table->unsignedInteger('limit')->comment('Макс число показов');
            $table->string('banner')->comment('Путь до файла');

            $table->unsignedInteger('created_at');
            $table->unsignedInteger('updated_at');
        });
    }
}