<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class Error extends Base {

	protected $table = 'visitors_errors';

	protected $fillable = array(
		'message',
		'code',
	);

}
