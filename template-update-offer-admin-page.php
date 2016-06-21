<?php
    require_once('classes/Form.class.php');
    require_once('classes/Enum.class.php');
    require_once('classes/Offer.class.php');
    
    $form = new Form();
    $enum = new Enum();
    
    if (!isset($_GET['id]'])) 
        $offer = $this->get_offer($_GET['id']);

    /* Options for textarea */
    $rows = 15;
    $cols = 100;
?>
<div class="wrap">
    <h2><?php _e('Job Offer', 'job-offer'); ?></h2>
</div>

<div>
    <h3 class="job-offer-title"><?php _e('Update an Offer', 'job-offer'); ?></h3>
    <form action="?page=job-offer/job-offer.php&action=updateoffer" method="post">
        <table class="form-table">
            <tr>
                <th><label for="jo_title"><?php _e('Title', 'job-offer'); ?></label></th>
                <td><?php echo $form->get_title_form($offer->get_title()); ?></td>
            </tr>
            
            <tr>
                <th><label for="jo_content"><?php _e('Content', 'job-offer'); ?></label></th>
                <td><?php echo $form->get_content_form($rows, $cols, $offer->get_content()); ?></td>
            </tr>
            
            <tr>
                <th><label for="jo_type"><?php _e('Type', 'job-offer'); ?></label></th>
                <td><?php echo $form->get_type_offer_form($enum, $offer->get_type()); ?></td>
            </tr>
            
            <tr>
                <td><?php echo $form->get_hidden_id_button_form($offer->get_id()); ?></td>
                <td><?php echo $form->get_submit_button_form(); ?></td>
            </tr>            
        </table>
        
    </form>
</div>


