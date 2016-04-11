<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Juego extends CI_Controller {


	public function index()
	{
		$data['ay'] = 'CHAN CHAN CHAAAAAAAAN';
		$this->load->view('welcome_message', $data);
	}
}
