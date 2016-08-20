<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class Domain extends Base {

	protected $table = 'visitors_domains';

	protected $fillable = array(
		'name',
	);

}
