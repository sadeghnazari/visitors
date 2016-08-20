<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class Agent extends Base {

	protected $table = 'visitors_agents';

	protected $fillable = array('name',
								'browser',
								'browser_version');

}
