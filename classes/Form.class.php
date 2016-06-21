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
    public function get_title_form($content = '') {
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
    public function get_content_form($rows = 15, $cols = 100, $content = '') {        
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
     * @param object $type
     *  $type is an EnumType object.
     * If $type === null, then it significate we added new offer,
     * else, significate we modified an offer and we recover the good type from Enum.
     * @return string
     *  The html field for an select type.
     */
    public function get_type_offer_form(Enum $enum, EnumType $type = null) {
        if ($type !== null)
            $id = $enum->get_id_by_key($type->get_key());
        $str = '<select name="jo_type" id="jo_type" />';
        for ($i = 0; $i < count($enum->get_enum()); $i++) {
            if ($id == $i) {
                $str .= '<option value="' . $i . '" selected="selected">' . $enum->get_enum()[$i]->get_key() . '</option>';
            } else {
                $str .= '<option value="' . $i . '">' . $enum->get_enum()[$i]->get_key() . '</option>';
            }
            $str .= "\n";
        }
        $str .= '</select>';
        return $str;
    }
    
    /**
     * This function return a string who represent an input type submit html field.
     * 
     * @access public
     * @return string
     *  The html field for an input submit type.
     */
    public function get_submit_button_form() {
        return '<input class="button button-primary" type="submit" name="jo_submit" id="jo_submit" value="Save the Offer" />';
    }
    
    /**
     * This function return a string <ho represent an input type hidden htmk field.
     * It used only on update form because when update, it's necessary
     * to spent the id for modified the good entry on Database.
     * 
     * @access public
     * @param integer $id
     *  The hidden id value.
     */
    public function get_hidden_id_button_form($id) {
        return '<input type="hidden" name="jo_id" id="jo_id" value="' . $id . '" />';
    }
}
