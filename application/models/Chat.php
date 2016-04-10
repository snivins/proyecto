<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Chat extends CI_Model
{
	function __construct()
	{
			 parent::__construct();
	}


	public function get_mensajes()
	{
    return $this->db->query('select * from mensajes order by id desc limit 10')->result_array();
	}

	public function insertar_mensaje($valores)
	{
		return $this->db->insert('mensajes', $valores);
	}


}
