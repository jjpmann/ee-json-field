<?php

class Json_Field_ft extends EE_Fieldtype
{

    /**
    * Info array
    *
    * @var  array
    */
    public $info = array(
        'name'      => 'JSON Field',
        'version'   => '0.1'
    );

    /**
     * Replace tag
     *
     * @access  public
     * @param   field contents
     * @return  replacement text
     *
     */
    public function replace_tag($data, $params = array(), $tagdata = false)
    {
        return $data;
    }

    /**
     * Replace pretty 
     *
     * @access  public
     * @param   field contents
     * @return  replacement text
     *
     */
    public function replace_pretty($data, $params = array(), $tagdata = false)
    {
        return $data;
    }

    /**
    * Displays the field in publish form
    *
    * @param    string
    * @param    bool
    * @return   string
    */
    public function display_field($data, $cell = false)
    {

        $this->insertJS();

        $textarea = form_textarea(array(
            'class' => 'source',
            'name'  => $this->field_name,
            'id'    => $this->field_id,
            'value' => $data
        ));

        $klass = '';
        if ($this->settings['reformat']) {
             $klass .= ' reformat';
        }

        
        if ($this->settings['friendly']) {
             $klass .= ' friendly';
        }

        $html = '<div class="json-field '.$klass.'">'.$textarea.'<pre class="result"></pre></div>';

        return $html;
    }


    /**
    * Displays the field in matrix
    *
    * @param    string
    * @return   string
    */
    public function display_cell($cell_data)
    {
        return $this->display_field($cell_data, true);
    }

    
    /**
    * Displays the field in Low Variables
    *
    * @param    string
    * @return   string
    */
    public function display_var_field($var_data)
    {
        return $this->display_field($var_data);
    }

    /**
     * Display Global Settings
     *
     * @access  public
     * @return  form contents
     *
     */
    public function display_global_settings()
    {
        
        $val = array_merge($this->settings, $_POST);

        // load the language file
        ee()->lang->loadfile('json', 'json_field');

        // load the table lib
        ee()->load->library('table');

        $options = array('off', 'on');

        // use the default template known as
        // $cp_pad_table_template in the views
        ee()->table->set_template(array(
            'table_open'    => '<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">',
            'row_start'     => '<tr class="even">',
            'row_alt_start' => '<tr class="odd">'
        ));

        // "Preference" and "Setting" table headings
        ee()->table->set_heading(array('data' => lang('preference'), 'style' => 'width: 50%'), lang('setting'));

        ee()->table->add_row(
            lang('preview', 'preview'),
            form_dropdown('preview', $options, $this->settings['preview'], 'id="preview"')
        );

        ee()->table->add_row(
            lang('friendly', 'friendly').'<br/>'.lang('friendly_desc'),
            form_dropdown('friendly', $options, $this->settings['friendly'], 'id="friendly"')
        );
        
        ee()->table->add_row(
            lang('reformat', 'reformat').'<br/>'.lang('reformat_desc'),
            form_dropdown('reformat', $options, $this->settings['reformat'], 'id="reformat"')
        );

        return ee()->table->generate();

    }

    /**
     * Save Global Settings
     *
     * @access  public
     * @return  global settings
     *
     */
    public function save_global_settings()
    {
        return array_merge($this->settings, $_POST);
    }

    /**
     * Display Settings Screen
     *
     * @access  public
     * @return  default global settings
     *
     */
    public function display_settings($data)
    {
        ee()->lang->loadfile('json', 'json_field');

        $options = array('off', 'on');

        $preview   = isset($data['preview']) ? $data['preview'] : $this->settings['preview'];
        $friendly  = isset($data['friendly']) ? $data['friendly'] : $this->settings['friendly'];
        $reformat  = isset($data['reformat']) ? $data['reformat'] : $this->settings['reformat'];


        ee()->table->set_template(array(
            'table_open'    => '<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">',
            'row_start'     => '<tr class="even">',
            'row_alt_start' => '<tr class="odd">'
        ));

        // "Preference" and "Setting" table headings
        ee()->table->set_heading(array('data' => lang('preference'), 'style' => 'width: 50%'), lang('setting'));

        ee()->table->add_row(
            lang('preview', 'preview'),
            form_dropdown('preview', $options, $preview, 'id="preview"')
        );

        ee()->table->add_row(
            lang('friendly', 'friendly').'<br/>'.lang('friendly_desc'),
            form_dropdown('friendly', $options, $friendly, 'id="friendly"')
        );
        
        ee()->table->add_row(
            lang('reformat', 'reformat').'<br/>'.lang('reformat_desc'),
            form_dropdown('reformat', $options, $reformat, 'id="reformat"')
        );

    }

    /**
     * Save Settings
     *
     * @access  public
     * @return  field settings
     *
     */
    public function save_settings($data)
    {
        return array(
            'preview'  => ee()->input->post('preview'),
            'friendly' => ee()->input->post('friendly'),
            'reformat' => ee()->input->post('reformat')
        );
    }

    /**
     * Install Fieldtype
     *
     * @access  public
     * @return  default global settings
     *
     */
    public function install()
    {
        return array(
            'preview' => 'off',
            'friendly' => 'off',
            'reformat' => 'off'
        );
    }

    /**
     * Control Panel Javascript
     *
     * @access  public
     * @return  void
     *
     */
    private function insertJS()
    {
        // This js is used on the global and regular settings
        // pages, but on the global screen the map takes up almost
        // the entire screen. So scroll wheel zooming becomes a hindrance.
        
        //ee()->javascript->set_global('gmaps.scroll', ($_GET['C'] == 'content_admin'));
        ee()->cp->load_package_css('style');
        ee()->cp->load_package_js('json2');
        ee()->cp->load_package_js('jsonlint');
        ee()->cp->load_package_js('cp');


    }
}
