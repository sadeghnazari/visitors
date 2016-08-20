<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class Device extends Base {

	protected $table = 'visitors_devices';

	protected $fillable = array(
		'kind',
		'model',
		'platform',
		'platform_version',
		'is_mobile',
	);

}
