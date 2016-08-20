<?php

use Blanfordia\Visitors\Support\Migration;

class CreateVisitorsQueriesArgumentsTable extends Migration {

	/**
	 * Table related to this migration.
	 *
	 * @var string
	 */

	private $table = 'visitors_query_arguments';

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

				$table->bigInteger('query_id')->unsigned()->index();
				$table->string('argument')->index();
				$table->string('value')->index();

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
