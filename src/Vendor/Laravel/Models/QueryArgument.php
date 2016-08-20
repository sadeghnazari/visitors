<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class QueryArgument extends Base {

	protected $table = 'visitors_query_arguments';

	protected $fillable = array(
		'query_id',
		'argument',
		'value',
	);

}
