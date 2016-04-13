<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Juegos extends CI_Controller {


	public function index()
	{
		$baraja_oros = array('1oros', '2oros', '3oros', '4oros', '5oros', '6oros', '7oros', '10oros', '11oros', '12oros');
		$baraja_copas = array('1copas', '2copas', '3copas', '4copas', '5copas', '6copas', '7copas', '10copas', '11copas', '12copas');
		$baraja_espadas = array('1espadas', '2espadas', '3espadas', '4espadas', '5espadas', '6espadas', '7espadas', '10espadas', '11espadas', '12espadas');
		$baraja_bastos = array('1bastos', '2bastos', '3bastos', '4bastos', '5bastos', '6bastos', '7bastos', '10bastos', '11bastos', '12bastos');
		$baraja = array_merge($baraja_oros, $baraja_copas, $baraja_espadas, $baraja_bastos);
		shuffle($baraja);
		$msj = $this->Juego->get_partidas_creadas();
		var_dump($msj);
		die();
		$this->load->view('juego', $data);
	}
}
