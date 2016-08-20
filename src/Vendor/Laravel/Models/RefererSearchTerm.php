<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Models;

class RefererSearchTerm extends Base {

	protected $table = 'visitors_referers_search_terms';

	protected $fillable = array(
		'referer_id',
		'search_term',
	);

}
