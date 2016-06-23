<?php    
    require_once('classes/Form.class.php');
    require_once('classes/Enum.class.php');
    
    $form = new Form();
    $enum = Enum::get_instance();

    /* Options for textarea */
    $rows = 15;
    $cols = 100;
?>
<div class="wrap">
    <h2><?php _e('Job Offer', 'job-offer'); ?></h2>
</div>

<div>
    <h3 class="job-offer-title"><?php _e('Add a new Offer', 'job-offer'); ?></h3>
    <form id="jo_form" action="?page=job-offer/job-offer.php&action=addoffer" method="post">
        <table class="form-table">
            <tr>
                <th><label for="jo_title"><?php _e('Title', 'job-offer'); ?></label></th>
                <td>
                    <?php echo $form->get_title_form(); ?>
                    <p id="job-offer-warning-message-title"></p>
                </td>
            </tr>
            
            <tr>
                <th><label for="jo_content"><?php _e('Content', 'job-offer'); ?></label></th>
                <td>
                    <?php echo $form->get_content_form($rows, $cols); ?>
                    <p id="job-offer-warning-message-content"></p>
                </td>
            </tr>
            
            <tr>
                <th><label for="jo_type"><?php _e('Type', 'job-offer'); ?></label></th>
                <td><?php echo $form->get_type_offer_form($enum); ?></td>
            </tr>
            
            <tr>
                <td><?php echo $form->get_submit_button_form(); ?></td>
            </tr>            
        </table>
        
    </form>
</div>
