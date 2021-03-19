require('./bootstrap');

import 'jquery-ui/ui/widgets/datepicker.js';
import 'jquery-ui/ui/widgets/sortable.js';
import videojs from 'video.js';
window.videojs = videojs;
window.modal_templates = require('./modal_templates.js');
window.ui_functions = require('./ui_functions.js');
require('./ui_templates');
import dt from 'datatables.net'
window.dt = dt

import ClassicEditor from 'ckeditor5-build-laravel-image';
require('ckeditor5-build-laravel-image/build/translations/ru');
//require('./ckeditor/ckeditor.js');

window.CKEditors = [];

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

$('.ckeditor').each(function (ei, el) {
    ClassicEditor
        .create(el, {
            toolbar: {
                // items: [
                //     'undo',
                //     'redo',
                //     'heading',
                //     '|',
                //     'bold',
                //     'italic',
                //     'link',
                //     'bulletedList',
                //     'numberedList',
                //     '|',
                //     'outdent',
                //     'indent',
                //     'alignment',
                //     '|',
                //     'imageUpload',
                //     'blockQuote',
                //     'insertTable',
                //     'mediaEmbed',
                //     'fontColor',
                //     'fontBackgroundColor',
                //     'fontSize',
                //     'highlight',
                //     'imageInsert',
                //     'specialCharacters',
                //     'superscript',
                //     'underline'
                // ]
            },
            language: 'ru',
            simpleUpload: {
                uploadUrl: {
                    url: $(el).data('upload_url') || '/ckeditor-image'
                }
            },
            image: {
                styles: ['alignLeft', 'alignCenter', 'alignRight'],
                resizeOptions: [
                    {
                        name: 'imageResize:original',
                        label: 'Original',
                        value: null
                    },
                    {
                        name: 'imageResize:100',
                        label: '100%',
                        value: '100'
                    },
                    {
                        name: 'imageResize:75',
                        label: '75%',
                        value: '75'
                    },
                    {
                        name: 'imageResize:50',
                        label: '50%',
                        value: '50'
                    },
                    {
                        name: 'imageResize:40',
                        label: '40%',
                        value: '40'
                    },
                    {
                        name: 'imageResize:30',
                        label: '30%',
                        value: '30'
                    },
                    {
                        name: 'imageResize:25',
                        label: '25%',
                        value: '25'
                    },
                    {
                        name: 'imageResize:20',
                        label: '20%',
                        value: '20'
                    },
                ],
                toolbar: [
                    'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight',
                    '|',
                    'imageResize',
                    'imageTextAlternative'
                ]
            },
        })
        .then(editor => {
            window.CKEditors.push(editor);
        })
        .catch(err => {
            console.error(err.stack);
        });
});



