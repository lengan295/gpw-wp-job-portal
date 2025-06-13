<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALformfield {
    /*
     * Create the form text field
     */
    static function resumetext($fieldName, $value,$section, $extraattr = array(),$over_limit = 0) {
        $name = $section.'['.$fieldName.']';        
        $textfield = '<input type="text" name="' . $name . '" id="' . $fieldName . '" 
        value="' .  wpjobportalphplib::wpJP_htmlspecialchars($value) . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        if($over_limit == 0){
            $textfield .= ' maxlength="255" ';
        }
        $textfield .= ' />';
        return $textfield;
    }

    static function text($name, $value, $extraattr = array(),$over_limit = 0) {
        $textfield = '<input type="text" name="' . $name . '" id="' . $name . '"
        value="' .  wpjobportalphplib::wpJP_htmlspecialchars($value) . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        if($over_limit == 0){
            $textfield .= ' maxlength="255" ';
        }
        $textfield .= ' />';

        return $textfield;
    }

    static function email($name, $value, $extraattr = array()) {
        $textfield = '<input type="email" name="' . $name . '" id="' . $name . '" 
        value="' .  wpjobportalphplib::wpJP_htmlspecialchars($value) . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }

    /*
     * Create the form password field
     */

    static function password($name, $value, $extraattr = array()) {
        $textfield = '<input type="password" name="' . $name . '" id="' . $name . '" value="' .  wpjobportalphplib::wpJP_htmlspecialchars($value) . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }

    /*
     * Create the form text area
     */

    static function textarea($name, $value, $extraattr = array()) {
        $textarea = '<textarea name="' . $name . '" id="' . $name . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textarea .= ' ' . $key . '="' . $val . '"';
        $textarea .= ' >' . $value . '</textarea>';
        return $textarea;
    }

    /*
     * Create the form hidden field
     */

    static function hidden($name, $value, $extraattr = array(),$id='') {
        $textfield = '';
        if($id == ''){
            $id = $name;
        }
        if(is_array($value)){
            if(wpjobportalphplib::wpJP_strstr($name, '[]')){
                for ($i=0; $i < count($value) ; $i++) { 
                    $textfield .= "<input type='hidden' name='" . $name . "' id='" . $id . "' value='" .  wpjobportalphplib::wpJP_htmlspecialchars($value[$i]) . "' /> ";
                }
                return $textfield;
            }
        }
        $textfield = "<input type='hidden' name='" . $name . "' id='" . $id . "' value='" . sanitize_text_field( wpjobportalphplib::wpJP_htmlspecialchars($value)) . "'" ;
        
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= " " . $key . "='" . $val . "'";
        $textfield .= " />";
        return $textfield;
    }

    /*
     * Create the form submitbutton
     */

    static function submitbutton($name, $value, $extraattr = array()) {
        $textfield = '<input type="submit" name="' . $name . '" id="' . $name . '" value="' .  wpjobportalphplib::wpJP_htmlspecialchars($value) . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }

    /*
     * Create the form button
     */

    static function button($name, $value, $extraattr = array()) {
        $textfield = '<input type="button" name="' . $name . '" id="' . $name . '" value="' .  wpjobportalphplib::wpJP_htmlspecialchars($value) . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }

    static function resumeSelect($fieldName, $list, $defaultvalue,$section,$title = '', $extraattr = array()) {
        $name = $section.'['.$fieldName.']';

        $selectfield = '<select name="' . $name . '" id="' . $fieldName . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val) {
                $selectfield .= ' ' . $key . '="' .  wpjobportalphplib::wpJP_htmlspecialchars($val) . '"';
            }
        $selectfield .= ' >';
        if ($title != '') {
            $selectfield .= '<option value="">' . $title . '</option>';
        }
        if($defaultvalue == ''){
            $defaultvalue = -9999; // B/c '' == 0 in php 
        }
        if (!empty($list))
            foreach ($list AS $record) {
                if ((is_array($defaultvalue) && in_array($record->id, $defaultvalue)) || $defaultvalue == $record->id)
                    $selectfield .= '<option selected="selected" value="' . $record->id . '">' . wpjobportal::wpjobportal_getVariableValue($record->text) . '</option>';
                else
                    $selectfield .= '<option value="' . $record->id . '">' . wpjobportal::wpjobportal_getVariableValue($record->text) . '</option>';
            }

        $selectfield .= '</select>';
        return $selectfield;
    }



    /*
     * Create the form select field
     */

    static function select($name, $list, $defaultvalue, $title = '', $extraattr = array()) {
        $selectfield = '<select name="' . $name . '" id="' . $name . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val) {
                $selectfield .= ' ' . $key . '="' .  wpjobportalphplib::wpJP_htmlspecialchars($val) . '"';
            }
        $selectfield .= ' >';
        if ($title != '') {
            $selectfield .= '<option value="">' . $title . '</option>';
        }
        if($defaultvalue == ''){
            $defaultvalue = -9999; // B/c '' == 0 in php 
        }
        if (!empty($list))
            foreach ($list AS $record) {
                $class=isset($record->class)?$record->class:"";
                if ((is_array($defaultvalue) && in_array($record->id, $defaultvalue)) || $defaultvalue == $record->id)
                    $selectfield .= '<option class="' . $class . '"  selected="selected" value="' . $record->id . '">' . wpjobportal::wpjobportal_getVariableValue($record->text) . '</option>';
                else
                    $selectfield .= '<option class="' . $class . '" value="' . $record->id . '">' . wpjobportal::wpjobportal_getVariableValue($record->text) . '</option>';
            }

        $selectfield .= '</select>';
        return $selectfield;
    }

    /*
     * Create the form radio button
     */

    static function radiobutton($name, $list, $defaultvalue, $extraattr = array()) {
        $radiobutton = '';
        $count = 1;
        foreach ($list AS $value => $label) {
            //for admin forms added field wrapper
            $radiobutton .= '<span class="wpjobportal-form-radio-field" >';
            $radiobutton .= '<input type="radio" name="' . $name . '" id="' . $name . $count . '" value="' .  wpjobportalphplib::wpJP_htmlspecialchars($value) . '"';
            if ($defaultvalue == $value)
                $radiobutton .= ' checked="checked"';

            if (!empty($extraattr))
                foreach ($extraattr AS $key => $val) {
                    $radiobutton .= ' ' . $key . '="' . $val . '"';
                }
            $radiobutton .= '/><label id="for' . $name . '" for="' . $name . $count . '">' . $label . '</label>';
            $radiobutton .= '</span>';
            $count++;
        }
        return $radiobutton;
    }

    /*
     * Create the form checkbox
     */

    static function checkbox($name, $list, $defaultvalue, $extraattr = array()) {
        $checkbox = '';
        $count = 1;
        foreach ($list AS $value => $label) {
            //for admin forms added field wrapper
            $checkbox .= '<span class="wpjobportal-form-chkbox-field" >';
            $checkbox .= '<input type="checkbox" name="' . $name . '" id="' . $name . $count . '" value="' .  wpjobportalphplib::wpJP_htmlspecialchars($value) . '"';
            if ($defaultvalue == $value)
                $checkbox .= ' checked="checked"';
            if (!empty($extraattr))
                foreach ($extraattr AS $key => $val) {
                    $checkbox .= ' ' . $key . '="' . $val . '"';
                }
            $checkbox .= '/><label id="for' . $name . '" for="' . $name . $count . '">' . $label . '</label>';
            $checkbox .= '</span>';
            $count++;
        }
        return $checkbox;
    }

    /*
     * Create the form wp editor
     */

    static function editor($name, $defaultvalue='') {
        $settings = array(
            //'textarea_name' => isset( $field['name'] ) ? $field['name'] : $key,
            'media_buttons' => false,
            'textarea_rows' => 8,
            'quicktags'     => false,
            'tinymce'       => array(
                'plugins'                       => 'lists,paste,tabfocus,wplink,wordpress',
                'paste_as_text'                 => true,
                'paste_auto_cleanup_on_paste'   => true,
                'paste_remove_spans'            => true,
                'paste_remove_styles'           => true,
                'paste_remove_styles_if_webkit' => true,
                'paste_strip_class_attributes'  => true,
                'toolbar1'                      => 'bold,italic,|,bullist,numlist,|,link,unlink,|,undo,redo',
                'toolbar2'                      => '',
                'toolbar3'                      => '',
                'toolbar4'                      => ''
            ),
        );
        ob_start();
        wp_editor( !empty($defaultvalue) ? wp_kses_post($defaultvalue) : '', $name, $settings);
        return ob_get_clean();
    }

}

?>
