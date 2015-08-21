<?php
/** reference javascript controller for "isProduction" checking */
$ci = & get_instance();
?>
var _gaq = _gaq || [];
        _gaq.push(
            ['_setAccount', '<?=GANALYTIC_CODE?>'],
            ['_setDomainName', '<?=WEBSITE_PRODUCTION_URL?>'],
            ['_setAllowLinker', true],
            ['_trackPageview'],
            ['_trackPageLoadTime'],
            ['b._setAccount', '<?=GANALYTIC_CROSS?>'],
            ['b._setDomainName', '<?=WEBSITE_PRODUCTION_URL?>'],
            ['b._setAllowLinker', true],
            ['b._trackPageview'],
            ['b._trackPageLoadTime']
        );
        (function() {
            var ga = document.createElement('script');     ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('http:'   == document.location.protocol ? 'http://ssl'   : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

        /** IP Address tracking for BOT identification */
        _gaq.push(['_setCustomVar', 1, 'IP', '<?=$ipAddress?>', 1]);

        /**
         * custom event and page tracking helper functions
         */
        var customGATracking = {

            /** track a specific event then redirect to new location if needed */
        	eventTrack: function(cat,act,val,follow) {
            	<?php
            	   /** QA / DEV log the GA tracking for analytics debugging */
            	   if (!$ci->isProduction()) {
                ?>
                    console.log('Google Analytics Event Track [CAT: ' + cat + '] [ACT: ' + act + '] [VAL: ' + val + '] [FOLLOW: ' + follow + ']');
            	<?php
            	   }
                ?>
		        if (!follow) var follow = false;
		        _gaq.push(['_trackEvent',cat,act,val]);
		        if (follow !== false) {
		            window.location = follow;
		            return false;
		        }
		    },

		    /** track a custom page view */
		    pageTrack: function(uri) {
            	<?php
            	   /** QA / DEV log the GA tracking for analytics browser debugging */
            	if (!$ci->isProduction()) {
                ?>
                    console.log('Google Analytics Page Track [URI: ' + uri + ']');
            	<?php
            	   }
                ?>
		        _gaq.push(['_trackPageview', uri]);
		    },

            /** track specific links being clicked */
            linkEvent : function(cat, act, val, uri) {
            	<?php
            	   /** QA / DEV log the GA tracking for analytics browser debugging */
            	if (!$ci->isProduction()) {
                ?>
                    console.log('Google Analytics Link Event Track [CAT: ' + cat + '] [ACT: ' + act + '] [VAL: ' + val + '] [URI: ' + uri + ']');
            	<?php
            	   }
                ?>
                customGATracking.eventTrack(cat, act, val);
            	setTimeout(function() {
            	    window.location = uri;
            	}, 500);
            	return false;
            }
        }