require('./bootstrap');
//window.videojs = require('video.js');
import videojs from 'video.js';
window.videojs = videojs;
window.modal_templates = require('./modal_templates.js');
window.ui_functions = require('./ui_functions.js');
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
