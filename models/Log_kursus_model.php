<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Log_kursus_model extends MY_Model {
	
	protected $_table_name = 'log_kursus';
	protected $_primary_key = 'dk_id';
	protected $_order_by = 'dk_id';
	protected $_order_by_type = 'ASC';
	// protected $_type = 'artikel';

	// public $rules = array(
	// 	'bank_norek' => array(
	// 		'field' => 'bank_norek', 
	// 		'label' => 'No rekening', 
	// 		'rules' => 'trim|required|numeric'
	// 	)
	// );	

	function __construct() {
		parent::__construct();
	}	
	function get_log_kursus($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->join('{PRE}user', '{PRE}user.ID = {PRE}log_kursus.dk_userid', 'LEFT');
		$this->db->join('{PRE}kursus', '{PRE}kursus.kursus_id = {PRE}log_kursus.dk_kursusid', 'LEFT');
		$this->db->join('{PRE}organisasi', '{PRE}organisasi.organisasi_id = {PRE}kursus.kursus_organisasi', 'LEFT');
		return parent::get_by($where,$limit,$offset,$single,$select);
	}
}