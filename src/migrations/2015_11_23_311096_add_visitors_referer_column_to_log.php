<?php

use Blanfordia\Visitors\Support\Migration;

class AddVisitorsRefererColumnToLog extends Migration
{
	/**
	 * Table related to this migration.
	 *
	 * @var string
	 */

	private $table = 'visitors_log';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function migrateUp()
	{
		$this->builder->table(
			$this->table,
			function ($table)
			{
                $table->integer('referer_id')->unsigned()->nullable()->index();
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
		$this->builder->table(
			$this->table,
			function ($table)
			{
				$table->dropColumn('referer_id');
			}
		);
	}
}
