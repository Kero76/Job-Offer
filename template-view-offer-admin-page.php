<?php
    $offers = $this->get_offers();
?>

<div class="wrap">
    <h2><?php _e('Job Offer', 'job-offer'); ?></h2>
</div>

<div>
    <h3 class="job-offer-title"><?php _e('Views all offers', 'job-offer'); ?></h3>
    <a href="?page=job-offer/job-offer.php&p=insert"><button class="button button-primary" ><?php _e('Add new offer', 'job-offer'); ?></button></a>
    <table id="view-table" class="form-table">
        <tr>
            <th><?php _e('Id of offer', 'job-offer'); ?></th>
            <th><?php _e('Type of offer', 'job-offer'); ?></th>
            <th><?php _e('Title of offer', 'job-offer'); ?></th>
            <th><?php _e('Update offer', 'job-offer'); ?></th>
            <th><?php _e('Delete offer', 'job-offer'); ?></th>
            <th><?php _e('Shortcode', 'job-offer'); ?></th>
        </tr>
        <?php foreach($offers as $offer) {
            echo '<tr>';
            echo '<td>' . $offer->get_id() . '</td>';
            echo '<td class="' . str_replace(' ', '_', strtolower($offer->get_type()->get_key())) . '">' . $offer->get_type()->get_key()  . '</td>';
            echo '<td>' . $offer->get_title() . '</td>';
            echo '<td><a href="?page=job-offer/job-offer.php&p=update&amp;id=' . $offer->get_id() . '"><button class="button button-primary">' . __('Update', 'job-offer')  . '</button></a></td>';
            echo '<td><a href="?page=job-offer/job-offer.php&action=deleteoffer&amp;id=' . $offer->get_id() . '"><button class="button button-primary">' . __('Delete', 'job-offer') . '</button></a></td>';
            echo '<td><input type="text" name="jo_shortcode" id="jo_shortcode_' . $offer->get_id() . '" value="[jo_job id=&quot;' . $offer->get_id() . '&quot; ]" /></td>';
            echo '</tr>';
        }
        ?>
    </table>
</div>


