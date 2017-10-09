(function ($) {
	$(document).on( 'pumBeforeOpen', function( ) {
		var $popup = PUM.getPopup('.pum #mc_embed_signup');

		// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Regular_Expressions#Using_special_characters
		var escaped = news_match_popup_basics_mailchimp.campaign.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
		var match = new RegExp( 'utm_source=' + escaped, 'i' );

		if (window.location.href.match( match )) {
			// @since Popup Maker v1.6.6
			$popup.addClass('preventOpen');
		}
	});
}(jQuery));
