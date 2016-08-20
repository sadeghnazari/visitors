<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class Route extends Base {

	protected $table = 'visitors_routes';

	protected $fillable = array(
		'name',
		'action',
	);

	public function paths()
	{
		return $this->hasMany($this->getConfig()->get('route_path_model'));
	}

}
