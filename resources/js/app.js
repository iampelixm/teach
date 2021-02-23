require('./bootstrap');
require('./modal_templates.js');
require('./ui_functions.js');
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

