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