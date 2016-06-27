<?php

/**
 * This class sanitize string for clear string from all potentials specials characters.
 * 
 * Indeed, when the plugin create a new post after an offer, the name of the post
 * can contains accentuated characters or special character like quote, double quote, ...
 * So, for avoid it in name post, using arrays with a char who replace on key
 * and a replaced char on value.
 * 
 * @since Job Offer 1.1.0
 * @version 1.0.0
 */
class Sanitizer {
    
    /**
     * This string is the string at sanitize.
     * 
     * @access private
     * @var string 
     *  String at sanitize.
     */
    private $_string;
    
    /**
     * An array with all specials characters at replace into $_string.
     * @var array
     *  An array with all characters at replaced in post name. 
     */
    private $_sanitize_char_post_name;
    
    /**
     * Constructor of the class with 1 parameter.
     * 
     * @param string $str
     *  String at sanitize.
     */
    public function __construct($str) {
        $this->set_string($str);
        $this->_init_sanitize_char_post_name();
    }
    
    /**
     * Return the string at sanitizy
     * 
     * @return string
     *  Return the string who client at sanitizy.
     */
    public function get_string() {
        return $this->_string;
    }
    
    /**
     * Set current string to the new string.
     * 
     * Verify at the first time if the string passed on parameter is instance of string.
     * If it's an instance of string, replace current string by new string,
     * otherwise, don't modify current string.
     * @param type $str
     */
    public function set_string($str) {
        if (is_string($str)) {
            $this->_string = $str;
        }
    }
    
    /**
     * Sanitize string for generate a valid post name.
     * Loop in all characters present in array and replace it into string.
     * Before loop, it transform the string into lowercase for work with lowercase char.
     */
    public function sanitize_post_name() {
        foreach ($this->_sanitize_char_post_name as $key => $value) {
            $this->set_string(str_replace($key, $value, $this->_string));
        }
        $this->set_string(strtolower($this->_string));
    }
    
    /**
     * This method initialize _sanitize_char_post_name attribute with all special characters.
     * 
     * @access private
     */
    private function _init_sanitize_char_post_name() {
        $this->_sanitize_char_post_name = array(
            ' '  => '-',
            
            // Char : A //
            'à'  => 'a',
            'á'  => 'a',
            'â'  => 'a',
            'ä'  => 'a',
            'ã'  => 'a',
            
            // Char : E //
            'è'  => 'e',
            'é'  => 'e',
            'ê'  => 'e',
            'ë'  => 'e',
            
            // Char : I //
            'ì'  => 'i',
            'í'  => 'i',
            'î'  => 'i',
            'ï'  => 'i',
            
            // Char : O //
            'ò'  => 'o',
            'ó'  => 'o',
            'ô'  => 'o',
            'õ'  => 'o',
            'ö'  => 'o',
            
            // Char : U //
            'ù'  => 'u',
            'ú'  => 'u',
            'û'  => 'u',
            'ü'  => 'u',
            
            // Char : Y/N/C //
            'ý'  => 'y',
            'ÿ'  => 'y',
            'ñ'  => 'n',
            'ç'  => 'c',
            
            // Special Char //
            '/'  => '',
            '\\' => '',
            '\'' => '',
            '"'  => '',
            '{'  => '',
            '}'  => '',
            '|'  => '',
            '`'  => '',
            '%'  => '',
            '<'  => '',
            '>'  => '',
            ','  => '',
            ';'  => '',
            ':'  => '',
            '!'  => '',
            '?'  => '',
            '#'  => '',
        );
    }
}