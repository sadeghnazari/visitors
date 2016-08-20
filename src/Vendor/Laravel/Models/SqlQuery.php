<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class SqlQuery extends Base {

	protected $table = 'visitors_sql_queries';

	protected $fillable = array(
		'sha1',
		'statement',
	    'time',
	    'connection_id',
	);

}
