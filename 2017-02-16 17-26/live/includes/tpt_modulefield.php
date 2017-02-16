<?php

defined('TPT_INIT') or die('access denied');

class tpt_ModuleField {
    
    var $fieldName; //mysql table field name
    var $fieldType; // (S)tring,(I)nteger,(B)oolean,(F)loat,I(n)dex,(O)bject,(A)rray,Fi(l)e
    var $fieldLength; // can be null (default)
    /* Control types ($controlType):
     *    String
     *       - ta - textarea
     *       - tf - text input field
     *       - s - select of newline separated values
     *       - ms - multi-select of newline separated values
     *       - r - radio button group of newline separated values
     *       - cb - checkbox group of newline separated values
     *    Integer
     *       - tf - text input field
     *       - c - calendar returning timestamp
     *    Float
     *       - tf - text input field
     *    Index
     *       -- NONE --
     *    Boolean
     *       - cb - 1/0 checkbox
     *    Object/Array
     *       - b - related table branch
     *    File
     *       - fp - file upload input (stores file path on server)
     *       - fu - text input file url 
     *       - id - specify image dialog 
     *    -- ALL --
     *       - sp - field won't be editable through admin
     */
    var $controlType;
    var $controlAttr; //<$type $parentTagOuterHTMLattribsString></$type>
    /* Options:
     *    String (can be combined using comma separation - order matters)
     *       -- NONE --
     *    Integer
     *       -- NONE --
     *    Float
     *       -- NONE --
     *    Index (PRIMARY KEY)
     *       - ai - autoincrement (AUTOINCREMENTED INTEGER, NOT NULL, UNSIGNED)
     *       - vc - varchar
     *    Boolean
     *       -- NONE --
     *    Object/Array
     *       - rt - related table
     *       - je - json encoded string
     *       - sr - serialized string
     *    File
     *       - si - show image in admin
     *    -- ALL --
     */
    var $options; //type options
    /* Storage Options:
     *    String (can be combined using comma separation - order matters)
     *       - strip_tags - strip tags
     *       - html_entities - html entities
     *       - nl2br - nl2br
     *       - trim - trim
     *       - ltrim - ltrim
     *       - rtrim - rtrim
     *       - itval10 - intval(arg, 10)
     *    Integer
     *       -- NONE --
     *    Float
     *       -- NONE --
     *    Index (PRIMARY KEY)
     *       -- NONE --
     *    Boolean
     *       -- NONE --
     *    Object/Array
     *       -- NONE --
     *    File
     *       -- NONE --
     *    -- ALL --
     */
    public $storageOptions; // storage options
	public $dflt; //default value
	public $label; //template display label
	public $template; //order of the label and control in the template html: LC label is before control, CL control is before label
	public $index_data; //load an associative array of all data table rows for this module which will have index keys provided by the value of the corresonding field in the table
	public $split_keys; //
	public $queryRules; //

    

    function __construct($fieldName, $fieldType, $fieldLength=null, $options='', $storageOptions='', $controlType='sp', $controlAttr='', $default=null, $label='', $index_data=false, $split_keys=false, $template='LC', $queryRules='' ) {
		/*
		if($fieldName == 'pname') {
			ob_start();
			debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
			$dpb = ob_get_clean();
			tpt_dump($dpb, true);
			tpt_dump($queryRules, true);
		}
		*/
        $this->fieldName = $fieldName;
        $this->fieldType = $fieldType;
        $this->fieldLength = $fieldLength;
        $this->options = $options;
        $this->storageOptions = $storageOptions;
        $this->controlType = $controlType;
        $this->controlAttr = $controlAttr;
        $this->dflt = $default;
        $this->label = $label;
        $this->template = $template;
        $this->index_data = $index_data; //load an associative array of all data table rows for this module which will have index keys provided by the value of the corresonding field in the table
        $this->split_keys = $split_keys; //
        $this->queryRules = $queryRules; //
    }
    
    function get_control(&$vars, $moduleTable, $index, $value) {
        $html = '';
        $html .= '<div class="moduleControlWrapper float-left">';
        $fieldControl = '';
        switch(strtolower($this->controlType)) {
            case 'tf' :
            //if($this->fieldName == 'HEX') {
                //var_dump($this->controlAttr);
                //var_dump($this);die();
            //}
                //$fieldControl = $this->controlAttr;
                $fieldControl = '<input autocomplete="off" type="text" name="tpt_modules['.$moduleTable.']['.$index.']['.$this->fieldName.']" value="'.htmlentities($value).'" '.$this->controlAttr.' />';
            break;
        }
        $fieldLabel = '<span class="moduleFieldLabel">'.$this->label.'</span>';
        if($this->template == 'CL') {
            $html .= $fieldControl."\n".$fieldLabel;
        } else {
            $html .= $fieldLabel."\n".$fieldControl;
        }
        $html .= '</div>';
        
        return $html;
    }

}
