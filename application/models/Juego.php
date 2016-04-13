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


}
