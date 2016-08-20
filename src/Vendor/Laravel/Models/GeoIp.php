<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class GeoIp extends Base {

	protected $table = 'visitors_geoip';

	protected $fillable = array(
		'country_code',
		'country_code3',
		'country_name',
		'region',
		'city',
		'postal_code',
		'latitude',
		'longitude',
		'area_code',
		'dma_code',
		'metro_code',
		'continent_code',
	);

}
