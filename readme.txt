=== Job Offer by Nicolas GILLE ===
Contributors: Nicolas GILLE <nic.gille@gmail.com>
Donate link: 
Tags: job, jobs, offer, offers, traineeship
Requires at least: 4.5
Tested up to: 4.5
Stable tag: 1.2.0
License: Licence GPLv2 or Later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Job Offer allows you to add differents type of offers on your website.

== Description ==

Job Offer allows you to add differents type of offers on your website.

For more information, contact developer (Nicolas GILLE <nic.gille@gmail.com>).

= Features include: =

* Add job offer types and traineeship offer types.
* Add custom types of offer for more adaptability.
* Use TyniMCE editor if is enable for simplify the style of your offer.
* Create custom post to avoid to spamm WordPress post.
* Generate shortcodes for simplify integration on your website.
* Generate list of all offers present in database and redirect users on offer's information.
* Use shortcodes for generate one offer information if you like, for customised style for example.
* See directly and auto select shortcode beside all offers view.
* Modification of offer thanks to Offer post. 
*   /!\ Not change offer title here, modified it only in Job Offer setting.

= Usage: =

For using Job Offer, used the two shortcodes on your post or page.
    - [jo_jobs] for see all offers stored in your website and redirect it on the good post.
    - [jo_job id="[your_id]"] and replace [your_id] by offer's id for see the details about her.
For simplified, you can find these shortcodes directly in your admin page, beside "View all Offers" page.
You can add only [jo_jobs] for display all offers on your website.
When you create a offer, a post is generated, but don't worry, he's not display because the post status is pending.
Validate and publish your post and your offer will display for all users.

If you would add new offer type, check the file Enum.class.php in folder classes, 
and add your new type directly in the function _init_enum_() at the end of the class.
Use the same syntax for generate translation and don't forget to update your language file after this action.

= Translations: =

* French - by [Nicolas GILLE]

== Installation ==

1. Install Job Offer by uploading the files to your server
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Add [jo_jobs] on a WordPress post or page, but it's advisable to place it on a WordPress page, for display automatically all offers publish.
4. Go to the Job Offer settings under Setting and create your first offer, this action will auto generate a post not visible for the moment.
5. Change pending status of offer post present on Offer category on admin panel into publish for will display your offer on your website.
Or using the single shortcode [jo_job id="your_id"] present on job offer setting and paste it on your post or page for display offer's informations.


== Frequently Asked Questions ==

No questions yet.

== Screenshots ==

Nothing here for the moment.

== Changelog ==

= 1.2.0 =
Possibility to update an offer on post offer section and return modification on Job Offer setting.

= 1.1.2 =
Fixed update and delete for post. Now you can create, modify and delete every offer thanks to Job Offer settings.
Added Offer custom post type.
Auto generate custom post (on status pending) and after validation, he add automatically on your website without any manipulation.

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
