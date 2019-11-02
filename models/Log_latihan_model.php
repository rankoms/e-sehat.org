<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Log_latihan_model extends MY_Model {
	
	protected $_table_name = 'log_latihan';
	protected $_primary_key = 'log_lat_id';
	protected $_order_by = 'log_lat_id';
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
	function get_log_latihan($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->join('{PRE}user', '{PRE}user.ID = {PRE}log_latihan.log_lat_iduser', 'LEFT');
		$this->db->join('{PRE}modul', '{PRE}modul.modul_id = {PRE}log_latihan.log_lat_modulid', 'LEFT');
		$this->db->join('{PRE}materi', '{PRE}materi.materi_id = {PRE}log_latihan.log_lat_materiid', 'LEFT');
		$this->db->join('{PRE}kursus', '{PRE}kursus.kursus_id = {PRE}materi.materi_kursus', 'LEFT');
		$this->db->join('{PRE}organisasi', '{PRE}organisasi.organisasi_id = {PRE}kursus.kursus_organisasi', 'LEFT');
		return parent::get_by($where,$limit,$offset,$single,$select);
	}
	function get_log_ujian($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->join('{PRE}user', '{PRE}user.ID = {PRE}log_latihan.log_lat_iduser', 'LEFT');
		$this->db->join('{PRE}kursus', '{PRE}kursus.kursus_id = {PRE}log_latihan.log_lat_kursusid', 'LEFT');
		$this->db->join('{PRE}organisasi', '{PRE}organisasi.organisasi_id = {PRE}kursus.kursus_organisasi', 'LEFT');
		return parent::get_by($where,$limit,$offset,$single,$select);
	}
}