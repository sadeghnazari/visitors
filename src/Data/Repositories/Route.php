<?php

namespace Blanfordia\Visitors\Data\Repositories;

use PragmaRX\Support\Config;

class Route extends Repository {

	public function __construct($model, Config $config)
	{
		parent::__construct($model);

		$this->config = $config;
	}

	public function isTrackable($route)
	{
		$forbidden = $this->config->get('do_not_track_routes');

		return
			! $forbidden ||
			! $route->currentRouteName() ||
			! in_array_wildcard($route->currentRouteName(), $forbidden);
	}

}
