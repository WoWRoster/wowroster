
//ApiTips

jQuery(function() {
	
});
var mouseX;
var mouseY;
jQuery(document).mousemove( function(e) {
   mouseX = (e.pageX + 10); 
   mouseY = (e.pageY + 10);
});  

jQuery(document).ready( function($){

ApiTips.initialize();

/*
jQuery( ".apitip" ).mouseover(function()
{
	var data = jQuery(this).data('item'),
	id = jQuery(this).data('id');//parseInt(href[1]);
	type = jQuery(this).data('type');//parseInt(href[1]);
	var typeid = jQuery(this).data('tooltip').split('-');
	query = (data) ? '&'+ data : "";
	//alert(id+'-'+query);
	Tooltip.show(jQuery(this), 'tooltips/'+typeid[0]+'/'+ typeid[1] +'', true);
	jQuery('.ui-tooltip').css({'top':mouseY,'left':mouseX}).show();
}).mousemove(function(e){
        var x = e.pageX;// - this.offsetLeft;
        var y = e.pageY;// - this.offsetTop;
        //jQuery('#example2-xy').html("X: " + x + " Y: " + y); 
		jQuery('.ui-tooltip').css({'top':mouseY,'left':mouseX});
    });
	// test for tooltips
	/*
$(function () {
	jQuery.data('tooltip').mouseover(function()
	//jQuery.data('tooltip').live("mouseover", function(e)
	{
		var data = jQuery(this).data('item'),
		id = jQuery(this).data('id');//parseInt(href[1]);
		type = jQuery(this).data('type');//parseInt(href[1]);
		var typeid = $(this).split('-');
		query = (data) ? '&'+ data : "";
		//alert(id+'-'+query);
		Tooltip.show(jQuery(this), 'tooltips/'+typeid[0]+'/'+ typeid[1] +'', true);
		jQuery('.ui-tooltip').css({'top':mouseY,'left':mouseX}).show();
	});/*.mousemove(function(e){
			var x = e.pageX;// - this.offsetLeft;
			var y = e.pageY;// - this.offsetTop;
			//jQuery('#example2-xy').html("X: " + x + " Y: " + y); 
			jQuery('.ui-tooltip').css({'top':mouseY,'left':mouseX});
		});
});*/
	
});
var Msg = {
	ui: {
		submit: 'Submit',
		cancel: 'Cancel',
		reset: 'Reset',
		viewInGallery: 'View in gallery',
		loading: 'Loading...',
		unexpectedError: 'An error has occurred',
		fansiteFind: 'Find this on...',
		fansiteFindType: 'Find {0} on...',
		fansiteNone: 'No fansites available.',
		flashErrorHeader: 'Adobe Flash Player must be installed to see this content.',
		flashErrorText: 'Download Adobe Flash Player',
		flashErrorUrl: 'http://get.adobe.com/flashplayer/'
	},
};
var ApiTips = {

	/**
	 * Initialize all wow tooltips.
	 *
	 * @constructor
	 */
	browser: null,
	baseUrl: window.location.hostname,//roster_js.roster_url, //'http://pvp-live.com/',
	
	initialize: function() {
		setTimeout(function() {
            //ApiTips.bindItemTooltips();
		}, 1);
	},
	isCallback: function(callback) {
		return (callback && typeof callback === 'function');
	},

	/**
	 * Is the browser using IE?
	 *
	 * @param version
	 * @return boolean
	 */
	isIE: function(version) {
		var browser = ApiTips.getBrowser();

		if (version)
			return ('ie'+ version == browser);
		else
			return (browser == 'ie6' || browser == 'ie7' || browser == 'ie8' || browser == 'ie9');
	},
	/**
	 * Detect the browser type, based on feature detection and not user agent.
	 *
	 * @return string
	 */
	getBrowser: function() {
		if (ApiTips.browser)
			return ApiTips.browser;

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
	},
	/*
	bindItemTooltips: function() {
		Tooltip.bind('.item, [data-item]', false, function() {
			if (this.rel == 'np')
				return;

			var self = jQuery(this),
				id,
				query;

			if (this.href !== null) {
				if (this.href == 'javascript:;' || this.href.indexOf('#') == 0)
					return;

				var data = self.data('item'),
					href = self.attr('href').split('/item/');

				id = parseInt(href[1]);
				query = (data) ? '&'+ data : "";

			} else {
				id = parseInt(self.data('item'));
				query = '';
			}

			if (id && id > 0)
				Tooltip.show(this, '/item.php?itemid='+ id +''+ query, true);
		});
	}*/
};

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
var Page = {

	/**
	 * Window object.
	 */
	object: null,

	/**
	 * Initialized?
	 */
	loaded: false,

	/**
	 * Window dimensions.
	 */
	dimensions: {
		width: 0,
		height: 0
	},

	/**
	 * Window scroll.
	 */
	scroll: {
		top: 0,
		width: 0
	},

	/**
	 * Initialized and grab window properties.
	 *
	 * @constructor
	 */
	initialize: function() {
		if (Page.loaded)
			return;

		if (!Page.object)
			Page.object = jQuery(window);

		Page.object
			.resize(Page.getDimensions)
			.scroll(Page.getScrollValues);

		Page.getScrollValues();
		Page.getDimensions();
		Page.loaded = true;
	},

	/**
	 * Get window scroll values.
	 */
	getScrollValues: function() {
		Page.scroll.top  = Page.object.scrollTop();
		Page.scroll.left = Page.object.scrollLeft();
	},

	/**
	 * Get window dimensions.
	 */
	getDimensions: function() {
		Page.dimensions.width  = Page.object.width();
		Page.dimensions.height = Page.object.height();
	}
};





