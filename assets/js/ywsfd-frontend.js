jQuery(function ($) {

    /**
     * If Facebook active
     */
    if (ywsfd.facebook == 'yes') {

        $.getScript("//connect.facebook.net/" + ywsfd.locale + "/sdk.js", function () {

            FB.init({
                appId  : ywsfd.fb_app_id,
                xfbml  : true,
                version: 'v2.7'
            });

            FB.Event.subscribe("edge.create", function (href, widget) {

                get_coupon('fblike');

            });

            FB.XFBML.parse();

            $('body').trigger('facebook_button').trigger('facebook_share');

        });

        $("a.ywsfd-facebook-share").click(facebook_share);

        /** Share Button Function */
        function facebook_share(e) {

            e.preventDefault();

            var url = $(this).data('href');

            FB.ui({
                method : 'feed',
                link   : url,
                caption: '',
                display: 'popup'
            }, function (response) {

                if (typeof response != 'undefined'){
                    
                    get_coupon('fbshare');

                }

            });

        }

    }

    /**
     * If Twitter active
     */
    if (ywsfd.twitter == 'yes') {

        var tweet_textarea = $('.ywsfd-twitter-form-wrapper .input-textarea');

        twitter_length(tweet_textarea);

        tweet_textarea.on('change keyup input', function () {

            twitter_length($(this));

        });

        tweet_textarea.on('blur input change', function (e) {

            var $this = $(this),
                $parent = $this.closest('.form-row'),
                validated = true;

            if ($parent.is('.ywsfd-validate-required')) {
                if ($this.val() === '') {
                    $parent.removeClass('ywsfd-validated').addClass('ywsfd-invalid');
                    validated = false;
                }
            }

            if (validated) {
                $parent.removeClass('ywsfd-invalid').addClass('ywsfd-validated');
            }

        });

        $('.ywsfd-tweet-button').unbind().bind('click', function (e) {

            e.preventDefault();

            $.when(twitter_auth()).then(function (response) {

                twitter_show_form(response.oauth_token, response.oauth_verifier)

            })

        });

        $('body').trigger('twitter_button');

        function twitter_auth() {

            var dfd = $.Deferred(),
                popup = window.open(ywsfd.twitter_login, "", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=800,height=450");

            window.twitter_callback = function (oauth_token, oauth_verifier) {

                popup.close();

                dfd.resolve({
                    oauth_token   : oauth_token || null,
                    oauth_verifier: oauth_verifier || null
                });

            };

            return dfd.promise();

        }

        function twitter_show_form(oauth_token, oauth_verifier) {

            $('.ywsfd-twitter-form-wrapper').addClass('opened');

            $('.ywsfd-send-tweet').unbind().bind('click', function () {

                var $social = $('.ywsfd-social');

                if ($social.is('.processing')) {
                    return false;
                }

                $social.addClass('processing');

                var form_data = $social.data();

                if (form_data["blockUI.isBlocked"] != 1) {
                    $social.block({
                        message   : null,
                        overlayCSS: {
                            background: '#fff',
                            opacity   : 0.6
                        }
                    });
                }

                $.ajax({
                    type    : 'POST',
                    url     : ywsfd.twitter_send_ajax,
                    data    : {
                        oauth_token   : oauth_token || null,
                        oauth_verifier: oauth_verifier || null,
                        tweet         : $('#ywsfd_tweet_text').val(),
                        sharing_url   : $('.ywsfd-tweet-button').data('href')
                    },
                    success : function (code) {
                        var result = '';

                        try {
                            // Get the valid JSON only from the returned string
                            if (code.indexOf('<!--WC_START-->') >= 0)
                                code = code.split('<!--WC_START-->')[1]; // Strip off before after WC_START

                            if (code.indexOf('<!--WC_END-->') >= 0)
                                code = code.split('<!--WC_END-->')[0]; // Strip off anything after WC_END

                            // Parse
                            result = $.parseJSON(code);

                            if (result.status === 'success') {

                                get_coupon('twitter');

                            } else if (result.status === 'failure') {

                                throw 'Result failure';

                            } else {

                                throw 'Invalid response';

                            }

                        } catch (err) {

                            // Remove old errors
                            $('.woocommerce-error, .woocommerce-message').remove();

                            // Add new errors
                            if (result.messages) {

                                $('.ywsfd-errors').append(result.messages);

                            } else {

                                $('.ywsfd-errors').append(code);

                            }

                            // Cancel processing
                            $social.removeClass('processing').unblock();

                            // Scroll to top
                            $('html, body').animate({
                                scrollTop: ( $social.offset().top - 100 )
                            }, 1000);

                        }
                    },
                    dataType: 'html'
                });

            });

            $('.ywsfd-cancel-tweet').unbind().bind('click', function () {

                $('.ywsfd-twitter-form-wrapper').removeClass('opened');

            });

        }

        function twitter_length(textarea) {

            if (textarea.val() === undefined) {
                return;
            }

            var length = textarea.val().length,
                chars = 160 - length,
                char_count = $('.ywsfd-char-count');

            char_count.find('span').text(chars);

            if (chars < 0) {

                char_count.addClass('maxed-out');

            } else {

                char_count.removeClass('maxed-out');

            }

        }

    }

});


/**
 * If Google+ active
 */
if (ywsfd.google == 'yes') {

    /*var share_timer = null,
     counter = 0;*/

    window.___gcfg = {
        lang: ywsfd.locale.substring(0, 2)
    };

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = '//apis.google.com/js/plusone.js?onload=onGoogleLoad';
        js.async = true;
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'google-sdk'));

    function onGoogleLoad() {

        jQuery('body').trigger('google_button').trigger('google_share');

    }

    window.onmessage = function (mes) {

        if (mes.data.indexOf("!_")) {
            return;
        }

        var s = mes.data.replace("!_", "");

        if (s.indexOf("{h:") != -1) {
            return;
        }

        s = jQuery.parseJSON(s);

        if (s.s.indexOf("_g_restyleMe") != -1 && Object.keys(s.a[0]).length == 2 && s.a[0].hasOwnProperty("height") && s.a[0].hasOwnProperty("width")) {
            get_coupon('gpshare');
        }

    }

}

