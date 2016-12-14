<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Juegos extends CI_Controller {

	public function juego()
	{
		$this->template->load('index');

	}
	public function index()
	{
/*
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
				var_dump($this->session->userdata('usuario')['id']);
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
	public function multimedia() {
		$this->template->load('multimedia');
	}
	public function nuevo_nombre() {
		$id_partida= $this->Juego->get_id_partida($this->session->userdata('usuario')['id'])['id_partida'];
		$nombre = $_REQUEST['nombre'];
		if ($nombre != null && $nombre != ""){
			$this->Juego->set_nombre($id_partida, $nombre);
		}
	}
	public function abandonar_partida()
	{
		$id_partida = $this->Juego->get_id_partida($this->session->userdata('usuario')['id'])['id_partida'];
		$this->Juego->fin_partida($id_partida, 'abandonando');
	}
	public function confirmar_salir()
	{
		$id = $this->session->userdata('usuario')['id'];
		$id_partida = $this->Juego->get_id_partida($id)['id_partida'];

		$this->Juego->confirmar_salida($id_partida, $id);
	}
	public function tutorial(){
		$this->template->load('tutorial');
	}
	public function ver_jugadas(){
		$id_partida = $this->Juego->get_id_partida($this->session->userdata('usuario')['id'])['id_partida'];
		$msj = $this->Juego->get_mensajes($id_partida);//donde equipo enemigo privado = false
		$msn = json_encode(array_reverse($msj));
		echo ($msn);
	}
	public function abandonar_sala() //como no ha empezado no elimina la partida
	{
		$pos = $this->Usuario->get_posicion($this->session->userdata('usuario')['id'])['posicion'];
		if ($pos === 'jug_1'){
			$this->abandonar_partida();
		} else if ($pos !== 'null'){
			$this->Juego->borrar_jugador($this->Juego->get_id_partida($this->session->userdata('usuario')['id'])['id_partida'], $pos);
			$this->Usuario->set_posicion($this->session->userdata('usuario')['id'], null);
		}
	}
	public function listar_partidas()
	{
		$partidas = $this->Juego->get_partidas_creadas();
		echo (json_encode($partidas));
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
		$id = $this->session->userdata('usuario')['id'];
		$datos = $this->Juego->get_estado($id);
		if ($datos['jug_1'] !== NULL) {
			$datos['jug_1_nick'] = $this->Usuario->get_nick($datos['jug_1'])['nick'];
			$datos['jug_1_foto'] = $this->Usuario->get_foto($datos['jug_1'])['foto_perfil'];
		}
		if ($datos['jug_2'] !== NULL) {
			$datos['jug_2_nick'] = $this->Usuario->get_nick($datos['jug_2'])['nick'];
			$datos['jug_2_foto'] = $this->Usuario->get_foto($datos['jug_2'])['foto_perfil'];
		}
		if ($datos['jug_3'] !== NULL) {
			$datos['jug_3_nick'] = $this->Usuario->get_nick($datos['jug_3'])['nick'];
			$datos['jug_3_foto'] = $this->Usuario->get_foto($datos['jug_3'])['foto_perfil'];
		}
		if ($datos['jug_4'] !== NULL) {
			$datos['jug_4_nick'] = $this->Usuario->get_nick($datos['jug_4'])['nick'];
			$datos['jug_4_foto'] = $this->Usuario->get_foto($datos['jug_4'])['foto_perfil'];
		}
		$estado = json_encode($datos);
		echo($estado);
	}
	public function get_estado_partida()
	{
		$valores['id_partida']= $this->Juego->get_id_partida($this->session->userdata('usuario')['id'])['id_partida'];
		$valores['posicion']=$this->Usuario->get_posicion($this->session->userdata('usuario')['id'])['posicion'];
		$situacion = $this->Juego->get_estado_jugador($valores);

		//$situacion['activo'] = $this->Juego->get_jug_activo($valores['id_partida']);
		if (!isset($_REQUEST['jugada'])) {
			//echo(json_encode(array('juega'=>'no')));
			echo(json_encode($situacion));

		}
	}

	public function nueva_jugada()
	{
		$valores['id'] = $this->session->userdata('usuario')['id'];
		$valores['id_partida'] = $this->Juego->get_id_partida($valores['id'])['id_partida'];
		$valores['movimiento'] = $_REQUEST['movimiento'];
		$posicion = $this->Usuario->get_posicion($valores['id'])['posicion'];
		$datos = $this->Juego->get_estado_partida($valores['id_partida']);
		if ($datos['puntos_equipo_1'] >= 30){
			var_dump("ay");
			$this->Juego->fin_partida($valores['id_partida'], 'terminando1');
		}
		if ($datos['puntos_equipo_2'] >= 30){
			$this->Juego->fin_partida($valores['id_partida'], 'terminando2');
		}
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
			$datos['turno_jug'] = $sig_jug;

			$nombre_jugada = $this->Juego->get_jug_id($posicion, $valores['id_partida']);
			$nombre_jugada = $this->Usuario->get_nick($nombre_jugada[$posicion]);
			$datos['ultima_jugada']= $nombre_jugada['nick'] . ' envia. (Pide jugar por 3 puntos)';
			$this->Juego->insertar_jugada($datos);

		}	else if ($valores['movimiento'] === 'Quiero') {
			if ($datos['puntos_ronda'] > 1) {
				$datos['puntos_ronda'] += $datos['puntos_pendientes'];
			} else {
				$datos['puntos_ronda'] = $datos['puntos_pendientes'];
			}
				$valores_jugador['estado'] = 'esperando_turno';
				$datos[$posicion] = json_encode($valores_jugador);
				$sig_jug = substr($posicion,strpos($posicion,'_')+1);
				if ($datos['puntos_pendientes'] % 6 == 0){//para ver si es el jugador q envio inicialmente
					$sig_jug = 'jug_'.$sig_jug;
				} else {
					$sig_jug -=1;
					if ($sig_jug < 1) $sig_jug+=4;
					$sig_jug = 'jug_'.$sig_jug;
				}
				$datos['puntos_pendientes'] = 0;
				$jugador_defensa = json_decode($datos[$sig_jug],true);
				$jugador_defensa['estado'] = 'ataque';

				$datos['turno_jug'] = $sig_jug;
				$nombre_jugada = $this->Juego->get_jug_id($posicion, $valores['id_partida']);
				$nombre_jugada = $this->Usuario->get_nick($nombre_jugada[$posicion]);
				$datos['ultima_jugada']= $nombre_jugada['nick'] . ' quiere jugar por '.$datos['puntos_ronda'].' puntos';
				$datos[$sig_jug] = json_encode($jugador_defensa);
				$this->Juego->insertar_jugada($datos);
		} else if ($valores['movimiento'] === 'Mas'){
				$valores_jugador['estado'] = 'esperando_turno';
				$datos[$posicion] = json_encode($valores_jugador);
				$sig_jug = substr($posicion,strpos($posicion,'_')+1);
				if ($datos['puntos_pendientes'] % 6 == 0){//para ver si es el jugador q envio inicialmente
					$sig_jug +=1;
					if ($sig_jug > 4) $sig_jug-=4;
					$sig_jug = 'jug_'.$sig_jug;
				} else {
					$sig_jug -=1;
					if ($sig_jug < 1) $sig_jug+=4;
					$sig_jug = 'jug_'.$sig_jug;
				}
				$datos['puntos_pendientes']+=3;
				$jugador_defensa = json_decode($datos[$sig_jug],true);
				$jugador_defensa['estado'] = 'defensa';

				$datos['turno_jug'] = $sig_jug;
				$nombre_jugada = $this->Juego->get_jug_id($posicion, $valores['id_partida']);
				$nombre_jugada = $this->Usuario->get_nick($nombre_jugada[$posicion]);
				$datos['ultima_jugada']= $nombre_jugada['nick'] . ' ve y pide mas puntos ('.$datos['puntos_pendientes'].').';
				$datos[$sig_jug] = json_encode($jugador_defensa);
				$this->Juego->insertar_jugada($datos);
		} else if ($valores['movimiento'] === 'Paso'){
			if ($datos['puntos_pendientes'] > 3){
				if ($datos['puntos_ronda'] > 1){
					$datos['puntos_ronda'] += $datos['puntos_pendientes'] - 3;
				}
				$datos['puntos_ronda'] = $datos['puntos_pendientes'] - 3;
			}
			//ver equipo q dice paso y darle los puntos al otro
			if ($posicion !== 'jug_1' && $posicion !== 'jug_3'){
				$datos['puntos_equipo_1']+=$datos['puntos_ronda'];
			} else {
				$datos['puntos_equipo_2']+=$datos['puntos_ronda'];
			}
			$datos['puntos_ronda'] = 1;
			$datos['puntos_pendientes'] = 0;
			$sig_dealer =  $this->Usuario->get_posicion($datos['dealer_id'])['posicion'];
			$sig_dealer = substr($sig_dealer, strpos($sig_dealer,'_')+1);
			$sig_dealer++;
			if ($sig_dealer > 4) $sig_dealer-=4;
			$sig_dealer = 'jug_'.$sig_dealer;

			$datos['dealer_id'] = $this->Juego->get_jug_id($sig_dealer, $valores['id_partida'])[$sig_dealer];

			$datos['turno_ronda'] = 1;

			$sig_jug = substr($sig_dealer, strpos($sig_dealer,'_')+1);
			$sig_jug++;
			if ($sig_jug > 4) $sig_jug-=4;
			$sig_jug = 'jug_'.$sig_jug;
			$sig_jug_estado = json_decode($datos[$sig_jug],true);
			$sig_jug_estado['estado'] = 'ataque';

			$datos['turno_jug'] = $sig_jug;
			$nombre_jugada = $this->Juego->get_jug_id($posicion, $valores['id_partida']);
			$nombre_jugada = $this->Usuario->get_nick($nombre_jugada[$posicion]);
			$datos['ultima_jugada']= $nombre_jugada['nick'] . ' no acepta y pasa ronda.';
			$datos[$sig_jug] = json_encode($sig_jug_estado);
			$datos['ronda']++;
			$num_cards = $datos['ronda'];
			while ($num_cards > 3){
				$num_cards-=3;
			}
			if ($num_cards == 3){
				$datos['turno'] = 3;
			}
			$nueva_ronda = false;
			if ($datos['puntos_equipo_1'] > 21 || $datos['puntos_equipo_2'] > 21){
				$nueva_ronda = true;
				$num_cards = 3;

			} else if ($num_cards == 1){
				$nueva_ronda = true;
				$datos['turno'] = 0;
			}

			$datos['cartas_jugadas_totales'] = json_decode($datos['ultima_mano']);
			$datos['ultima_mano'] = json_decode($datos['cartas_jugadas']);
			array_shift($datos['ultima_mano']);
			$datos['cartas_jugadas_totales'] = array_merge($datos['cartas_jugadas_totales'],$datos['ultima_mano']);
			$datos['cartas_jugadas_totales'] = json_encode($datos['cartas_jugadas_totales']);
			$datos['ultima_mano'] = json_encode($datos['ultima_mano']);
			if ($nueva_ronda) {
				$baraja = $this->barajar();
				$datos['baraja'] = json_encode($baraja);
				$datos['cartas_jugadas'] = json_encode($baraja[0]);
				$datos['cartas_jugadas_totales'] = json_encode($baraja[0]);
			} else {
				$baraja = json_decode($datos['baraja']);
			}
			$suma=4;
			if ($num_cards == 3)  $suma=12;
			for ($i = 1;$i < 5; $i++){//reparto las cartas
				unset($jugador);
				$jugador['estado'] = ($i==1)?'ataque':'esperando_turno';
				for ($j=0;$j<$num_cards;$j++){
					switch ($j){
						case 0:
							$k = "uno";
							break;
						case 1:
							$k = "dos";
						break;
						case 2:
							$k = "tres";
						break;
					}
					if ($nueva_ronda){
						$jugador['carta_'.$k] = $baraja[$i+($j*4)];
					} else {
						$jugador['carta_'.$k] = $baraja[$i+($j*4)+$suma];
					}
				}

				$datos[$sig_jug] = json_encode($jugador);

				var_dump($jugador);
				$sig_jug = substr($sig_jug,strpos($sig_jug,'_')+1);
				$sig_jug +=1;
				if ($sig_jug > 4) $sig_jug-=4;
				$sig_jug = 'jug_'.$sig_jug;
			}
			$this->Juego->insertar_jugada($datos);

		} else {
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

				$datos['turno_jug'] = $pos_sig_jug;

				$nombre_jugada = $this->Juego->get_jug_id($posicion, $valores['id_partida']);
				$nombre_jugada = $this->Usuario->get_nick($nombre_jugada[$posicion]);
				$datos['ultima_jugada']= $nombre_jugada['nick'] . ' juega '.$valores['movimiento'];
				$datos[$pos_sig_jug] = json_encode($sig_jug);
				$this->Juego->insertar_jugada($datos);
			} else {//se han jugado 4 cartas
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

				$datos['cartas_jugadas_totales'] = json_decode($datos['ultima_mano']);
				$datos['ultima_mano'] = json_decode($datos['cartas_jugadas']);
				array_shift($datos['ultima_mano']);
				$datos['cartas_jugadas_totales'] = array_merge($datos['cartas_jugadas_totales'],$datos['ultima_mano']);
				$datos['cartas_jugadas_totales'] = json_encode($datos['cartas_jugadas_totales']);
				$datos['ultima_mano'] = json_encode($datos['ultima_mano']);
				if ($nueva_ronda) {
					$baraja = $this->barajar();
					$datos['baraja'] = json_encode($baraja);
					$datos['cartas_jugadas'] = json_encode($baraja[0]);
					$datos['cartas_jugadas_totales'] = json_encode($baraja[0]);
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
									$datos['turno_jug'] = $pos_sig_jug;

									$nombre_jugada = $this->Juego->get_jug_id($posicion, $valores['id_partida']);
									$nombre_jugada = $this->Usuario->get_nick($nombre_jugada[$posicion]);
									$datos['ultima_jugada']= $nombre_jugada['nick'] . ' juega '.$valores['movimiento'].'.';

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
							$valores_jugador['carta_'.$j] = $baraja[($i*4)-1];
						} else if ($num_cards == 2){
							$valores_jugador['carta_'.$j] = $baraja[($i*4)-1+4];
						} else {
							$valores_jugador['carta_'.$j] = $baraja[($i*4)-1+($suma*3)];
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
							$sig_jug['carta_'.$j] = $baraja[($i*4)];
						} else if ($num_cards == 2){
							$sig_jug['carta_'.$j] = $baraja[($i*4)+4];
						} else {
							$sig_jug['carta_'.$j] = $baraja[($i*4)+($suma*3)];
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
									$datos['turno_jug'] = $pos_sig_jug;

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
						} else if ($num_cards == 2){
							$sig_jug['carta_'.$j] = $baraja[($i*4)-3+4];
						} else {
							$sig_jug['carta_'.$j] = $baraja[($i*4)-3+($suma*3)];
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
							$sig_jug['carta_'.$j] = $baraja[($i*4)-2];
						} else if ($num_cards == 2){
							$sig_jug['carta_'.$j] = $baraja[($i*4)-2+4];
						} else {
							$sig_jug['carta_'.$j] = $baraja[($i*4)-2+($suma*3)];
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
						$datos['ultima_jugada'].= 'Equipo 2 gana '.$datos['puntos_ronda'].' puntos';
					} else {//1 o 3 equipo 1
						$datos['puntos_equipo_1'] += $datos['puntos_ronda'];
						$datos['ultima_jugada'].= 'Equipo 1 gana '.$datos['puntos_ronda'].' puntos';
					}
				}
				$datos['puntos_ronda'] = 1;


				if ($datos['turno'] != 0){
					$datos['turno']--;
				}


				//vemos si hay ganador

				if ($datos['puntos_equipo_1'] >= 30){
					var_dump("ay");
					$this->Juego->fin_partida($valores['id_partida'], 'terminando1');
				}
				if ($datos['puntos_equipo_2'] >= 30){
					$this->Juego->fin_partida($valores['id_partida'], 'terminando2');
				}

				$datos['cartas_jugadas'] = json_encode($baraja[0]);
				$this->Juego->insertar_jugada($datos);
			}

		}
	}

	}
	public function nueva_partida(){
		$id = $this->session->userdata('usuario')['id'];
		if ($this->Juego->get_id_partida($id)['id_partida'] === null) {
			$this->Juego->nueva_partida($id);
		}
	}
	public function unirse_partida()
	{
		$valores['id'] = $this->session->userdata('usuario')['id'];
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

				$datos['ultima_jugada'].= 'La vida es '.$datos['vida'];
				$datos['cartas_jugadas'] = json_encode($baraja[0]);/*solo las cartas jugadas, visibles por todos y activas en el turno se renueva tras una ronda con 3 cartas*/
				$datos['cartas_jugadas_totales'] = $datos['cartas_jugadas'];
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
						$datos['turno_jug'] = 'jug_2';
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
