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
		$id_partida = $this->Juego->get_id_partida($this->session->userdata('usuario')['id'])['id_partida'];
		$posicion = $this->Usuario->get_posicion($this->session->userdata('usuario')['id'])['posicion'];

		if ($posicion === 'jug_1' || $posicion === 'jug_3'){
			$equipo = 'equipo_2';
		} else {
			$equipo = 'equipo_1';
		}

			$msj = $this->Chat->get_mensajes($equipo, $id_partida);//donde equipo enemigo privado = false
			$msn = json_encode(array_reverse($msj));
			echo ($msn);
	}

	public function escribir_mensajes()
	{

		$valores = array(
			'usuario' => $this->Usuario->get_nick($this->session->userdata('usuario')['id'])['nick'],
			'id_partida' => $this->Juego->get_id_partida($this->session->userdata('usuario')['id'])['id_partida'],
			'contenido' => $_REQUEST['contenido']
		);
		$this->Chat->insertar_mensaje($valores);

	}
	public function escribir_mensaje_privado()
		{
			$posicion = $this->Usuario->get_posicion($this->session->userdata('usuario')['id'])['posicion'];

			if ($posicion === 'jug_1' || $posicion === 'jug_3'){
				$equipo = 'equipo_1';
			} else {
				$equipo = 'equipo_2';
			}
			$valores = array(
				'usuario' => $this->Usuario->get_nick($this->session->userdata('usuario')['id'])['nick'],
				'id_partida' => $this->Juego->get_id_partida($this->session->userdata('usuario')['id'])['id_partida'],
				'privado' => $equipo,
				'contenido' => $_REQUEST['contenido']
			);
			$this->Chat->insertar_mensaje($valores);

		}

}
