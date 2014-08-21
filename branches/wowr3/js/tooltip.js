
jQuery(document).ready( function($){

	var wraper = null;
	var contentCell = null;
	var cache = {};
	var browser = null;
	var UserAgent = {

			/**
			 * User agent header.
			 */
			header: navigator.userAgent.toLowerCase(),

			/**
			 * The current browser.
			 */
			browser: 'other',

			/**
			 * The current version, single number.
			 */
			version: null,

			/**
			 * Extract the browser and version.
			 *
			 * @constructor
			 */
			initialize: function() {
				var userAgent = UserAgent.header,
					version = ['other/0.0', '0'],
					browser = UserAgent.browser,
					className;

				// Browser
				if (userAgent.indexOf('firefox') != -1)
					browser = 'ff';

				else if (userAgent.indexOf('msie') != -1)
					browser = 'ie';

				else if (userAgent.indexOf('chrome') != -1)
					browser = 'chrome';

				else if (userAgent.indexOf('opera') != -1)
					browser = 'opera';

				else if (userAgent.indexOf('safari') != -1)
					browser = 'safari';

				// Version
				if (browser == 'ff')
					version = /firefox\/([-.0-9]+)/.exec(userAgent);

				else if (browser == 'ie')
					version = /msie ([-.0-9]+)/.exec(userAgent);

				else if (browser == 'chrome')
					version = /chrome\/([-.0-9]+)/.exec(userAgent);

				else if (browser == 'opera')
					version = /opera\/([-.0-9]+)/.exec(userAgent);

				else if (browser == 'safari')
					version = /safari\/([-.0-9]+)/.exec(userAgent);

				// version can be null if userAgent == 'firefox/', &c.
				if (version === null) {
					version = [browser + '/0.0', '0.0'];
				}

				UserAgent.browser = browser;

				UserAgent.version = (/(\d*)\D/.exec(version[1])) || '0';

				className = browser;

				if (UserAgent.version)
					className += ' '+ browser + UserAgent.version;

				if (browser == 'ie' && (UserAgent.version == 6 || UserAgent.version == 7))
					className += ' ie67';

				jQuery('html').addClass(className);
			}
		};
		
		
	// called last after all others have loaded
	var init = initialize();
	
	function isIE(version) {
		var browser = getBrowser();

		if (version)
			return ('ie'+ version == browser);
		else
			return (browser == 'ie6' || browser == 'ie7' || browser == 'ie8' || browser == 'ie9');
	}
	/**
	 * Detect the browser type, based on feature detection and not user agent.
	 *
	 * @return string
	 */
	function getBrowser() {
		if (browser)
			return browser;

		var s = jQuery.support;

		if (!s.hrefNormalized && !s.tbody && !s.style && !s.opacity) {
			if ((typeof document.body.style.maxHeight != "undefined") || (window.XMLHttpRequest))
				browser = 'ie7';
			else
				browser = 'ie6';

		} else if (s.hrefNormalized && s.tbody && s.style && !s.opacity) {
			browser = 'ie8';

		} else {
			browser = UserAgent.browser + UserAgent.version;
		}

		return browser;
	}
	
	function initialize() {
		var tooltipDiv = jQuery('<div/>').addClass('ui-tooltip').appendTo("body");

		if (isIE(6) && document.location.protocol === 'http:') {
			jQuery('<iframe/>', {
				src: 'javascript:void(0);',
				frameborder: 0,
				scrolling: 'no',
				marginwidth: 0,
				marginheight: 0
			}).addClass('tooltip-frame').appendTo('body');
		}

        // Assign to reference later
        wrapper = tooltipDiv;
    }
	
	jQuery('[data-tooltip]').hover(function(e){ // Hover event

		var t = jQuery(this).data('tooltip');
		var cap = jQuery(this).data('caption');
		content = _get_content(t, cap);
			jQuery('.ui-tooltip').show();

	}, function(){ // Hover off event
		jQuery('.ui-tooltip').empty();
		jQuery('.ui-tooltip').hide();

	}).mousemove(function(e){ // Mouse move event

		wrapper
		.css('top', (e.pageY - 10) + 'px')
		.css('left', (e.pageX + 20) + 'px');

	});
	
	function _get_content(content, cap)
	{
		typeid = content.split('-');
		cont = 'tooltips/'+typeid[0]+'/'+ typeid[1] +'';
		
		if (typeid[0] == 'text')
		{			
			url = location.hostname+roster_js.roster_path+'index.php?p=ajax-'+typeid[0]+'&id=plain';//'http://pvp-live.com/'+cont;
		}
		else
		{
			//alert(location.hostname);
			
			url = 'index.php?p=ajax-'+typeid[0]+'&id='+ typeid[1] +'';//'http://pvp-live.com/'+cont;
		}
		//url = 'http://localhost/item.php?itemid='+typeid[1];
		
		if (cache[content] != null)
		{
			jQuery('.ui-tooltip').empty().append(cache[content]);
		}
		else
		{
			jQuery.ajax({
				type: "POST",
				url: url,
				data: { tooltip: content, caption: cap},
				dataType: "html",
				global: false,
				beforeSend: function() {
					// Show "Loading..." tooltip when request is being slow
					jQuery('.ui-tooltip').empty().append('Loading...');
					setTimeout(function() {
						//if (!Tooltip.visible)
						//	Tooltip.position(node, Msg.ui.loading, options);
					}, 500);
				},
				success: function(data)
				{
					cache[content] = data;
					jQuery('.ui-tooltip').empty().append(data);
				},
				error: function(xhr) {
					if (xhr.status != 200){
						jQuery('.ui-tooltip').empty().append('Error...');
					}
				}
			});
		}
	}


});