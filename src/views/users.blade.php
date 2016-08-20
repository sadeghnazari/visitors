@extends($stats_layout)

@section('page-contents')
	<table id="table_div" class="display" cellspacing="0" width="100%"></table>
@stop

@section('inline-javascript')
    @include(
        'blanfordia/visitors::_datatables',
        array(
            'datatables_ajax_route' => route('visitors.stats.api.users'),
            'datatables_columns' =>
            '
                { "data" : "user_id",    "title" : "Email", "orderable": true, "searchable": false },
                { "data" : "updated_at", "title" : "Last seen", "orderable": true, "searchable": false },
            '
        )
    )
@stop
