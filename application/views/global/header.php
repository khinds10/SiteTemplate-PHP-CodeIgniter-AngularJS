<?php  $thisPageURL = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<html <?php echo (isset($htmlAttributes)?$htmlAttributes:'ng-app="MainApp" ng-cloak'); ?>>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <meta name="format-detection" content="telephone=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="icon" href="<?=getStaticResourceURL('/app/img/favicon.ico');?>" />
    <meta name="google-site-verification" content="<?=isset($googleSiteVerification) ? $googleSiteVerification : GOOGLE_SITE_VERIFICATION_ID;?>" />
    <link rel="canonical" href="<?=isset($websiteCanonical) ? $websiteCanonical : $thisPageURL?>" />
    <link rel="og:url" href="<?=isset($ogURL) ? $ogURL : $thisPageURL?>" />
    <link rel="apple-touch-icon" href="app/img/apple-touch-icon.png">
    <meta name=viewport content="<?=isset($websiteMetaViewport) ? $websiteMetaViewport : WEBSITE_META_VIEWPORT;?>" />
    <meta name="description" content="<?=isset($websiteDescription) ? $websiteDescription : WEBSITE_DESCRIPTION;?>" />
    <meta name="keywords" content="<?=isset($websiteKeyWords) ? $websiteKeyWords : WEBSITE_KEYWORDS;?>" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:title" content="<?=isset($websiteTitle) ? $websiteTitle : WEBSITE_TITLE;?>" />
    <meta property="og:description" content="<?=isset($websiteDescription) ? $websiteDescription : WEBSITE_DESCRIPTION;?>" />
    <meta property="og:site_name" content="<?=isset($websiteName) ? $websiteName : WEBSITE_NAME;?>" />
    <meta property="og:image" content="<?=isset($ogImage) ? $ogImage : "http://".$_SERVER["HTTP_HOST"]."/app/img/logo-header.png";?>" />
    <meta property="og:type" content="website" />
    <title><?=isset($websiteTitle) ? $websiteTitle : WEBSITE_TITLE;?></title>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/foundation/5.5.2/css/foundation.min.css"></link>
    <?=isset($includeCSS)?$includeCSS :"<link rel=\"stylesheet\" type=\"text/css\" href=\"".getStaticResourceURL('/app/css/application.css')."\"></link>\n";?>
    <link rel="stylesheet" type="text/css" href="<?=getStaticResourceURL('/app/css/angular/ng-modal.css');?>"></link>
    <script src="app/scripts/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.6/angular.min.js" type="text/javascript"></script>
    <script src="<?=getStaticResourceURL('/app/scripts/vendor/ng-modal.min.js');?>" type="text/javascript"></script>
    <script src="<?=getStaticResourceURL('/google_analytics.js');?>" type="text/javascript"></script>
    <script type="text/javascript" src="//platform.linkedin.com/in.js">api_key: <?=LINKEDIN_APP_KEY?></script>
    <script src="//www.google.com/recaptcha/api.js?onload=loadCaptcha&render=explicit" async defer></script>
    <script type="text/javascript">
	   var captchaContainer = null;
	   window.grecaptchaValue = null;
	   var loadCaptcha = function() {
	     captchaContainer = grecaptcha.render('captcha_container', {
	       'sitekey' : '<?=NOCAPTCHA_RECAPTCHA_KEY?>',
	       'callback' : function(response) {
		    var captchaErrorMessage = document.getElementById('captcha-error-message');
		    captchaErrorMessage.style.display = 'none';
		    captchaErrorMessage.style.visibility = 'hidden';
		    window.grecaptchaValue = response;
	       }
	     });
	   }; 
   </script>
    <?=(isset($angularJS)) ? $angularJS : '' ;?>
    <?=(isset($includeJS)) ? $includeJS : '' ;?>
</head>
<body>
	<!-- CodeIgniter/AngularJS UXD Project -->
	<div id="fb-root"><!-- Required for Facebook OAuth --></div>
	<script type="text/javascript">
       /** load in the Facebook App for processing login-as button */
        window.fbAsyncInit = function() {FB.init({appId: '<?=FACEBOOK_APP_KEY?>', status: true, cookie: true, xfbml: true, oauth: true}); };
        (function() {
            var e = document.createElement('script');
            e.type = 'text/javascript';
            e.src = document.location.protocol +
            '//connect.facebook.net/en_US/all.js'; e.async = true;
            document.getElementById('fb-root').appendChild(e);
        }());
    </script>
    <!--[if lt IE 8]>
    	<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->