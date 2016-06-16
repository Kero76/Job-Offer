<?php

namespace JobOffer\classes\form;

use JobOffer\classes\enum\Enum;

/**
 *
 * @author Nicolas GILLE
 */
class Form {
    
    /**
     * This function return a string wh represeting an input type text html marker.
     * @access public
     * @return string
     *  The html marker for an input text type.
     */
    public function getTitleForm() {
        return '<input type="text" name="title" id="title" size="100"/>';
    }
    
    /**
     * This function return a string wh represeting a textarea html marker.
     * @access public
     * @return string
     *  The html marker for an textarea type.
     */
    public function getContentForm($rows = 15, $cols = 100) {        
        return '<textarea  name="content" id="content" rows="' . $rows . '" cols="' . $cols . '"></textarea>';
    }
    
    /**
     * This function return a string wh represeting a select html marker.
     * @access public
     * @return string
     *  The html marker for an select type.
     */
    public function getTypeOfferForm(Enum $enum) {
        $str = '<select name="type" id="type" />';
        for ($i = 0; $i < count($enum); $i++) {
            $str .= '<option value="' . $i . '">' . $enum->getEnum()[$i]->getKey() . '</option>';
        }
        $str .= '</select>';
        return $str;
    }
    
    /**
     * This function return a string wh represeting an input type submit html marker.
     * @access public
     * @return string
     *  The html marker for an input submit type.
     */
    public function getSubmitButton() {
        return '<input class="button button-primary" type="submit" name="submit" id="submit" value="Save" />';
    }
}
