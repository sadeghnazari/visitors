@extends($stats_layout)

@section('page-contents')
	<table id="table_div" class="display" cellspacing="0" width="100%"></table>
@stop

@section('inline-javascript')
    @include(
        'blanfordia/visitors::_datatables',
        array(
            'datatables_ajax_route' => route('visitors.stats.api.events'),
            'datatables_columns' =>
            '
                { "data" : "name",  "title" : "Name", "orderable": true, "searchable": false },
                { "data" : "total", "title" : "# of occurrences in the period", "orderable": true, "searchable": false },
            '
        )
    )
@stop
