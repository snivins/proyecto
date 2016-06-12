<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Flashdata
{
    public function load($mensajes_externo) {
        $CI =& get_instance();
        $mensajes = $CI->session->flashdata('mensajes');
        $mensajes = isset($mensajes) ? $mensajes : $mensajes_externo;

        $CI->session->set_flashdata("mensajes", $mensajes);
    }
}
