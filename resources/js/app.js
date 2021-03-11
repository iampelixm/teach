require('./bootstrap');

import 'jquery-ui/ui/widgets/datepicker.js';
import 'jquery-ui/ui/widgets/sortable.js';
import videojs from 'video.js';
window.videojs = videojs;
window.modal_templates = require('./modal_templates.js');
window.ui_functions = require('./ui_functions.js');
import dt from 'datatables.net'
window.dt=dt
//var dt = window.dt = require( 'datatables.net' )();
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('.data-table').DataTable(
    {
        "order": [],
        "language": {
        "url": "/js/datatable-ru.json"
  }
    }
);
