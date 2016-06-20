/**
 * Using jQuery and not $ to writing code because Wordpress
 * uses '$' selector and if I use it too, it can create some bugs.
 * 
 * @param {type} param
 */
jQuery(document).ready(function() {
   
    /**
     * Used for 'autoselect' the shortcode present in admin view page.
     */
    jQuery("#jo_shortcode").click(function() {
       this.select();
   });
});
