<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class SqlQueryBinding extends Base {

	protected $table = 'visitors_sql_query_bindings';

	protected $fillable = array(
		'sha1',
		'serialized',
	);

}
