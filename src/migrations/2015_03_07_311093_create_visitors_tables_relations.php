<?php

use Blanfordia\Visitors\Support\Migration;

class CreateVisitorsTablesRelations extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function migrateUp()
	{
		$this->builder->table('visitors_query_arguments', function($table)
		{
			$table->foreign('query_id')
				->references('id')
				->on('visitors_queries')
				->onUpdate('cascade')
				->onDelete('cascade');
		});

		$this->builder->table('visitors_route_paths', function($table)
		{
			$table->foreign('route_id')
				->references('id')
				->on('visitors_routes')
				->onUpdate('cascade')
				->onDelete('cascade');
		});

		$this->builder->table('visitors_route_path_parameters', function($table)
		{
			$table->foreign('route_path_id')
				->references('id')
				->on('visitors_route_paths')
				->onUpdate('cascade')
				->onDelete('cascade');
		});

		$this->builder->table('visitors_referers', function($table)
		{
			$table->foreign('domain_id')
				->references('id')
				->on('visitors_domains')
				->onUpdate('cascade')
				->onDelete('cascade');
		});

		$this->builder->table('visitors_sessions', function($table)
		{
			$table->foreign('device_id')
				->references('id')
				->on('visitors_devices')
				->onUpdate('cascade')
				->onDelete('cascade');
		});

		$this->builder->table('visitors_sessions', function($table)
		{
			$table->foreign('agent_id')
				->references('id')
				->on('visitors_agents')
				->onUpdate('cascade')
				->onDelete('cascade');
		});

		$this->builder->table('visitors_sessions', function($table)
		{
			$table->foreign('referer_id')
				->references('id')
				->on('visitors_referers')
				->onUpdate('cascade')
				->onDelete('cascade');
		});

		$this->builder->table('visitors_sessions', function($table)
		{
			$table->foreign('cookie_id')
				->references('id')
				->on('visitors_cookies')
				->onUpdate('cascade')
				->onDelete('cascade');
		});

		$this->builder->table('visitors_sessions', function($table)
		{
			$table->foreign('geoip_id')
				->references('id')
				->on('visitors_geoip')
				->onUpdate('cascade')
				->onDelete('cascade');
		});

		$this->builder->table('visitors_log', function($table)
		{
			$table->foreign('session_id')
				->references('id')
				->on('visitors_sessions')
				->onUpdate('cascade')
				->onDelete('cascade');
		});

		$this->builder->table('visitors_log', function($table)
		{
			$table->foreign('path_id')
				->references('id')
				->on('visitors_paths')
				->onUpdate('cascade')
				->onDelete('cascade');
		});

		$this->builder->table('visitors_log', function($table)
		{
			$table->foreign('query_id')
				->references('id')
				->on('visitors_queries')
				->onUpdate('cascade')
				->onDelete('cascade');
		});

		$this->builder->table('visitors_log', function($table)
		{
			$table->foreign('route_path_id')
				->references('id')
				->on('visitors_route_paths')
				->onUpdate('cascade')
				->onDelete('cascade');
		});

		$this->builder->table('visitors_log', function($table)
		{
			$table->foreign('error_id')
				->references('id')
				->on('visitors_errors')
				->onUpdate('cascade')
				->onDelete('cascade');
		});


		$this->builder->table('visitors_events_log', function($table)
		{
			$table->foreign('event_id')
				->references('id')
				->on('visitors_events')
				->onUpdate('cascade')
				->onDelete('cascade');
		});

		$this->builder->table('visitors_events_log', function($table)
		{
			$table->foreign('class_id')
				->references('id')
				->on('visitors_system_classes')
				->onUpdate('cascade')
				->onDelete('cascade');
		});

		$this->builder->table('visitors_events_log', function($table)
		{
			$table->foreign('log_id')
				->references('id')
				->on('visitors_log')
				->onUpdate('cascade')
				->onDelete('cascade');
		});


		$this->builder->table('visitors_sql_query_bindings_parameters', function($table)
		{
			$table->foreign('sql_query_bindings_id', 'visitors_sqlqb_parameters')
				->references('id')
				->on('visitors_sql_query_bindings')
				->onUpdate('cascade')
				->onDelete('cascade');
		});


		$this->builder->table('visitors_sql_queries_log', function($table)
		{
			$table->foreign('log_id')
				->references('id')
				->on('visitors_log')
				->onUpdate('cascade')
				->onDelete('cascade');
		});

		$this->builder->table('visitors_sql_queries_log', function($table)
		{
			$table->foreign('sql_query_id')
				->references('id')
				->on('visitors_sql_queries')
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
		// Tables will be dropped in the correct order... :)
	}

}
