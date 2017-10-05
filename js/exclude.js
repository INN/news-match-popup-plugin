(function ($) {
	$(document).on( 'pumBeforeOpen', function( ) {
		var $popup = PUM.getPopup('#mc_embed_signup');
		console.log( $popup );

		// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Regular_Expressions#Using_special_characters
		var escaped = news_match_popup_basics_mailchimp.campaign.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
		console.log( escaped );
		var match = new RegExp( 'utm_source=' + escaped, 'i' );
		console.log( match );

		if (window.location.href.match( match )) {
			console.log( $popup.addClass('preventOpen') );
		}
	});
}(jQuery));
