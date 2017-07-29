<html>
<head>

</head>
<body>
<script>

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

	if (!getUrlParameter('code') && !getUrlParameter('state')) {
		var data = {
			action: 'ywsfd_get_linkedin_url'
		};

		window.opener.jQuery.post(window.opener.ywsfd.linkedin_auth_ajax, data, function (response) {

			var data = JSON.parse(response);

			if (data.hasOwnProperty('error')) {

				alert(data.error);

			} else {

				window.location.href = data.success;

			}
		});

	} else {

		code = getUrlParameter('code');
		state = getUrlParameter('state');

		try {

			if (window.opener && !window.opener.closed) {

				window.opener.linkedin_callback(code, state);

			} else {

				alert(window.opener.ywsfd.linkedin_close);

			}

		} catch (ex) {

			setTimeout(function () {

				alert(window.opener.ywsfd.linkedin_fail);

				window.close();

			}, 1);

		}

	}
</script>
</body>
</html>