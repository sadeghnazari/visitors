<?php

use Blanfordia\Visitors\Support\Migration;

class AddVisitorsRefererColumns extends Migration {

	/**
	 * Table related to this migration.
	 *
	 * @var string
	 */

	private $table = 'visitors_referers';

	private $foreign = 'visitors_referers_search_terms';

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
				$table->string('medium')->nullable()->index();
				$table->string('source')->nullable()->index();
				$table->string('search_terms_hash')->nullable()->index();
			}
		);

		$this->builder->table($this->foreign, function($table)
		{
			$table->foreign('referer_id', 'visitors_referers_referer_id_fk')
				->references('id')
				->on('visitors_referers')
				->onUpdate('cascade')
				->onDelete('cascade');
		});
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
				$table->dropColumn('medium');
				$table->dropColumn('source');
				$table->dropColumn('search_terms_hash');
			}
		);

		$this->builder->table(
			$this->foreign,
			function ($table)
			{
				$table->dropForeign('visitors_referers_referer_id_fk');
			}
		);

	}

}
