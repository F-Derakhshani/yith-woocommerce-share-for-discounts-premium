jQuery(function ($) {

    //TinyMCE Button
    var image_url = '';
    tinymce.create('tinymce.plugins.YITH_WooCommerce_Share_For_Discounts', {
        init         : function (ed, url) {
            ed.addButton('ywsfd_shortcode', {
                title  : 'Add YITH WooCommerce Share For Discounts shortcode',
                image  : url + '/../images/icon_shortcode.png',
                onclick: function () {
                    ed.insertContent("[ywsfd_shortcode]");
                }
            });
        },
        createControl: function (n, cm) {
            return null;
        },
        getInfo      : function () {
            return {
                longname : 'YITH WooCommerce Share For Discounts',
                author   : 'YITHEMES',
                authorurl: 'http://yithemes.com/',
                infourl  : 'http://yithemes.com/',
                version  : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('ywsfd_shortcode', tinymce.plugins.YITH_WooCommerce_Share_For_Discounts);

});
