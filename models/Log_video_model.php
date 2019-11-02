<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Log_video_model extends MY_Model {
	
	protected $_table_name = 'log_video';
	protected $_primary_key = 'lv_id';
	protected $_order_by = 'lv_id';
	protected $_order_by_type = 'DESC';
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
	function get_lv($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->join('{PRE}user', '{PRE}user.ID = {PRE}log_video.lv_iduser', 'LEFT');
		$this->db->join('{PRE}materi', '{PRE}materi.materi_id = {PRE}log_video.lv_idmateri', 'LEFT');
		$this->db->join('{PRE}modul', '{PRE}modul.modul_id = {PRE}materi.materi_modul', 'LEFT');
		$this->db->join('{PRE}kursus', '{PRE}kursus.kursus_id = {PRE}materi.materi_kursus', 'LEFT');
		$this->db->join('{PRE}organisasi', '{PRE}organisasi.organisasi_id = {PRE}kursus.kursus_organisasi', 'LEFT');
		return parent::get_by($where,$limit,$offset,$single,$select);
	}
}