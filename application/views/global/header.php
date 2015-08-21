<?php  $thisPageURL = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>
<!doctype html>
<html <?php echo (isset($htmlAttributes)?$htmlAttributes:'ng-app="MainApp" ng-cloak'); ?>>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <meta name="format-detection" content="telephone=no" />
    <link rel="icon" href="<?=getStaticResourceURL('/app/img/favicon.ico');?>" />
    <meta name="google-site-verification" content="<?=isset($googleSiteVerification) ? $googleSiteVerification : GOOGLE_SITE_VERIFICATION_ID;?>" />
    <link rel="canonical" href="<?=isset($websiteCanonical) ? $websiteCanonical : $thisPageURL?>" />
    <link rel="og:url" href="<?=isset($ogURL) ? $ogURL : $thisPageURL?>" />
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
    <script src="<?=getStaticResourceURL('/app/scripts/vendor/angular.min.js');?>" type="text/javascript"></script>
    <script src="<?=getStaticResourceURL('/app/scripts/vendor/ng-modal.min.js');?>" type="text/javascript"></script>
    <script src="<?=getStaticResourceURL('/google_analytics.js');?>" type="text/javascript"></script>
    <script type="text/javascript" src="https://platform.linkedin.com/in.js">api_key: <?=LINKEDIN_APP_KEY?></script>
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