<?php

use Blanfordia\Visitors\Support\Migration;

class CreateVisitorsEventsLogTable extends Migration {

	/**
	 * Table related to this migration.
	 *
	 * @var string
	 */

	private $table = 'visitors_events_log';

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

				$table->bigInteger('event_id')->unsigned()->index();
				$table->bigInteger('class_id')->unsigned()->nullable()->index();
				$table->bigInteger('log_id')->unsigned()->index();

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
