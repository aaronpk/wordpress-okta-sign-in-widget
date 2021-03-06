<!-- load the Okta sign-in widget-->
<script src="https://global.oktacdn.com/okta-signin-widget/4.1.3/js/okta-sign-in.min.js" type="text/javascript"></script>
<link href="https://global.oktacdn.com/okta-signin-widget/4.1.3/css/okta-sign-in.min.css" type="text/css" rel="stylesheet"/>

<style type="text/css">
    body {
        font-family: montserrat,Arial,Helvetica,sans-serif;        
    }

    #wordpress-login{
        text-align: center;
    }

    #wordpress-login a{
        font-size:10px;
        color: #999;
        text-decoration:none;
    }

    #error {
        max-width: 500px;
        margin: 20px auto;
        padding: 20px;
        border: 1px #d93934 solid;
        border-radius: 6px;
    }
    #error h2 {
        color: #d93934;
    }
</style>

<?php if(isset($_GET['error'])): ?>
<div id="error">
    <h2>Error: <?php echo htmlspecialchars($_GET['error']) ?></h2>
    <p><?php echo htmlspecialchars($_GET['error_description']) ?></p>
</div>
<?php endif ?>

<div id="primary" class="content-area">
  <div id="widget-container"></div>
  <?php if(get_option('okta-allow-wordpress-login')): ?>
      <div id="wordpress-login"><a href="<?php echo wp_login_url(); ?>?wordpress_login=true">Login via Wordpress</a></div>
  <?php endif ?>
</div>

<script>
    var signIn = new OktaSignIn({
        baseUrl: '<?php echo parse_url($issuer = get_option('okta-issuer-url'), PHP_URL_SCHEME).'://'.parse_url($issuer, PHP_URL_HOST) ?>',
        redirectUri: '<?php echo wp_login_url() ?>',
        el: '#widget-container',
        authParams: {
            display: 'page',
            issuer: '<?php echo get_option('okta-issuer-url') ?>'
        }
    });
    if(signIn.hasTokensInUrl()) {
        if(document.getElementById('wordpress-login')) {
            document.getElementById('wordpress-login').remove();
        }
        // Grab the auth code from the URL and exchange it for an ID token
        signIn.authClient.token.parseFromUrl()
            .then(function (res) {
                signIn.authClient.tokenManager.add('id_token', res.tokens.idToken);
                // Redirect back to this page with the ID token in the URL. The backend will validate it and log the user in.
                window.location = '<?php echo wp_login_url() ?>?log_in_from_id_token='+res.tokens.idToken.value;
            });
    }
    else {
        signIn.showSignInToGetTokens({
            clientId: '<?php echo get_option('okta-widget-client-id') ?>',
            getAccessToken: false,
            getIdToken: true,
            scope: '<?php echo apply_filters( 'okta_widget_token_scope', 'openid email') ?>'
        });
    }
</script>
