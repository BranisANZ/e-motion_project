function formDataChange(btnModifier) {
    var _form = btnModifier.closest('form');
    var _data = {};

    _data[btnModifier.attr('name')] = btnModifier.val();

    $.ajax({
        url : _form.attr('action'),
        type: _form.attr('method'),
        data : _data,
    }).then(function(html) {
        $('#div-ajax').replaceWith(
            $(html).find('#div-ajax')
        );
    });
}

function swipeHide(btnNon) {
    id = this.id;
    console.log(id);
    console.log(btnNon);
}
