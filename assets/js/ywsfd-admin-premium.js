jQuery(function ($) {

    $('body')
        .on('click', 'button.ywsfd-purge-coupon', function () {

            var result = $('.ywsfd-clear-result'),
                data = {
                    action: 'ywsfd_clear_expired_coupons'
                };

            result.show();
            $(this).hide();

            $.post(ywsfd_admin.ajax_url, data, function (response) {

                result.removeClass('clear-progress');

                if (response.success) {

                    result.addClass('clear-success');
                    result.html(response.message);

                } else {

                    result.addClass('clear-fail');
                    result.html(response.error);

                }

            });

        });

    $(document).ready(function ($) {

        $('input.ywsfd-checkbox').change(function () {

            var $this = $(this).attr('id').replace('_enable', '');

            if ($(this).is(':checked')) {

                $('#' + $this + '_position').parent().parent().show();

            } else {

                $('#' + $this + '_position').parent().parent().hide();

            }

        }).change();

        //upload icon
        var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment,
            upload_button = $('.ywsfd_upload_button'),
            upload_img_url = $('.ywsfd_upload_img_url'),
            upload_preview_div = $('.ywsfd_upload_preview'),
            upload_preview_img = $('.ywsfd_upload_preview_img');

        upload_img_url.change(function () {
            var url = upload_img_url.val(),
                re = new RegExp('(http|ftp|https)://[a-zA-Z0-9@?^=%&amp;:/~+#-_.]*.(jpg|jpeg|png)');

            if (re.test(url)) {
                upload_preview_img.attr('src', url);
                upload_preview_div.show();
            } else {
                upload_preview_img.attr('');
                upload_preview_div.hide();

            }
        }).change();

        upload_button.on('click', function () {

            var send_attachment_bkp = wp.media.editor.send.attachment;
            _custom_media = true;

            wp.media.editor.send.attachment = function (props, attachment) {
                if (_custom_media) {
                    upload_img_url.val(attachment.url).change()
                } else {
                    return _orig_send_attachment.apply(this, [props, attachment]);
                }
            };

            wp.media.editor.open(upload_button);
            return false;
        });

        $('.add_media').on('click', function () {
            _custom_media = false;
        });

    });


});
