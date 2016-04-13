<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function template_set($clave, $valor)
{
    $CI =& get_instance();
    $CI->template->set($clave, $valor);
}
