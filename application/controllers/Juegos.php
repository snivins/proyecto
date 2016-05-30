<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Juegos extends CI_Controller {


	public function index()
	{

		$numero = 0;
		$carta['carta_uno'] = $numero;
		$carta['carta_dos'] = $numero;
		$jug_actual = 'jug_'.$numero;
		$valores['posicion']=$this->Usuario->get_posicion(1)['posicion'];
		var_dump($valores);
		//echo(json_encode($baraja));
		$msj = $this->Juego->get_partidas_creadas();/*
		$id = 1;
		$estado = json_encode($this->Juego->get_estado($id));

		$estado = json_encode($this->Juego->unirse($valores));
		var_dump($estado);*/
		$this->template->load('juego');
	}
	public function barajar()
	{
		$baraja_oros = array('1_oros', '2_oros', '3_oros', '4_oros', '5_oros', '6_oros', '7_oros', '10_oros', '11_oros', '12_oros');
		$baraja_copas = array('1_copas', '2_copas', '3_copas', '4_copas', '5_copas', '6_copas', '7_copas', '10_copas', '11_copas', '12_copas');
		$baraja_espadas = array('1_espadas', '2_espadas', '3_espadas', '4_espadas', '5_espadas', '6_espadas', '7_espadas', '10_espadas', '11_espadas', '12_espadas');
		$baraja_bastos = array('1_bastos', '2_bastos', '3_bastos', '4_bastos', '5_bastos', '6_bastos', '7_bastos', '10_bastos', '11_bastos', '12_bastos');
		$baraja = array_merge($baraja_oros, $baraja_copas, $baraja_espadas, $baraja_bastos);
		shuffle($baraja);
		return $baraja;
	}

	public function get_estado()
	{
		$id = $_REQUEST['id'];
		$estado = json_encode($this->Juego->get_estado($id));
		echo($estado);
	}
	public function get_estado_partida()
	{
		$valores['id_partida']= $_REQUEST['id_partida'];
		$valores['posicion']=$this->Usuario->get_posicion($_REQUEST['id'])['posicion'];
		$situacion = $this->Juego->get_estado_partida($valores);

		if (!isset($_REQUEST['jugada'])) {
			//echo(json_encode(array('juega'=>'no')));
			echo(json_encode($situacion));

		}
	}

	public function unirse_partida()
	{
		$valores['id'] = $_REQUEST['id'];
		$valores['id_partida'] = $_REQUEST['id_partida'];
		$valido = $this->Juego->unirse($valores);
		if ($valido){
			if($this->Juego->partida_completa($valores['id_partida'])){
				$valores['estado'] = 'jugando';
				$this->Juego->set_estado($valores);
				$baraja_oros = array('1_oros', '2_oros', '3_oros', '4_oros', '5_oros', '6_oros', '7_oros', '10_oros', '11_oros', '12_oros');
				$baraja_copas = array('1_copas', '2_copas', '3_copas', '4_copas', '5_copas', '6_copas', '7_copas', '10_copas', '11_copas', '12_copas');
				$baraja_espadas = array('1_espadas', '2_espadas', '3_espadas', '4_espadas', '5_espadas', '6_espadas', '7_espadas', '10_espadas', '11_espadas', '12_espadas');
				$baraja_bastos = array('1_bastos', '2_bastos', '3_bastos', '4_bastos', '5_bastos', '6_bastos', '7_bastos', '10_bastos', '11_bastos', '12_bastos');
				$baraja = array_merge($baraja_oros, $baraja_copas, $baraja_espadas, $baraja_bastos);
				shuffle($baraja);
				$datos['id_partida'] = $valores['id_partida'];
			  $datos['turno'] = 1;
				$datos['ronda'] = 1;
			  $datos['dealer_id']=1;//sera un num random entre los 4 jugadores
			  $datos['baraja']=json_encode($baraja); /*cartas barajadas/ array cartas tipo 1espada , 3basto, 4copas,12oro */
			  $datos['vida']= $baraja[0];// varchar(20),/* "3oro" */
			  $datos['cartas_jugadas'] = json_encode($baraja[0]);/*solo las cartas jugadas, visibles por todos y activas en el turno se renueva tras una ronda con 3 cartas*/
			  $datos['puntos_ronda']=0;// numeric(30) not null default 1,/*en caso de que envie y acepte, se guarda aqui el total*/
			  $datos['puntos_totales']=0;// numeric(80),

				//al ser el primer turno es una version simplificada
				for ($i = 0; $i<4;$i++)//reparto las cartas en orden desde el siguiente del dealer
				{
					$numero = $datos['dealer_id'] + $i + 1;//primero saco el siguiente al dealer
					if ($numero > 4) $numero -= 4;
					if ($i == 0) {//pongo el estado correspondiente en el primer turno, el siguiente al dealer ataca, el siguiente a este def y el resto espera
						$datos_jug['estado'] = 'ataque';
					} elseif ($i == 1) {
						$datos_jug['estado'] = 'defensa';
					} else {
						$datos_jug['estado'] = 'esperando_turno';
					}
					$datos_jug['carta_uno'] = $baraja[count($datos['cartas_jugadas'])+$i];//sabre q cartas tngo en cliente con .hasOwnProperty()
					$jug_actual = 'jug_'.$numero;
					$datos[$jug_actual]= json_encode($datos_jug);
				}
				//echo($datos['baraja']);
				$this->Juego->insertar_jugada($datos);
			}
		}
		echo(json_encode($valido));
	}



}
