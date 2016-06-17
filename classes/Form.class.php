<?php

/**
 *
 * @author Nicolas GILLE
 */
class Form {
    
    /**
     * This function return a string who represent an input type text field.
     * 
     * @access public
     * @param string $content
     *  If this parameter is empty, you create a empty field, 
     * and if this parameter is not empty, it fill with data about offer.
     * @return string
     *  The html field for an input text type.
     */
    public function getTitleForm($content = '') {
        if ($content == '') {
            return '<input type="text" name="jo_title" id="jo_title" size="100"/>';
        } else {
            return '<input type="text" name="jo_title" id="jo_title" size="100" value="' . $content . '"/>';
        }
    }
    
    /**
     * This function return a string who represents either an TinyMCE editor, or an textarea field.
     * 
     * @access public
     * @param integer $rows
     *  Number of line for the field
     * @param integer $cols
     *  Number of cols for the field.
     * @param string $content
     *  If this parameter is empty, you create a empty field, 
     * and if this parameter is not empty, it fill with data about offer.
     * @return string
     *  An TinyMCE editor or an textarea field.
     */
    public function getContentForm($rows = 15, $cols = 100, $content = '') {        
        if (function_exists('wp_editor')) {
            if ($content == '') {
                return wp_editor('', 'jo_content');
            } else {
                return wp_editor($content, 'jo_content');
            }
        } else {
            if ($content == '') {
                return '<textarea  name="jo_content" id="jo_content" rows="' . $rows . '" cols="' . $cols . '">' . $content . '</textarea>';
            } else {
                return '<textarea  name="jo_content" id="jo_content" rows="' . $rows . '" cols="' . $cols . '"></textarea>';
            }
        }
    }
    
    /**
     * This function return a string who represent a select html field.
     * For display the list, this function have a enum parameter.
     * This parameter represent the list who display on page.
     * The second argument represent the element select outcome of the database.
     * 
     * @access public
     * @param Enum $enum
     *  Enumerator containing all elements to display.
     * @param integer $id
     *  Id for added 'selected' attributes on option field.
     *  If this value is '-1', no enumerator element was selected.
     * @return string
     *  The html field for an select type.
     */
    public function getTypeOfferForm(Enum $enum, $id = '-1') {        
        $str = '<select name="jo_type" id="jo_type" />';
        for ($i = 0; $i < count($enum->getEnum()); $i++) {
            if ($id == $i) {
                $str .= '<option value="' . $i . '" selected="selected">' . $enum->getEnum()[$i]->getKey() . '</option>';
            } else {
                $str .= '<option value="' . $i . '">' . $enum->getEnum()[$i]->getKey() . '</option>';
            }
            $str .= "\n";
        }
        $str .= '</select>';
        return $str;
    }
    
    /**
     * This function return a string wh who represent an input type submit html field.
     * 
     * @access public
     * @return string
     *  The html field for an input submit type.
     */
    public function getSubmitButton() {
        return '<input class="button button-primary" type="submit" name="jo_submit" id="jo_submit" value="Save the Offer" />';
    }
}
