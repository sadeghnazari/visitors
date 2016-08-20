<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class SqlQueryBindingParameter extends Base {

	protected $table = 'visitors_sql_query_bindings_parameters';

	protected $fillable = array(
		'sql_query_bindings_id',
		'name',
		'value',
	);

}
