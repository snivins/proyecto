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

	public function insertar_jugada($valores)
	{
		return $this->db->insert('jugadas', $valores);
	}

	public function get_estado($id)
	{
		return $this->db->query("select estado from partidas where
														estado != 'terminada' AND (jug_1 = $id OR
														jug_2 = $id OR jug_3 = $id OR jug_4 = $id )")->row_array();
	}
	public function get_estado_partida($valores)
	{
		$posicion = $valores['posicion'];
		$id_partida = $valores['id_partida'];
		return $this->db->query("select id, vida, cartas_jugadas, puntos_ronda,
		 												puntos_totales, $posicion
														from jugadas where id_partida= $id_partida order by id desc")->row_array();
	}

	public function set_estado($valores)
	{
		$id_partida = $valores['id_partida'];
		$estado['estado'] = $valores['estado'];//unshift maybe?
		$this->db->where('id_partida', $id_partida)->update('partidas', $estado);
	}

	public function partida_completa($id_partida)
	{
		$res = $this->db->query("select * from partidas where id_partida=$id_partida
														AND jug_1 is not null and jug_2 is not null and
														jug_3 is not null and jug_4 is not null ");
		return $res->num_rows()>0?true:false;
	}
	public function get_info($id_partida)
	{
		return $this->db->query("select * from partidas
															where id_partida=$id_partida")->row_array();
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
