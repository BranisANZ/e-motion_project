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

$(function() {
    $('body').on("click", "#searchAnnouncement",  function() {
        var _form = $(this).closest('form');
        if(_form.valid()){
            $.ajax({
                type: 'GET',
                url: _form.attr('action'),
                data: _form.serialize(),
                dataType: "json",
            }).then(function(response) {
                $('#r-advantages-part').empty().append(response.html.content);
            });
        }
        return false;
    }).on("click", '#eSwipe', function() {
        var _this  = $(this);
        var _div   = _this.next('#modal-ajax');

        $.ajax({
            type: 'POST',
            url: _this.attr('data-href'),
        }).then(function(response) {
            _div.empty().append(response);
            $('#e-swipeForm').modal('show');
        });
    });
});