<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Usuario extends CI_Model
{
	function __construct()
	{
			 parent::__construct();
	}

	public function get_nick($id)
	{
    return $this->db->query("select nick from usuarios where id =$id")->row_array();		
	}
	public function get_posicion($id)
	{
    return $this->db->query("select posicion from usuarios where id =$id")->row_array();
	}
	public function logueado()
	{
			return $this->session->has_userdata('usuario');
	}
	public function por_id($id)
{
		$res = $this->db->get_where('usuarios', array('id' => $id));
		return $res->num_rows() > 0 ? $res->row_array() : FALSE;
}
  public function por_nick($nick)
  {
      $res = $this->db->get_where('usuarios', array('nick' => $nick));
      return $res->num_rows() > 0 ? $res->row_array() : FALSE;
  }
  public function set_posicion($id,$posicion)
  {
    $pos['posicion']= $posicion;
    $this->db->where('id', $id)->update('usuarios', $pos);
  }
	public function existe_nick($nick)
	{
		$res = $this->db->get_where('usuarios', array('nick' => $nick));
    return $res->num_rows() > 0 ? $res->row_array() : FALSE;
	}
	public function existe_email($email)
	{
			$res = $this->db->get_where('usuarios', array('email' => $email));
			return $res->num_rows() > 0 ? $res->row_array() : FALSE;
	}
	public function insertar($valores)
	{
		return $this->db->insert('usuarios', $valores);
	}
}
