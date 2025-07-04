@php
if (!isset($id)) {
    $id = uniqid('modal_');
}
if (!isset($title)) {
    $title = 'Модальное окно';
}

if (!isset($body)) {
    $body = '';
}
@endphp
<div id="{{ $id }}" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    {{ $body }}
                    {{ $slot }}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
