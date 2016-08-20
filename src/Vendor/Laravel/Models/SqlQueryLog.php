<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class SqlQueryLog extends Base {

	protected $table = 'visitors_sql_queries_log';

	protected $fillable = array(
		'log_id',
		'sql_query_id',
	);
}
