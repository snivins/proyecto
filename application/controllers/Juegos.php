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
		$datos = $this->Juego->get_estado_partida(1);
		$baraja = json_decode($datos['jug_1']);
		$pos = $this->Usuario->get_posicion(1)['posicion'];//jug_1
		$estado = $datos[$pos];
		$num_sig =  substr($pos, strpos($pos,'_')+1);
		$num_sig++;
		$carta_jugada[] = json_decode($datos['cartas_jugadas']);

		$vida = json_decode($datos['cartas_jugadas'])[0];
				var_dump($this->Juego->get_jug_id("jug_3", 1));
						var_dump(substr($vida,0,2));
				var_dump("02"==2);
				var_dump($num_sig);
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
		$baraja_oros = array('01_oros', '02_oros', '03_oros', '04_oros', '05_oros', '06_oros', '07_oros', '10_oros', '11_oros', '12_oros');
		$baraja_copas = array('01_copas', '02_copas', '03_copas', '04_copas', '05_copas', '06_copas', '07_copas', '10_copas', '11_copas', '12_copas');
		$baraja_espadas = array('01_espadas', '02_espadas', '03_espadas', '04_espadas', '05_espadas', '06_espadas', '07_espadas', '10_espadas', '11_espadas', '12_espadas');
		$baraja_bastos = array('01_bastos', '02_bastos', '03_bastos', '04_bastos', '05_bastos', '06_bastos', '07_bastos', '10_bastos', '11_bastos', '12_bastos');
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
		$situacion = $this->Juego->get_estado_jugador($valores);

		if (!isset($_REQUEST['jugada'])) {
			//echo(json_encode(array('juega'=>'no')));
			echo(json_encode($situacion));

		}
	}

	public function nueva_jugada()
	{
		$valores['id'] = $_REQUEST['id'];
		$valores['id_partida'] = $_REQUEST['id_partida'];
		$valores['movimiento'] = $_REQUEST['movimiento'];
		$posicion = $this->Usuario->get_posicion($valores['id'])['posicion'];
		$datos = $this->Juego->get_estado_partida($valores['id_partida']);
		$valores_jugador = json_decode($datos[$posicion],true);
		if ($valores_jugador['estado'] !== 'defensa' && $valores_jugador['estado'] !== 'ataque'){
			echo('oh oh');
		} else {
		//calculamos el tipo de movimiento
		if ($valores['movimiento'] === 'Envio'){
			$datos['puntos_pendientes']+=3;
			$valores_jugador['estado'] = 'esperando_turno';
			$datos[$posicion] = json_encode($valores_jugador);
			$sig_jug = substr($posicion,strpos($posicion,'_')+1);
			$sig_jug +=1;
			if ($sig_jug > 4) $sig_jug-=4;
			$sig_jug = 'jug_'.$sig_jug;
			$jugador_defensa = json_decode($datos[$sig_jug],true);
			$jugador_defensa['estado'] = 'defensa';
			$datos[$sig_jug] = json_encode($jugador_defensa);
			$this->Juego->insertar_jugada($datos);

		}	else if ($valores['movimiento'] === 'Quiero') {
				$datos['puntos_ronda'] = $datos['puntos_pendientes'];
				$datos['puntos_pendientes'] = 0;
				$valores_jugador['estado'] = 'esperando_turno';
				$datos[$posicion] = json_encode($valores_jugador);
				$sig_jug = substr($posicion,strpos($posicion,'_')+1);
				$sig_jug -=1;
				if ($sig_jug < 1) $sig_jug+=4;
				$sig_jug = 'jug_'.$sig_jug;
				$jugador_defensa = json_decode($datos[$sig_jug],true);
				$jugador_defensa['estado'] = 'ataque';
				$datos[$sig_jug] = json_encode($jugador_defensa);
				$this->Juego->insertar_jugada($datos);
		} else if ($valores['movimiento'] !== 'Mas'){
			if (is_array(json_decode($datos['cartas_jugadas']))){
				$datos['cartas_jugadas'] = json_decode($datos['cartas_jugadas']);
				$datos['cartas_jugadas'][] = $valores['movimiento'];
				$datos['cartas_jugadas'] = json_encode($datos['cartas_jugadas']);
			} else {
				$cartas_jugada[] = json_decode($datos['cartas_jugadas']);
				$cartas_jugada[] = $valores['movimiento'];
				$datos['cartas_jugadas'] = json_encode($cartas_jugada);
			}

			//$cartas_jugadas[0] =
			if ($datos['turno_ronda'] < 4){
				$datos['turno_ronda']++;
				$valores_jugador['estado'] = 'esperando_turno';
				$datos[$posicion] = json_encode($valores_jugador);
				$pos_sig_jug = substr($posicion, strpos($posicion,'_')+1);
				if ($pos_sig_jug < 4){
					$pos_sig_jug++;
				} else {
					$pos_sig_jug=1;
				}
				$pos_sig_jug = 'jug_'.$pos_sig_jug;
				$sig_jug = json_decode($datos[$pos_sig_jug],true);
				$sig_jug['estado'] = 'ataque';
				$datos[$pos_sig_jug] = json_encode($sig_jug);
				$this->Juego->insertar_jugada($datos);
			} else {
				$datos['turno_ronda'] = 1;

				if ($datos['turno'] == 0){
					$datos['ronda']++;
				}

				$nueva_ronda = false;
				$suma= 0;

				$num_cards = $datos['ronda'];
				while ($num_cards > 3){
					$num_cards-=3;
				}
				if ($num_cards == 3 && $datos['turno'] == 0){
					$datos['turno'] = 3;
				}

				if ($datos['puntos_equipo_1'] > 21 || $datos['puntos_equipo_2'] > 21){
					$nueva_ronda = true;
					$num_cards = 3;

				} else if ($num_cards == 1){
					$nueva_ronda = true;
					$datos['turno'] = 0;
				}

				if ($num_cards == 3){
					$suma= 4;
				}

				if ($nueva_ronda) {
					$baraja = $this->barajar();
					$datos['baraja'] = json_encode($baraja);
				} else {
					$baraja = json_decode($datos['baraja']);
				}

					$valores_jugador['estado'] = 'esperando_turno';
					$datos[$posicion] = json_encode($valores_jugador);

					$pos_sig_jug = substr($posicion, strpos($posicion,'_')+1);
					if ($pos_sig_jug < 4){
						$pos_sig_jug++;
					} else {
						$pos_sig_jug=1;
					}
					$pos_sig_jug = 'jug_'.$pos_sig_jug;
					$sig_jug = json_decode($datos[$pos_sig_jug],true);
					$sig_jug['estado'] = 'ataque';
					$datos[$pos_sig_jug] = json_encode($sig_jug);

				if ($datos['turno'] == 3 || $datos['turno'] == 0){ //cambio de dealer
					unset($valores_jugador);
					$valores_jugador['estado']='esperando_turno';
					for ($i = 1; $i <= $num_cards;$i++){
						switch ($i){
							case 1:
								$j = "uno";
								break;
							case 2:
								$j = "dos";
							break;
							case 3:
								$j = "tres";
							break;
						}
						if ($nueva_ronda){
							$valores_jugador['carta_'.$j] = $baraja[($i*4)-2];
						} else {
							$valores_jugador['carta_'.$j] = $baraja[($i*4)+count(json_decode($datos['cartas_jugadas']))-2+$suma];
						}
					}
					$datos[$posicion] = json_encode($valores_jugador);

					$pos_sig_jug = substr($posicion, strpos($posicion,'_')+1);
					if ($pos_sig_jug < 4){
						$pos_sig_jug++;
					} else {
						$pos_sig_jug=1;
					}
					$pos_sig_jug = 'jug_'.$pos_sig_jug;
					unset($sig_jug);
					$sig_jug['estado'] = 'esperando_turno';

					for ($i = 1; $i <= $num_cards;$i++){
						switch ($i){
							case 1:
								$j = "uno";
								break;
							case 2:
								$j = "dos";
							break;
							case 3:
								$j = "tres";
							break;
						}
						if ($nueva_ronda){
							$sig_jug['carta_'.$j] = $baraja[($i*4)-1];
						} else {
							$sig_jug['carta_'.$j] = $baraja[($i*4)+count(json_decode($datos['cartas_jugadas']))-1+$suma];
						}
					}
					$datos[$pos_sig_jug] = json_encode($sig_jug);


					$pos_sig_jug = substr($pos_sig_jug, strpos($pos_sig_jug,'_')+1);
					if ($pos_sig_jug < 4){
						$pos_sig_jug++;
					} else {
						$pos_sig_jug=1;
					}
					$pos_sig_jug = 'jug_'.$pos_sig_jug;
					$sig_jug['estado'] = 'ataque';

					for ($i = 1; $i <= $num_cards;$i++){
						switch ($i){
							case 1:
								$j = "uno";
								break;
							case 2:
								$j = "dos";
							break;
							case 3:
								$j = "tres";
							break;
						}
						if ($nueva_ronda){
							$sig_jug['carta_'.$j] = $baraja[($i*4)-4];
						} else {
							$sig_jug['carta_'.$j] = $baraja[($i*4)+count(json_decode($datos['cartas_jugadas']))-4+$suma];
						}
					}
					$datos[$pos_sig_jug] = json_encode($sig_jug);

					$pos_sig_jug = substr($pos_sig_jug, strpos($pos_sig_jug,'_')+1);
					if ($pos_sig_jug < 4){
						$pos_sig_jug++;
					} else {
						$pos_sig_jug=1;
					}
					$pos_sig_jug = 'jug_'.$pos_sig_jug;
					$sig_jug['estado'] = 'esperando_turno';

					for ($i = 1; $i <= $num_cards;$i++){
						switch ($i){
							case 1:
								$j = "uno";
								break;
							case 2:
								$j = "dos";
							break;
							case 3:
								$j = "tres";
							break;
						}
						if ($nueva_ronda){
							$sig_jug['carta_'.$j] = $baraja[($i*4)-3];
						} else {
							$sig_jug['carta_'.$j] = $baraja[($i*4)+count(json_decode($datos['cartas_jugadas']))-3+$suma];
						}
					}
					$datos[$pos_sig_jug] = json_encode($sig_jug);
				}
/*
	para saber cuantas cartas repartir
	if ($datos['puntos_equipo_1'] > 21 || $datos['puntos_equipo_2'] > 21){
	$cartas a repartir = 3
} else {
	$cartas a repartir = $datos['ronda'];
	while ($cartas a repartir] > 3){
		$cartas a repartir-=3;
	}
}

*/

				//Calculo de puntos
				if ($valores['movimiento'] != 'Paso'){
					$vida = json_decode($datos['cartas_jugadas'])[0];
					$vida = substr($vida, strpos($vida,'_')+1);//m da el palo
					$palo_mayor = json_decode($datos['cartas_jugadas'])[1];
					$palo_mayor = substr($palo_mayor, strpos($palo_mayor,'_')+1);
					$ganador = 0;
					$ganador_puntos = 0;
					$cartas_jugadas = json_decode($datos['cartas_jugadas']);
					$cartas_calculo[1] = $cartas_jugadas[count($cartas_jugadas)-4];//4 ultimas cartas jugadas
					$cartas_calculo[2] = $cartas_jugadas[count($cartas_jugadas)-3];
					$cartas_calculo[3] = $cartas_jugadas[count($cartas_jugadas)-2];
					$cartas_calculo[4] = $cartas_jugadas[count($cartas_jugadas)-1];
					for ($i = 1; $i<5;$i++){
						$cartas_puntos[$i] = $cartas_calculo[$i];
						$puntos[$i] = substr($cartas_calculo[$i],0,2);
						if ($puntos[$i]=='01'){
							$puntos[$i] = 8;
						}
						if (substr($cartas_puntos[$i], strpos($cartas_puntos[$i],'_')+1) === $vida) {
							if ($puntos[$i]=='02'){
								$puntos[$i]=13;
							}
							$puntos[$i]+= 40;
						} else if (substr($cartas_puntos[$i], strpos($cartas_puntos[$i],'_')+1) === $palo_mayor) {
							$puntos[$i]+= 20;
						}

						if ($puntos[$i] > $ganador_puntos){
							$ganador_puntos = $puntos[$i];
								$ganador = $i;//aqui guardo el jugador q gana, dealer = 4, 1 , 2 ,3
						}
					}
				//Entrega de puntos
				/*

								dealer_id = jug_ 3
								jug_3 = pos 4
								jug_4 = pos 1
								jug_1 = pos 2
								jug_2 = pos 3

								caso gana pos 3 con dealer jug_2
								q jug es pos 3?
								2 + 3 = 5
								5 > 4
								5 - 4 = 1;
								1 < 4
								1 o 3? equipo 1 gana
								2 o 4? equipo 2 gana

				*/

					$posicion_dealer = $this->Usuario->get_posicion($datos['dealer_id'])['posicion'];
					$posicion_dealer = substr($posicion_dealer,strpos($posicion_dealer,'_')+1);
					$nuevo_dealer = $posicion_dealer + 1;
					if ($nuevo_dealer > 4) $nuevo_dealer -=4;
					$nuevo_dealer = 'jug_'.$nuevo_dealer;
					$datos['dealer_id'] = $this->Juego->get_jug_id($nuevo_dealer, $valores['id_partida'])[$nuevo_dealer];//quiza falle
					$jug_ganador = $ganador+$posicion_dealer;
					if ($jug_ganador > 4) $jug_ganador -= 4;
					if ($jug_ganador%2 == 0){//2 o 4 gana equipo 2
						$datos['puntos_equipo_2'] += $datos['puntos_ronda'];
					} else {//1 o 3 equipo 1
						$datos['puntos_equipo_1'] += $datos['puntos_ronda'];
					}
				}
				$datos['puntos_ronda'] = 1;


				if ($datos['turno'] != 0){
					$datos['turno']--;
				}

				$this->Juego->insertar_jugada($datos);
			}
		}
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
				$baraja = $this->barajar();
				$datos['id_partida'] = $valores['id_partida'];
				$datos['turno'] = 0;
				$datos['ronda'] = 1;
				$datos['turno_ronda'] = 1;
				$datos['dealer_id']=1;//sera un num random entre los 4 jugadores
				$datos['baraja']=json_encode($baraja); /*cartas barajadas/ array cartas tipo 1espada , 3basto, 4copas,12oro */
				$datos['vida']= $baraja[0];// varchar(20),/* "3oro" */
				$datos['cartas_jugadas'] = json_encode($baraja[0]);/*solo las cartas jugadas, visibles por todos y activas en el turno se renueva tras una ronda con 3 cartas*/
				$datos['puntos_ronda']=1;// numeric(30) not null default 1,/*en caso de que envie y acepte, se guarda aqui el total*/
				$datos['puntos_equipo_1']=0;// numeric(80),
				$datos['puntos_equipo_2']=0;
				//al ser el primer turno es una version simplificada
				for ($i = 0; $i<4;$i++)//reparto las cartas en orden desde el siguiente del dealer
				{
					$numero = $datos['dealer_id'] + $i + 1;//primero saco el siguiente al dealer
					if ($numero > 4) $numero -= 4;
					if ($i == 0) {//pongo el estado correspondiente en el primer turno, el siguiente al dealer ataca, el siguiente a este def y el resto espera
						$datos_jug['estado'] = 'ataque';
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
