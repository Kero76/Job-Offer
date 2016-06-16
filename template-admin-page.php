<?php    
    $enum = array(
      "CDI", "CDD", "Stage",   
    );    
?>
<div class="wrap">
    <h2>Job Offer</h2>
</div>

<div>
    <h3 class="job-offer-title">Added a new Job Offer.</h3>
    <form action="" method="post">
        <table class="form-table">
            <tr>
                <th><label for="title">Title</label></th>
                <td><input type="text" name="title" id="title" size="100"/></td>
            </tr>
            
            <tr>
                <th><label for="content">Content</label></th>
                <td><textarea name="content" id="content" rows="15" cols="100"></textarea></td>
            </tr>
            
            <tr>
                <th><label for="type">Type</label></th>
                <td>
                    <select name="type" id="type" />
                    <?php
                        $str = '';
                        for ($i = 0; $i < count($enum); $i++) {
                            $str .= '<option value="' . $i . '">' . $enum[$i] . '</option>';
                        }
                        echo $str;
                    ?>
                    </select>
                </td>
            </tr>
            
            <tr>
                <td><input class="button button-primary" type="submit" name="submit" id="submit" value="Save the offer" /></td>
            </tr>            
        </table>
        
    </form>
</div>
