<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class RoutePathParameter extends Base {

	protected $table = 'visitors_route_path_parameters';

	protected $fillable = array(
		'route_path_id',
		'parameter',
		'value',
	);

}
