<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class Event extends Base {

	protected $table = 'visitors_events';

	protected $fillable = array(
		'name',
	);

	public function allInThePeriod($minutes, $result)
	{
		$query =
			$this
				->select(
					'visitors_events.id',
					'visitors_events.name',
					$this->getConnection()->raw('count(visitors_events_log.id) as total')
				)
				->from('visitors_events')
				->period($minutes, 'visitors_events_log')
				->join('visitors_events_log', 'visitors_events_log.event_id', '=', 'visitors_events.id')
				->groupBy('visitors_events.id', 'visitors_events.name')
				->orderBy('total', 'desc');

		if ($result)
		{
			return $query->get();
		}

		return $query;
	}

}
