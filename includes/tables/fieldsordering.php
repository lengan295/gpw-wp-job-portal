<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALfieldsorderingTable extends WPJOBPORTALtable {

    public $id = '';
    public $field = '';
    public $fieldtitle = '';
    public $placeholder = '';
    public $description = '';
    public $ordering = '';
    public $section = '';
    public $fieldfor = '';
    public $published = '';
    public $isvisitorpublished = '';
    public $sys = '';
    public $cannotunpublish = '';
    public $required = '';
    public $cannotsearch = '';
    public $search_ordering = '';
    public $isuserfield = '';
    public $userfieldtype = '';
    public $userfieldparams = '';
    public $search_user = '';
    public $search_visitor = '';
    public $showonlisting = '';
    public $cannotshowonlisting = '';// it was missing and custom fields were causing issue beacuse of it.
    public $depandant_field = '';
    public $j_script = '';
    public $size = '';
    public $maxlength = '';
    public $cols = '';
    public $rows = '';
    public $readonly = '';
    public $is_section_headline = '';// to handle resume section titles or exisiting and custom sections
    // field visble feature
    public $visible_field = '';
    public $visibleparams = '';


    function __construct() {
        parent::__construct('fieldsordering', 'id'); // tablename, primarykey
    }

}

?>
