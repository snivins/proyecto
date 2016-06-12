<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {

      private $reglas_comunes = array(
          array(
              'field' => 'nick',
              'label' => 'Nick',
              'rules' => 'trim|required|max_length[15]'
          ),
          array(
              'field' => 'email',
              'label' => 'Email',
              'rules' => 'trim|required'
          ),
          array(
              'field' => 'password',
              'label' => 'Contraseña',
              'rules' => 'trim|required'
          ),
          array(
              'field' => 'password_confirm',
              'label' => 'Confirmar contraseña',
              'rules' => 'trim|required|matches[password]'
          )
      );
      private $array_password_anterior = array(
          'field' => 'password_anterior',
          'label' => 'Contraseña Antigua',
          'rules' => 'required'
      );
  public function registro(){
		$this->template->load('registro');
  }
  public function index(){
    redirect('usuarios/cuenta');
  }

  public function cuenta(){
		$this->template->load('cuenta');
  }
  public function login() {
        if ($this->Usuario->logueado()) {
            redirect('juegos');
        }
        if ($this->input->post('login') !== NULL)
        {
            $nick = $this->input->post('nick');
            $reglas = array(
                array(
                    'field' => 'nick',
                    'label' => 'Nick',
                    'rules' => array(
                        'trim', 'required',
                    ),
                    'errors' => array(
                        'existe_nick' => 'El nick debe existir.',
                        'existe_nick_registrado' => 'Esta cuenta todavia no ha sido validada por' .
                                                    ' los medios correspondientes. Por favor, ' .
                                                    'valide su cuenta.'
                    ),
                ),
                array(
                    'field' => 'password',
                    'label' => 'Contraseña',
                    'rules' => "trim|required|callback__password_valido[$nick]"
                )
            );
            $this->form_validation->set_rules($reglas);
            if ($this->form_validation->run() === TRUE)
            {
                $usuario = $this->Usuario->por_nick($nick);
                $this->session->set_userdata('usuario', array(
                    'id' => $usuario['id'],
                    'nick' => $nick,
                    'rol_id' => $usuario['rol_id']
                ));
                if($this->session->has_userdata('last_uri'))
                {
                    $uri = $this->session->userdata('last_uri');
                    $this->session->unset_userdata('last_uri');
                    redirect($uri);
                }
                else
                {
                    redirect('juegos');
                }
            }
        }
        if (isset($_SERVER['HTTP_REFERER']) && !$this->session->has_userdata('last_uri'))
        {
            $this->session->set_userdata('last_uri',
                            parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH));
        }
        $this->output->delete_cache('juegos');
        $this->template->load('cuenta');
    }
    public function logout() {
        $this->output->delete_cache('juegos');
        $this->session->sess_destroy();
        redirect('usuarios/cuenta');
    }
  public function registrar(){
      if ($this->input->post('registrar') !== NULL)
    {
      $reglas = $this->reglas_comunes;
      $reglas[0] = array(
                      'field' => 'nick',
                      'label' => 'Nick',
                      'rules' => array(
                          'trim', 'required',
                          array('existe_nick', function ($nick) {
                                  return !$this->Usuario->existe_nick($nick);
                              }
                          )
                      ),
                      'errors' => array(
                          'existe_nick' => 'El nick ya existe, por favor, escoja otro.',
                      )
                  );
      $reglas[1] = array(
          'field' => 'email',
          'label' => 'Email',
          'rules' => array(
              'trim', 'required',
              array('existe_email', function ($email) {
                      return !$this->Usuario->existe_email($email);
                  }
              )
          ),
          'errors' => array(
              'existe_email' => 'El email ya existe, por favor, escoja otro.',
          )
      );
      $this->form_validation->set_rules($reglas);

      if ($this->form_validation->run() === TRUE) {

                  $valores = $this->input->post();

                  unset($valores['registrar']);
                  unset($valores['password_confirm']);
                  $valores['password'] = password_hash($valores['password'], PASSWORD_DEFAULT);

                  $this->Usuario->insertar($valores);

                                  $mensajes[] = array('info' =>
                                          "Cuenta creada correctamente.");
                                  $this->flashdata->load($mensajes);
                                  redirect('usuarios/cuenta');

      } else {
            $this->template->load('registro');
      }
    }
  }
  public function _password_valido($password, $nick) {
    $usuario = $this->Usuario->por_nick($nick);
    if ($usuario !== FALSE &&
        password_verify($password, $usuario['password']) === TRUE)
    {
        return TRUE;
    }
    else
    {
        $this->form_validation->set_message('_password_valido',
            'La {field} no es válida.');
        return FALSE;
    }
  }
}
