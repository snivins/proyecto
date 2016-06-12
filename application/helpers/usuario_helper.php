<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function login()
{
    $CI =& get_instance();

    $out = "";

    if ($CI->Usuario->logueado()):
        $usuario = $CI->session->userdata('usuario');
        $out .= form_open('usuarios/logout', 'class="form-inline"');
            $out .= '<div class="form-group">';
                $out .= anchor('/usuarios/perfil/' . $usuario['id'], $usuario['nick'], '');
                $out .= form_submit('logout', 'Logout',
                                    'id="logout" class="btn btn-custom btn-xs"');
            $out .= '</div>';
        $out .= form_close();
    else:
        $out .= '<div class="row">';
          $out .= '<div class="col-md-2">';
                $out .= anchor('/usuarios/login', 'Iniciar sesión',
                                'class="btn btn-custom btn-xs" role="button"');
          $out .= '</div>';
        $out .= '</div>';
    endif;

    return $out;
}

function usuario_id()
{
        $CI =& get_instance();
        return $CI->session->userdata('usuario')['id'];
}

function logueado()
{
    $CI =& get_instance();
    return $CI->Usuario->logueado();
}

function nick($usuario_id)
{
    $CI =& get_instance();
    $usuario =  $CI->Usuario->por_id($usuario_id);
    if ($usuario !== FALSE)
    {
        return $usuario['nick'];
    }
}
