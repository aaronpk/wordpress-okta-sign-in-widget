<!-- load the Okta sign-in widget-->
<script src="https://ok1static.oktacdn.com/assets/js/sdk/okta-signin-widget/2.6.0/js/okta-sign-in.min.js" type="text/javascript"></script>
<link href="https://ok1static.oktacdn.com/assets/js/sdk/okta-signin-widget/2.6.0/css/okta-sign-in.min.css" type="text/css" rel="stylesheet"/>
<link href="https://ok1static.oktacdn.com/assets/js/sdk/okta-signin-widget/2.6.0/css/okta-theme.css" type="text/css" rel="stylesheet"/>

<div id="primary" class="content-area">
    <div id="widget-container"></div>
</div>

<script>
	var signIn = new OktaSignIn({
		baseUrl: '<?php echo get_option('okta-base-url') ?>',
		clientId: '<?php echo get_option('okta-client-id') ?>',
		redirectUri: '<?php echo wp_login_url() ?>',
		authParams: {
			issuer: '<?php echo get_option('okta-base-url') ?>/oauth2/<?php echo get_option('okta-auth-server-id') ?>',
			responseType: 'code',
			display: 'page',
			scopes: ['openid', 'email'],
			state: '<?php echo Okta\OktaSignIn::generateState() ?>'
		}
	});
	signIn.session.get(function(res) {
		signIn.renderEl({
				el: '#widget-container'
			},
			function success(res) {}
		);
	});
</script>
