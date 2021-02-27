modal_template = {};
modal_template.yesno = function (data) {
    data.title = data.title || 'Уведомление';
    data.message = data.message || 'Точно?';
    return `
        <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">${data.title}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                ${data.message}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary action">Да</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Нет</button>
            </div>
            </div>
        </div>
        </div>    
    `;
};

modal_template.videoplayer = function (data) {
    data.title = data.title || 'Просмотр видео';
    data.message = data.message || 'Хотите видосик?';
    data.dialog_size = data.dialog_size || 'lg';
    return `
        <div class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-${data.dialog_size}" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">${data.title}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <video id="modal_video_player" controls style="width: 100%" class="vpl" playsinline>
                        <source src="${data.data.video}" type="video/mp4">
                    </video>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
                </div>
            </div>
        </div>    
    `;
}; 