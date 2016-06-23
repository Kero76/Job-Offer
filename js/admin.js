/**
 * Using 'jQuery' and not '$' to writing code because Wordpress
 * uses '$' selector and if I use it too, it can create some bugs.
 *
 * @since Job Offer 1.0.1 
 *  -> Added function for protect database from empty field.
 *  -> Added function for protect potential offer's deletions.
 * @since Job Offer 1.0.0
 */

/**
 * This function is loaded when the webpage is loaded.
 * In fact, it represent the event onload on javascript.
 * 
 * @param {object} document
 *  The object represent the page.
 */
jQuery(document).ready(function() {
    
    /**
     * This function allow to autoselect the shortcode from the admin page
     * for paste it directly.
     * For that, we loop and count all tr marker, and finally remove the last,
     * because the '<tr>' in header table is counting too.
     */
    var lastIndex = 0;
    jQuery('#job-offer-view-table tr').each(function() {
        lastIndex++;
    });
    
    for (var i = 0; i < lastIndex; i++) {
        jQuery('#jo_shortcode_' + i).click(function() {
            jQuery(this).select();
        });
    }
    
    /**
     * This function add an protection from forms used in admin page.
     * Indeed, before submit the new offer or update offer, it verify the content of all
     * fields and if one of them is empty, the submission is canceled to avoid to send empty data on Database.
     * Furthermore, a little message appear under the field for precised the error.
     * Oherwise, the form is submitted and the data are send on Database.
     */
    jQuery('#jo_form').submit(function(event) {
        var title = jQuery('#jo_title').val().trim();
        var content;
        if (jQuery("#wp-jo_content-wrap").hasClass("tmce-active")) {
            content = tinyMCE.activeEditor.getContent();
        } else {
            content = jQuery('#jo_content').val().trim();
        }
        
                
        (title == '') ? jQuery('#job-offer-warning-message-title').text('Title is empty').show() : jQuery('#job-offer-warning-message-title').hide();
        (content == '') ? jQuery('#job-offer-warning-message-content').text('Content is empty').show() : jQuery('#job-offer-warning-message-content').hide();
        if ((title == '') || (content ==''))
            event.preventDefault();
    });
    
    /**
     * This function launch a pop-up before deleted an offer.
     * In fact, when you delete on offer, this action is irreversible, and if you miss click,
     * you can potentially deleted on offer. So thos action is a protection against that.
     */
    jQuery('.job-offer-button-deletion').click(function(event) {
        if (!confirm('Really want to delete this offer ? (This action is irreversible)')) {
            event.preventDefault();
        }
    });
});