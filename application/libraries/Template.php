<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Template
{
    public $data_template = array();
    public function set($clave, $valor)
    {
        $this->data_template[$clave] = $valor;
    }
    public function load($vista, $data = array(), $data_template = array(),
                         $template = 'template')
    {
        $CI =& get_instance();
        $this->data_template['contents'] = $CI->load->view($vista, $data, TRUE);
        $this->data_template = array_merge($this->data_template,
                                           $data_template);
        $CI->load->view($template, $this->data_template);
    }
}
