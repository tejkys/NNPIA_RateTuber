$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).tooltip({
        track: true
    });
    $("#sort").selectmenu({
        change: function (event, ui) {
            this.form.submit();
        }
    });

    $("#dialog-channel-search").on("click", function () {
        let _this = this;
        let _content = $('#dialog-channel-content');
        let _form = $('#dialog-channel-form');
        _content.empty();
        _form.find('input[name=yt_id]').remove();
        $.ajax({
            method: "POST",
            url: "channels/search",
            data: $('#dialog-channel-form').serialize(),
            beforeSend: function () {
                $(_this).val('Loading ...');
                $(_this).prop('disabled', true);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
            }
        }).done(function (msg) {

            $(_this).prop('disabled', false);
            $(_this).val('Search');
            const obj = JSON.parse(msg);
            if (obj.items.length) {
                $('<img style="width: 60px;">').attr('src', obj.items[0].snippet.thumbnails.default.url).appendTo(_content);
                $("<div/>").html(obj.items[0].id.channelId).appendTo(_content);
                $("<div/>").html(obj.items[0].snippet.title).appendTo(_content);
                $('<input>').attr({
                    type: 'hidden',
                    name: 'yt_id',
                    value: obj.items[0].id.channelId
                }).appendTo(_form);
            } else {
                _content.html('Not found');
            }
        });
    });
    let dialog_channel = $("#dialog-channel").dialog({
        autoOpen: false,
        height: 400,
        width: 350,
        modal: true,
        buttons: {
            "Create channel": function () {
                if($('#dialog-channel-form').find('[name=yt_id]').length){
                    $('#dialog-channel-form').submit();
                } else {
                    alert('First find the channel');
                }
            },
            Cancel: function () {
                dialog_channel.dialog("close");
            }
        },
        close: function () {

        }
    });
    $("#create-channel").button().on("click", function () {
        dialog_channel.dialog("open");
    });
    $(".select-menu").selectmenu();
    $(function () {
        $("input[type=checkbox]").checkboxradio();
    });
    $(".rating-slider").each(function (index) {
        let val = $(this).data("value");

        $(this).css({
            "width": val + "%",
            "background": "linear-gradient(90deg, rgba(255,0,0,1) 0%, rgba(" + (255 - (val / 100) * 255) + "," + ((val / 100) * 255) + ",0,1) 100%)"
        });
    });
    $(".toggle-button").click(function () {
        $("#" + $(this).data('for')).toggle("slow", function () {
            // Animation complete.
        });
    });
});
