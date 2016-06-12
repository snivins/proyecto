<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function error_array()
{
    $CI =& get_instance();
    return $CI->form_validation->error_array();
}
