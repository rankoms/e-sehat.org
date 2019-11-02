<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Organisasi_model extends MY_Model {
	
	protected $_table_name = 'organisasi';
	protected $_primary_key = 'organisasi_id';
	protected $_order_by = 'organisasi_id';
	protected $_order_by_type = 'DESC';
	// protected $_type = 'artikel';

	public $rules = array(
		'organisasi_profesi' => array(
			'field' => 'organisasi_profesi', 
			'label' => 'Organisasi Profesi', 
			'rules' => 'trim|required'
		),
		'organisasi_nama' => array(
			'field' => 'organisasi_nama', 
			'label' => 'Nama Organisasi', 
			'rules' => 'trim|required'
		)
	);	

	function __construct() {
		parent::__construct();
	}	


	function get_dashboard_op($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->join('{PRE}kursus', '{PRE}organisasi.organisasi_id  = {PRE}kursus.kursus_organisasi', 'LEFT' );
		$this->db->group_by('{PRE}organisasi.organisasi_id');
		return parent::get_by($where,$limit,$offset,$single,$select);
	}
}