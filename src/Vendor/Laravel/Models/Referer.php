<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class Referer extends Base {

	protected $table = 'visitors_referers';

	protected $fillable = array(
		'url',
		'host',
		'domain_id',
		'medium',
		'source',
		'search_terms_hash'
	);

	public function domain()
	{
		return $this->belongsTo($this->getConfig()->get('domain_model'));
	}

}
