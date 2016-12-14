<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Juego extends CI_Model
{
	function __construct()
	{
			 parent::__construct();
	}

	public function get_partidas_creadas()
	{
    return $this->db->query("select * from partidas where estado = 'creada'")->result_array();
	}
	public function get_partidas_terminadas($id)
	{
    return $this->db->query("select * from partidas where estado = 'terminada' AND (jug_1 = $id OR jug_2 = $id OR jug_3 = $id OR jug_4 = $id )")->result_array();
	}

	public function get_jug_activo($id_partida)
	{
		$res = $this->db->query("select jug_1->>'estado' from jugadas where id_partida = $id_partida order by id desc")->row_array();
		foreach ($res as $i=>$j){
			if ($j === 'defensa' || $j ==='ataque'){ return 1;	}
		}
		$res = $this->db->query("select jug_2->>'estado' from jugadas where id_partida = $id_partida order by id desc")->row_array();
		foreach ($res as $i=>$j){
			if ($j === 'defensa' || $j ==='ataque'){ return 2;	}
		}
		$res = $this->db->query("select jug_3->>'estado' from jugadas where id_partida = $id_partida order by id desc")->row_array();
		foreach ($res as $i=>$j){
			if ($j === 'defensa' || $j ==='ataque'){ return 3;	}
		}
		$res = $this->db->query("select jug_4->>'estado' from jugadas where id_partida = $id_partida order by id desc")->row_array();
		foreach ($res as $i=>$j){
			if ($j === 'defensa' || $j ==='ataque'){ return 4;	}
		}
	}


	public function nueva_partida($id){
		$valores['jug_1'] = $id;
		$valores['estado'] = 'creada';
		$this->Usuario->set_posicion($id,'jug_1');
		return $this->db->insert('partidas', $valores);
	}
	public function insertar_jugada($valores)
	{
		return $this->db->insert('jugadas', $valores);
	}
	public function get_jug_id($pos_jug,$id_partida){
		return $this->db->query("select $pos_jug from partidas where
														id_partida = $id_partida")->row_array();
	}
	public function get_id_partida($id){
		return $this->db->query("select id_partida from partidas where
														estado != 'terminada' AND estado != 'cancelada' AND (jug_1 = $id OR
														jug_2 = $id OR jug_3 = $id OR jug_4 = $id )")->row_array();
	}
	public function get_estado($id)
	{
		return $this->db->query("select * from partidas where
														estado != 'terminada' AND estado != 'cancelada' AND (jug_1 = $id OR
														jug_2 = $id OR jug_3 = $id OR jug_4 = $id )")->row_array();
	}
	public function get_estado_jugador($valores)
	{
		$posicion = $valores['posicion'];
		$id_partida = $valores['id_partida'];
		return $this->db->query("select id, vida, cartas_jugadas, puntos_ronda,
		 												puntos_equipo_1,puntos_equipo_2, $posicion, puntos_pendientes,turno_jug, ultima_mano, cartas_jugadas_totales
														from jugadas where id_partida= $id_partida order by id desc")->row_array();
	}
	public function get_mensajes($id_partida)
	{
    return $this->db->query("select ultima_jugada from jugadas where id_partida=$id_partida order by id desc limit 5")->result_array();
	}

	public function get_estado_partida($id_partida)
	{
		return $this->db->query("select id_partida,turno, ronda, turno_ronda, dealer_id, vida,
														baraja, cartas_jugadas, puntos_ronda,	puntos_equipo_1,puntos_equipo_2,
														jug_1,jug_2,jug_3,jug_4,puntos_pendientes,ultima_mano, cartas_jugadas_totales	from jugadas
														where id_partida= $id_partida order by id desc")->row_array();
	}
		public function get_estado_partida_final($id_partida)
	{
		return $this->db->query("select estado from partidas
														where id_partida= $id_partida")->row_array();
	}
	public function set_jugada($valores){

		$this->db->where('id', $valores['id'])->update('jugadas', $valores);
	}
	public function set_nombre($id_partida, $nombre){
		$datos['nombre'] = $nombre;

		$this->db->where('id_partida', $id_partida)->update('partidas', $datos);
	}
	public function fin_partida($id_partida, $estado){
			$this->db->where('id_partida', $id_partida)->update('partidas', array('estado' => $estado));
			/*
			for ($i = 1; $i<5;$i++){
				$jug = $this->get_jug_id('jug_'.$i, $id_partida);
				if ($jug !== NULL) {
					$this->set_estado(array('id_partida' => $id_partida, 'estado' => null));
				}
			}*/
	}
	public function confirmar_salida($id_partida, $id)
	{
		$posicion = $this->Usuario->get_posicion($id);
		$jugador = array(
			$posicion['posicion'] => null
		);
		$this->db->where('id_partida', $id_partida)->update('partidas', $jugador);

		if ($this->partida_terminada_completa($id_partida)){
			if ($this->get_estado_partida_final($id_partida)['estado'] == 'cancelando') {
				$this->db->where('id_partida', $id_partida)->update('partidas', array('estado' => 'cancelada'));
			} else {
				$this->db->where('id_partida', $id_partida)->update('partidas', array('estado' => 'terminada'));
			}
		}
	}

	public function set_estado($valores)
	{
		$this->db->where('id_partida', $valores['id_partida'])->update('partidas', array('estado' => $valores['estado']));
	}

	public function partida_completa($id_partida)
	{
		$res = $this->db->query("select * from partidas where id_partida=$id_partida
														AND jug_1 is not null and jug_2 is not null and
														jug_3 is not null and jug_4 is not null ");
		return $res->num_rows()>0?true:false;
	}

		public function partida_terminada_completa($id_partida)
		{
			$res = $this->db->query("select * from partidas where id_partida=$id_partida
															AND jug_1 is null and jug_2 is null and
															jug_3 is null and jug_4 is null ");
			return $res->num_rows()>0?true:false;
		}
	public function get_info($id_partida)
	{
		return $this->db->query("select * from partidas
															where id_partida=$id_partida")->row_array();
	}
	public function borrar_jugador($id_partida, $posicion)
	{
		if ($posicion !== null)	$this->db->where('id_partida', $id_partida)->update('partidas', array($posicion => null));
	}
	public function unirse($valores)
	{
		$id_partida = $valores['id_partida'];
		/*
		*					MIRAMOS QUE HUECO HAY LIBRE
		*/
		$existe = $this->db->query("select * from partidas
																where id_partida=$id_partida and
																jug_1 IS NULL");

		if ($existe->num_rows()>0){
			//inserta como jug_1
			$jugador = array(
				'jug_1' => $valores['id']
			);
			$this->Usuario->set_posicion($valores['id'], "jug_1");
			return $this->db->where('id_partida', $id_partida)->update('partidas', $jugador);
		} else {
			$existe = $this->db->query("select * from partidas
															where id_partida=$id_partida and
															jug_2 IS NULL");
			if ($existe->num_rows()>0){
				//inserta como jug_2
				$jugador = array(
					'jug_2' => $valores['id']
				);
				$this->Usuario->set_posicion($valores['id'], "jug_2");
				return $this->db->where('id_partida', $id_partida)->update('partidas', $jugador);
			} else {
				$existe = $this->db->query("select * from partidas
																where id_partida=$id_partida and
																jug_3 IS NULL");

				if ($existe->num_rows()>0){
					//inserta como jug_3
					$jugador = array(
						'jug_3' => $valores['id']
					);
					$this->Usuario->set_posicion($valores['id'], "jug_3");
					return $this->db->where('id_partida', $id_partida)->update('partidas', $jugador);
				} else {
					$existe = $this->db->query("select * from partidas
																	where id_partida=$id_partida and
																	jug_4 IS NULL");
					if ($existe->num_rows()>0){
						//inserta como jug_4
						$jugador = array(
							'jug_4' => $valores['id']
						);
						$this->Usuario->set_posicion($valores['id'], "jug_4");
        		return $this->db->where('id_partida', $id_partida)->update('partidas', $jugador);
					} else {
						return false;
					}
				}
			}
		}
	}

}
