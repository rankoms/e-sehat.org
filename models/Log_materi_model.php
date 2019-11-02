<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Log_materi_model extends MY_Model {
	
	protected $_table_name = 'log_materi';
	protected $_primary_key = 'log_materi_id';
	protected $_order_by = 'log_materi_id';
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
	function get_log_materi($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->join('{PRE}user', '{PRE}user.ID = {PRE}log_materi.log_materi_iduser', 'LEFT');
		$this->db->join('{PRE}modul', '{PRE}modul.modul_id = {PRE}log_materi.log_materi_modul', 'LEFT');
		$this->db->join('{PRE}materi', '{PRE}materi.materi_id = {PRE}log_materi.log_materi_materiid', 'LEFT');
		$this->db->join('{PRE}kursus', '{PRE}kursus.kursus_id = {PRE}materi.materi_kursus', 'LEFT');
		$this->db->join('{PRE}organisasi', '{PRE}organisasi.organisasi_id = {PRE}kursus.kursus_organisasi', 'LEFT');
		return parent::get_by($where,$limit,$offset,$single,$select);
	}
}