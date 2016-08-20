<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class Query extends Base {

	protected $table = 'visitors_queries';

	protected $fillable = array(
		'query',
	);

	public function arguments()
	{
		return $this->hasMany($this->getConfig()->get('query_argument_model'));
	}

}
