<html>
<head>

</head>
<body>
<script type="text/javascript">

	var getUrlParameter = function getUrlParameter(sParam) {
		var sPageURL = decodeURIComponent(window.location.search.substring(1)),
			sURLVariables = sPageURL.split('&'),
			sParameterName,
			i;

		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');

			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : sParameterName[1];
			}
		}
	};

	if (!getUrlParameter('oauth_token') && !getUrlParameter('oauth_verifier')) {

		var data = {
			action: 'ywsfd_get_twitter_url'
		};

		window.opener.jQuery.post(window.opener.ywsfd.twitter_auth_ajax, data, function (response) {

			var data = JSON.parse(response);

			if (data.hasOwnProperty('error')) {

				alert(data.error);

			} else {

				window.location.href = data.success;

			}

		});

	} else {

		oauth_token = getUrlParameter('oauth_token');
		oauth_verifier = getUrlParameter('oauth_verifier');

		try {

			if (window.opener && !window.opener.closed) {

				window.opener.twitter_callback(oauth_token, oauth_verifier);

			} else {

				alert(window.opener.ywsfd.twitter_close);

			}

		} catch (ex) {

			setTimeout(function () {

				alert(window.opener.ywsfd.twitter_fail);
				window.close();

			}, 1);

		}
		
	}
</script>
</body>
</html>