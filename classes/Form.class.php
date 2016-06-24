<?php

/**
 * This class represent form field using on admin page.
 * All of these methods return an elements presents on admin page.
 * This object is create for centralize all form fields.
 * 
 * @since Job Offer 1.0.0
 * @version 1.0.0
 */
class Form {
    
    /**
     * Return title form field.
     * 
     * This method return a string who represent an input type text field.
     * 
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
            return '<input type="text" name="jo_title" id="jo_title" size="100" value="' . stripslashes($content) . '"/>';
        }
    }
    
    /**
     * Return content form field.
     * 
     * This method return a string who represents either TinyMCE editor, or textarea field.
     * It return a TinyMCE editor if it enable, otherwise return a textarea field. 
     * 
     * @param integer $rows
     *  Number of line for the field
     * @param integer $cols
     *  Number of cols for the field.
     * @param string $content
     *  If this parameter is empty, you create a empty field, 
     * and if this parameter is not empty, it fill with data about offer.
     * @return string
     *  A TinyMCE editor or an textarea field.
     */
    public function get_content_form($rows = 15, $cols = 100, $content = '') {
        if (function_exists('wp_editor')) {
            if ($content == '') {
                return wp_editor('', 'jo_content');
            } else {
                return wp_editor(stripslashes($content), 'jo_content');
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
     * Return set of all EnumType present in Enum object.
     * 
     * This method return a string who represent a select html field.
     * For display the list, this method have a enum parameter.
     * This parameter represent the list who display on page.
     * The second argument represent the element select outcome of the database.
     * 
     * @param Enum $enum
     *  Enumerator containing all elements to display.
     * @param object $type
     *  $type is an EnumType object.
     * If $type is null, then it add new Offer and the default value is the first entry from enum.
     * Otherwise, it significate we modified an offer and we recover the good type from Enum.
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
     * Return submit button form field.
     * 
     * This method return a string who represent an input type submit html field.
     * 
     * @return string
     *  The html field for an input submit type.
     */
    public function get_submit_button_form() {
        return '<input class="button button-primary" type="submit" name="jo_submit" id="jo_submit" value="' . __('Save the Offer', 'job-offer') . '" />';
    }
    
    /**
     * Return a hidden form field.
     * 
     * This method return a string who represent an input type hidden field.
     * It used only on update form because when update, it's necessary
     * to send the id for modified the good entry on Database.
     * 
     * @param integer $id
     *  The hidden id value.
     */
    public function get_hidden_id_button_form($id) {
        return '<input type="hidden" name="jo_id" id="jo_id" value="' . $id . '" />';
    }
}
