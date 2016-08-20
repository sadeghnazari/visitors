<?php

use Blanfordia\Visitors\Support\Migration;

class CreateVisitorsSqlQueryBindingsParametersTable extends Migration {

	/**
	 * Table related to this migration.
	 *
	 * @var string
	 */

	private $table = 'visitors_sql_query_bindings_parameters';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function migrateUp()
	{
		$this->builder->create(
			$this->table,
			function ($table)
			{
				$table->bigIncrements('id');

				$table->bigInteger('sql_query_bindings_id')->unsigned()->nullable();
				$table->string('name')->nullable()->index();
				$table->text('value')->nullable();

				$table->timestamp('created_at')->index();
				$table->timestamp('updated_at')->index();
			}
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function migrateDown()
	{
		$this->drop($this->table);
	}

}
