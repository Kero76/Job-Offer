=== Job Offer by Nicolas GILLE ===
Contributors: Nicolas GILLE<nic.gille@gmail.com>
Donate link: 
Tags: cookie, job, jobs, offer, offers, traineeship
Requires at least: 4.5
Tested up to: 4.5
Stable tag: 1.1.1
License: Licence GPLv2 or Later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Job Offer allows you to add differents type of offers on your website.

== Description ==

Job Offer allows you to add differents type of offers on your website.

For more information, contact developper (Nicolas GILLE <nic.gille@gmail.com>).

= Features include: =

* Added some type of offer.
* Using TyniMCE editor if is enable for simplify the style of your offer.
* Generate shortcode for simplify integration on your website.
* Generate automaticaly the good link on your offers pages.
* Using custom shortcode for generate one offer infotmation if you like, for customised style for example.
* See directly and autoselect the shortcode beside all offers view.

= Usage: =

For using Job Offer, using two shortcode on your post or page.
    - [jo_jobs] for see all offers stored in your website and redirect it on the good post.
    - [jo_job id="[your_id]"] when replace [your_id] by offer id for see the details about her.
For simplified, you can find these shortcodes directly in your admin page, beside "View all Offers" page.
You can add only [jo_jobs] for display all offers on your website.
When you create a offer, a post is generated, but don't worry, he's not display because the post status is pending.
Validate and publish your post and your offer will display for all users.

If you would add new offer type, check the file Enum.class.php in folder classes, 
and add your new type directly in the function _init_enum_() at the end of the class.

= Translations: =

* French - by [Nicolas GILLE]

== Installation ==

1. Install Job Offer by uploading the files to your server
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the Job Offer settings under Setting and create your first offer.
4. Add shortcodes for displaying offers in your website.
5. Create offers and validate and publish new post for displayed automatically your offer,
or using shortcode with id for cusomize your website.

== Frequently Asked Questions ==

No questions yet.

== Screenshots ==

Nothing here for the moment.

== Changelog ==

= 1.1.1 =
Fixed translations.
Fixed a little bug from sanitizied title before added on Database.
Fixed url writting on using &amp; instead of &.

= 1.1.0 =
Created post on posts database. Avoid to create post with single offer shortcode.
Added color rectangle on admin view for see clearly the type of offer.

= 1.0.1 =
Fixed lot of graphics bugs.
Added some scripts protections about admin page.

= 1.0.0 =
Initial release
