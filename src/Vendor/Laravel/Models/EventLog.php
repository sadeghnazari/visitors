<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class EventLog extends Base {

	protected $table = 'visitors_events_log';

	protected $fillable = array(
		'event_id',
		'class_id',
	    'log_id',
	);

}
