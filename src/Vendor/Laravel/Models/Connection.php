<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class Connection extends Base {

	protected $table = 'visitors_connections';

	protected $fillable = array(
		'name',
	);

}
