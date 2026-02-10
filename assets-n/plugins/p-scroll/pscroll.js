(function($) {
	"use strict";

	// For APP-SIDEBAR
	const ps = new PerfectScrollbar('.app-sidebar', {
	  useBothWheelAxes:true,
	  suppressScrollX:true,
	});

	// For Header Message dropdown
	const ps2 = new PerfectScrollbar('.message-menu', {
		useBothWheelAxes:true,
		suppressScrollX:true,
	});

	// For Header Notification dropdown
	const ps3 = new PerfectScrollbar('.notifications-menu', {
	useBothWheelAxes:true,
	suppressScrollX:true,
	});


})(jQuery);