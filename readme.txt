=== News Match Popup Basics ===
Contributors: innlabs
Tags: popup, popmake
Tested up to: 4.8.2
Requires PHP: 7
Stable tag: 0.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An introduction to popups for Knight News Match program particpants, and others. Depends upon Popup Maker

== Description ==

This plugin integrates with the free [Popup Maker](https://wordpress.org/plugins/popup-maker/) plugin to create a default popup with preconfigured, recommended settings based upon the Institute for Nonprofit News' research as part of the [Knight Foundation's News Match](https://www.knightfoundation.org/articles/announcing-news-match-2017-2-million-fund-will-match-donations-to-nonprofit-newsrooms) program. While the News Match program is limited in membership, this plugin can be used by anyone.

In addition to creating a default popup, the plugin allows sites to suppress all Popup Maker popups on donation pages and enables suppression of popups that contain MailChimp signup forms when readers visit the site at a URL that contains a MailChimp-style `utm_source` referrer.

== Installation ==

First, install and activate [Popup Maker](https://wordpress.org/plugins/popup-maker/). Then, install and activate this plugin.

Upon activating this plugin, you should receive a notice in the WordPress plugin dashboard telling you that the plugin has been activated and a new default popup has been created. You can also check for a "News Match Default Popup" at **Popup Maker > All Popups** in the WordPress Dashboard.

Once the plugin has created the default popup, you can go ahead and uninstall the plugin.

== Frequently Asked Questions ==

= How does the default popup creation work? =

Upon activation, the plugin creates a new popup with recommended default settings.

= What are the default settings for the popup? =

The popup:

- is not published by default, but requires you to publish it before it becomes active on the site
- is the size "Large" from Popup Maker's settings
- appears at the center of the bottom of the reader's screen
- appears by sliding up from the bottom of the screen, over 350 millisenconds
- has a "Close" button
- does not prevent readers from interacting with the page by means of an overlay
- does not have a title
- automatically opens after 25 seconds on the page, because immediate popup appearances can be jarring
- once dismissed by a reader, does not appear again for a year or until the reader clears their browser's cookies, whichever comes first
- appears on the front page of the site
- uses Popup Maker's default theme

For more about Popup Maker's options for popup placement and behavior, read [the Popup Maker documentation for the Popup Editor](http://docs.wppopupmaker.com/article/39-creating-a-popup).

= Can I change those settings? =

Once the popup is created, you can modify the popup just like any other popup. The popup will not appear on your site until you publish it.

To change the appearance of the popup, you can use [Popup Maker's included theming engine](http://docs.wppopupmaker.com/category/154-theming-popups), CSS in your site's theme, [Jetpack's Custom CSS Editor](https://jetpack.com/support/custom-css/), or other tools that allow you to define new styles.

= How does the MailChimp popup suppression work? =

In the WordPress Dashboard, under the "Popup Maker" menu item, on the "News Match Popup Basics" page, there is a checkbox that enables MailChimp suppression. There is also a text box to set the `utm_source` parameter. MailChimp automatically appends this URL parameter to outbound links in your emails if you have [click tracking](https://kb.mailchimp.com/reports/enable-and-view-click-tracking) set up.

From one of the emails you have sent, find a link that contains a `utm_source=` parameter and copy the following argument text, up until any `&` character, into the text box. For example, a Nerd Alert newsletter sent by INN Labs contained a link that looked like this: `https://example.org/?utm_source=Nerd+Alert&utm_campaign=4d4ecd9f68-EMAIL_CAMPAIGN_2017_10_06&utm_medium=email&utm_term=0_1476113985-4d4ecd9f68-421742753`. From that URL, you would copy `Nerd+Alert` into the text box.

Once you have provided a `utm_source` parameter, checked the checkbox, and saved the settings, any popup that contains an HTML element with an `id` attribute equal to `mc_embed_signup`, or a CSS selector equal to `#mc_embed_signup`, will be suppressed. Suppression works client-side using JavaScript that runs in the visitor's browser.

= How does the donation page popup suppression work? =

In the WordPress Dashboard, under the "Popup Maker" menu item, on the "News Match Popup Basics" page, there is a checkbox that enables donation page popup suppression, and a text box that allows you to add the URLs of donation pages on your site.

Once at least one URL has been added to the text box, and the check box has been checked, the News Match Popup Basics plugin will prevent all Popup Maker popups from appearing on pages which have a URL that matches the URL setting. This function pre-empts the [Popup Maker conditions](http://docs.wppopupmaker.com/article/235-standard-conditions): no matter what conditions a pop has for display, that popup will not appear on a page with a matching URL.

= Do I need to install this plugin to participate in the Knight News Match program? =

No.

= Is there advice specific to Knight News Match program participants? =

We recommend that you use the [News Match Donation Shortcode](https://wordpress.org/plugins/news-match-donation-shortcode) plugin to place donation forms in popups on your site, and on your site's donation page.

We also recommend you use this plugin's suppression features to prevent all popups from appearing on your donation and subscription pages.

== Changelog ==
