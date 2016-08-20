<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class Log extends Base {

	protected $table = 'visitors_log';

	protected $fillable = array(
		'session_id',
		'method',
		'path_id',
		'query_id',
		'route_path_id',
        'referer_id',
		'is_ajax',
		'is_secure',
		'is_json',
		'wants_json',
		'error_id',
	);

	public function session()
	{
		return $this->belongsTo($this->getConfig()->get('session_model'));
	}

	public function path()
	{
		return $this->belongsTo($this->getConfig()->get('path_model'));
	}

	public function error()
	{
		return $this->belongsTo($this->getConfig()->get('error_model'));
	}

	public function logQuery()
	{
		return $this->belongsTo($this->getConfig()->get('query_model'), 'query_id');
	}

	public function routePath()
	{
		return $this->belongsTo($this->getConfig()->get('route_path_model'), 'route_path_id');
	}

	public function pageViews($minutes, $results)
	{
		$query = $this->select(
				$this->getConnection()->raw('DATE(created_at) as date, count(*) as total')
			)->groupBy(
				$this->getConnection()->raw('DATE(created_at)')
			)
			->period($minutes)
			->orderBy('date');

		if ($results)
		{
			return $query->get();
		}

		return $query;
	}

	public function pageViewsByCountry($minutes, $results)
	{
		$query =
			$this
			->select(
				'visitors_geoip.country_name as label'
				, $this->getConnection()->raw('count(visitors_log.id) as value')
			)
			->join('visitors_sessions', 'visitors_log.session_id', '=', 'visitors_sessions.id')
			->join('visitors_geoip', 'visitors_sessions.geoip_id', '=', 'visitors_geoip.id')
			->groupBy('visitors_geoip.country_name')
			->period($minutes, 'visitors_log')
			->whereNotNull('visitors_sessions.geoip_id')
			->orderBy('value', 'desc');

		if ($results)
		{
			return $query->get();
		}

		return $query;
	}

	public function errors($minutes, $results)
	{
		$query = $this
					->with('error')
					->with('session')
					->with('path')
					->period($minutes, 'visitors_log')
					->whereNotNull('error_id')
					->orderBy('created_at', 'desc');

		if ($results)
		{
			return $query->get();
		}

		return $query;
	}

	public function allByRouteName($name, $minutes = null)
	{
		$result = $this
					->join('visitors_route_paths', 'visitors_route_paths.id', '=', 'visitors_log.route_path_id')

					->leftJoin(
						'visitors_route_path_parameters',
						'visitors_route_path_parameters.route_path_id',
						'=',
						'visitors_route_paths.id'
					)

					->join('visitors_routes', 'visitors_routes.id', '=', 'visitors_route_paths.route_id')

					->where('visitors_routes.name', $name);

		if ($minutes)
		{
			$result->period($minutes, 'visitors_log');
		}

		return $result;
	}
}
