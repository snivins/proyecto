<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Prueba extends CI_Model
{
	function __construct()
	{
			 parent::__construct();
	}


	public function get_mensajes()
	{
    return $this->db->query('select contenido from mensajes order by id desc limit 10')->row_array();
	}



}
