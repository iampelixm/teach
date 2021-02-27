function createModalDialog(data) {
    var dialog;
    if (data.dialog == 'yesno') {
        dialog = modal_template.yesno(data);
    } else if (data.dialog == 'yesnocancel') {

    } else if (data.dialog == 'videoplayer') {
        console.log(data);
        dialog = modal_template.videoplayer(data);
    }
    else {
        dialog = modal_template.yesno(data);
    }
    dialog = $(dialog).appendTo('body').modal('show')
        .on('hidden.bs.modal', function () {
            $(this).remove();
        });

    if (data.action == 'ajax') {
        console.log('ajax action');
        if (data.requesttype = 'post') {
            console.log('post request type');
            $(dialog).find('button.action').on('click', function () {
                console.log('action click');
                $.post(data.href,
                    data.data,
                    function (resp) {
                        alert(resp);
                        $(dialog).modal('hide');
                    });
            })
        } else {

        }
    }
    return dialog;
}

$('[data-role=dialog]').on('click', function () {
    createModalDialog($(this).data());
})