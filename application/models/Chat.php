<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Chat extends CI_Model
{
	function __construct()
	{
			 parent::__construct();
	}


	public function get_mensajes($equipo,  $id_partida)
	{
    return $this->db->query("select * from mensajes where id_partida=$id_partida AND privado!='$equipo' order by id desc limit 20")->result_array();
	}

	public function insertar_mensaje($valores)
	{
		return $this->db->insert('mensajes', $valores);
	}


}
