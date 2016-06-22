/**
 * Using jQuery and not $ to writing code because Wordpress
 * uses '$' selector and if I use it too, it can create some bugs.
 * This function permit to autoselect the shortcode from the admin page
 * for paste it directly.
 * For that, we loop and count all tr marker, and finally remove the last,
 * because the '<tr>' in header table is counting too.
 * 
 * @param {object} document
 *  The object represent the page.
 * @since Job Offer 1.0
 */
jQuery(document).ready(function() {
    var lastIndex = 0;
    jQuery('#job-offer-view-table tr').each(function() {
        lastIndex++;
    });
    
    for (var i = 0; i < lastIndex; i++) {
        jQuery('#jo_shortcode_' + i).click(function() {
            jQuery(this).select();
        });
    }
});