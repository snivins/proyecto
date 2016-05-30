<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Usuario extends CI_Model
{
	function __construct()
	{
			 parent::__construct();
	}


	public function get_posicion($id)
	{
    return $this->db->query("select posicion from usuarios where id =$id")->row_array();
	}

  public function set_posicion($id,$posicion)
  {
    $pos['posicion']= $posicion;
    $this->db->where('id', $id)->update('usuarios', $pos);
  }

}