/**
 * Callback for Google+ button
 * @param jsonParam
 */
function gpCallback(jsonParam) {

    if (jsonParam.state == 'on') {

        get_coupon('gpplus');

    }

}

/**
 * Callback for Google+ Share button
 * @param jsonParam
 */
/*function gpShareCallback(jsonParam) {

 share_timer = setInterval(function () {
 counter++;
 if (counter == 4) {
 get_coupon('gpshare');
 clearInterval(share_timer);
 }

 }, 1000);

 //console.log('share state ' + JSON.stringify(jsonParam));
 //if (jsonParam.type == 'confirm') { get_coupon(); }

 }

 function gpStopShareCallback(jsonParam) {

 //console.log('share state ' + JSON.stringify(jsonParam));

 if (share_timer != null) {
 counter = 0;
 clearInterval(share_timer);

 }

 }*/

/**
 * Get the coupon and add it to cart
 */
function get_coupon(social) {

    var $social = jQuery('.ywsfd-social');

    if (social !== 'twitter' && social !== 'linkedin') {

        if ($social.is('.processing')) {
            return false;
        }

        $social.addClass('processing');

        var form_data = $social.data();

        if (form_data["blockUI.isBlocked"] != 1) {
            $social.block({
                message   : null,
                overlayCSS: {
                    background: '#fff',
                    opacity   : 0.6
                }
            });
        }

    }

    jQuery.ajax({
        type    : 'POST',
        url     : ywsfd.ajax_social_url,
        data    : {
            post_id: ywsfd.post_id
        },
        success : function (code) {
            var result = '';

            try {
                // Get the valid JSON only from the returned string
                if (code.indexOf('<!--WC_START-->') >= 0)
                    code = code.split('<!--WC_START-->')[1]; // Strip off before after WC_START

                if (code.indexOf('<!--WC_END-->') >= 0)
                    code = code.split('<!--WC_END-->')[0]; // Strip off anything after WC_END

                // Parse
                result = jQuery.parseJSON(code);

                if (result.status === 'success') {

                    setTimeout(function () {

                        if (result.redirect.indexOf("https://") != -1 || result.redirect.indexOf("http://") != -1) {
                            window.location = result.redirect;
                        } else {
                            window.location = decodeURI(result.redirect);
                        }

                    }, 10000);


                } else if (result.status === 'failure') {
                    throw 'Result failure';
                } else {
                    throw 'Invalid response';
                }
            }

            catch (err) {

                // Remove old errors
                jQuery('.woocommerce-error, .woocommerce-message').remove();

                // Add new errors
                if (result.messages) {
                    jQuery('.ywsfd-errors').append(result.messages);
                } else {
                    jQuery('.ywsfd-errors').append(code);
                }

                // Cancel processing
                $social.removeClass('processing').unblock();

                // Scroll to top
                jQuery('html, body').animate({
                    scrollTop: ( jQuery('.ywsfd-social').offset().top - 100 )
                }, 1000);

            }
        },
        dataType: 'html'
    });

    return false;

}