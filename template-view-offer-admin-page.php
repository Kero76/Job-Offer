<?php
    $offers = $this->getOffers();
?>

<div class="wrap">
    <h2>Job Offer</h2>
</div>

<div>
    <h3 class="job-offer-title">Views all offers.</h3>
    <a href="?page=job-offer/job-offer.php&p=insert"><button class="button button-primary" >Add new offer</button></a>
    <table class="form-table">
        <tr>
            <th>Id of offer</th>
            <th>Type of offer</th>
            <th>Title of offer</th>
            <th>Update offer</th>
            <th>Delete offer</th>
            <th>Shortcode</th>
        </tr>
        <?php foreach($offers as $offer) {
            echo '<tr>';
            echo '<td>' . $offer->getId() . '</td>';
            echo '<td class="' . str_replace(' ', '_', strtolower($offer->getType()->getKey()) . '">' . $offer->getType()->getKey())  . '</td>';
            echo '<td>' . $offer->getTitle() . '</td>';
            echo '<td><a href="?page=job-offer/job-offer.php&p=update&amp;id=' . $offer->getID() . '"><button class="button button-primary">Update</button></a></td>';
            echo '<td><a href="?page=job-offer/job-offer.php&action=deleteoffer&amp;id=' . $offer->getID() . '"><button class="button button-primary">Delete</button></a></td>';
            echo '<td><input type="text" name="jo_shortcode" id="jo_shortcode" value="[jo_job id=&quot;' . $offer->getId() . '&quot; ]" /></td>';
            echo '</tr>';
        }
        ?>
    </table>
</div>


