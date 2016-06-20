<?php
    require_once('classes/Form.class.php');
    require_once('classes/Enum.class.php');
    require_once('classes/Offer.class.php');
    
    $form = new Form();
    $enum = new Enum();
    
    if (!isset($_GET['id]'])) 
        $offer = $this->getOffer($_GET['id']);

    /* Options for textarea */
    $rows = 15;
    $cols = 100;
?>
<div class="wrap">
    <h2>Job Offer</h2>
</div>

<div>
    <h3 class="job-offer-title">Update an Offer.</h3>
    <form action="?page=job-offer/job-offer.php&action=updateoffer" method="post">
        <table class="form-table">
            <tr>
                <th><label for="jo_title">Title</label></th>
                <td><?php echo $form->getTitleForm($offer->getTitle()); ?></td>
            </tr>
            
            <tr>
                <th><label for="jo_content">Content</label></th>
                <td><?php echo $form->getContentForm($rows, $cols, $offer->getContent()); ?></td>
            </tr>
            
            <tr>
                <th><label for="jo_type">Type</label></th>
                <td><?php echo $form->getTypeOfferForm($enum, $offer->getType()); ?></td>
            </tr>
            
            <tr>
                <td><?php echo $form->getHiddenIdButton($offer->getId()); ?></td>
                <td><?php echo $form->getSubmitButton(); ?></td>
            </tr>            
        </table>
        
    </form>
</div>


