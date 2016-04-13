<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chats extends CI_Controller {

	public function index()
  {
		$msj = $this->Chat->get_mensajes();
		$data['msj'] = array_reverse($msj);

	  // $this->load->view('chat', $data);
		$this->template->load('chat', $data);
  }

	public function ver_mensajes()
	{
			$msj = $this->Chat->get_mensajes();
			$msn = json_encode(array_reverse($msj));
			echo ($msn);
	}

	public function escribir_mensajes($mensaje, $usuario = 'anonimo')
	{
		$valores = array(
			'usuario' => urldecode($usuario),
			'contenido' => urldecode($mensaje)
		);
		$this->Chat->insertar_mensaje($valores);
	}

}
