<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Soal_model extends MY_Model {
	
	protected $_table_name = 'soal';
	protected $_primary_key = 'soal_id';
	protected $_order_by = 'soal_id';
	protected $_order_by_type = 'ASC';
	// protected $_type = 'artikel';

	public $rules_kuis = array(
		'soal_materiid' => array(
			'field' => 'soal_materiid', 
			'label' => 'Materi Kuis', 
			'rules' => 'trim|required'
		),
		'soal_soal' => array(
			'field' => 'soal_soal', 
			'label' => 'Soal Kuis', 
			'rules' => 'trim|required'
		),
		'soal_a' => array(
			'field' => 'soal_a', 
			'label' => 'Jawaban A', 
			'rules' => 'trim|required'
		),
		'soal_b' => array(
			'field' => 'soal_b', 
			'label' => 'Jawaban B', 
			'rules' => 'trim|required'
		),
		'soal_c' => array(
			'field' => 'soal_c', 
			'label' => 'Jawaban C', 
			'rules' => 'trim|required'
		),
		'soal_d' => array(
			'field' => 'soal_d', 
			'label' => 'Jawaban D', 
			'rules' => 'trim|required'
		),
		'soal_e' => array(
			'field' => 'soal_e', 
			'label' => 'Jawaban E', 
			'rules' => 'trim|required'
		),
		'soal_jawaban' => array(
			'field' => 'soal_jawaban', 
			'label' => 'Jawaban', 
			'rules' => 'trim|required'
		)
	);	

	public $rules_latihan = array(
		'soal_materiid_lat' => array(
			'field' => 'soal_materiid_lat', 
			'label' => 'Materi Latihan', 
			'rules' => 'trim|required'
		),
		'soal_soal_lat' => array(
			'field' => 'soal_soal_lat', 
			'label' => 'Soal Latihan', 
			'rules' => 'trim|required'
		),
		'soal_a_lat' => array(
			'field' => 'soal_a_lat', 
			'label' => 'Jawaban A', 
			'rules' => 'trim|required'
		),
		'soal_b_lat' => array(
			'field' => 'soal_b_lat', 
			'label' => 'Jawaban B', 
			'rules' => 'trim|required'
		),
		'soal_c_lat' => array(
			'field' => 'soal_c_lat', 
			'label' => 'Jawaban C', 
			'rules' => 'trim|required'
		),
		'soal_d_lat' => array(
			'field' => 'soal_d_lat', 
			'label' => 'Jawaban D', 
			'rules' => 'trim|required'
		),
		'soal_e_lat' => array(
			'field' => 'soal_e_lat', 
			'label' => 'Jawaban E', 
			'rules' => 'trim|required'
		),
		'soal_jawaban_lat' => array(
			'field' => 'soal_jawaban_lat', 
			'label' => 'Jawaban', 
			'rules' => 'trim|required'
		)
	);	

	public $rules_ujian = array(
		'materi_modul_ujian' => array(
			'field' => 'materi_modul_ujian', 
			'label' => 'Modul Ujian', 
			'rules' => 'trim|required'
		),
		'soal_soal_ujian' => array(
			'field' => 'soal_soal_ujian', 
			'label' => 'Soal Ujian', 
			'rules' => 'trim|required'
		),
		'soal_a_ujian' => array(
			'field' => 'soal_a_ujian', 
			'label' => 'Jawaban A', 
			'rules' => 'trim|required'
		),
		'soal_b_ujian' => array(
			'field' => 'soal_b_ujian', 
			'label' => 'Jawaban B', 
			'rules' => 'trim|required'
		),
		'soal_c_ujian' => array(
			'field' => 'soal_c_ujian', 
			'label' => 'Jawaban C', 
			'rules' => 'trim|required'
		),
		'soal_d_ujian' => array(
			'field' => 'soal_d_ujian', 
			'label' => 'Jawaban D', 
			'rules' => 'trim|required'
		),
		'soal_e_ujian' => array(
			'field' => 'soal_e_ujian', 
			'label' => 'Jawaban E', 
			'rules' => 'trim|required'
		),
		'soal_jawaban_ujian' => array(
			'field' => 'soal_jawaban_ujian', 
			'label' => 'Jawaban', 
			'rules' => 'trim|required'
		),
	);	

	function __construct() {
		parent::__construct();
	}	
	
	function get_kuis_kursus($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->select('*');
		$this->db->join('{PRE}materi', '{PRE}materi.materi_id  = {PRE}soal.soal_materiid', 'LEFT' );
		$this->db->where($where);
		return parent::get_by($where,$limit,$offset,$single,$select);
	}
	function get_ujian_kursus($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->select('*');
		$this->db->join('{PRE}modul', '{PRE}modul.modul_id  = {PRE}soal.soal_modulid', 'LEFT' );
		$this->db->where($where);
		return parent::get_by($where,$limit,$offset,$single,$select);
	}
	/* UNTUK MENCARI KUIS YANG BELUM DI KERJAKAN */
	function get_kuis_not_done($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->select('*');
		$this->db->join('{PRE}log_soal', '{PRE}soal.soal_id = {PRE}log_soal.log_soal_soalid', 'LEFT');
		$this->db->where($where);
		return parent::get_by($where,$limit,$offset,$single,$select);
	}

	/* MENGHITUNG LATIHAN YANG ADA */
	function get_latihan($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->select('*');
		// $this->db->group_by('{PRE}soal.soal_materiid');
		$this->db->group_by('{PRE}soal.soal_modulid');
		$this->db->where($where);
		return parent::get_by($where,$limit,$offset,$single,$select);
	}

	// function get_count_latihan($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
	// 	$this->db->select('*');
	// 	$this->db->group_by('{PRE}soal.soal_materiid');
		
	// 	return parent::get_by($where,$limit,$offset,$single,$select);
	// }

	// function get_artikel($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL, $where_in=array(), $where_not_in=array()){
	// 	// $this->db->join('table_name', 'table_name.field = table_name.field')
	// 	if(count($where_in) > 0){
	// 		$this->db->where_in('post_ID', $where_in);
	// 	}
	// 	if(count($where_not_in) > 0){
	// 		$this->db->where_not_in('post_ID', $where_not_in);
	// 	}
	// 	$this->db->join('{PRE}user', '{PRE}'.$this->_table_name.'.post_author = {PRE}user.ID', 'LEFT' );
	// 	$this->db->where('post_type',$this->_type);
	// 	return parent::get_by($where,$limit,$offset,$single,$select);
	// }

	// function get_popular($where = NULL, $limit = NULL){
	// 	$this->_order_by = 'post_counter';
	// 	$this->_order_by_type = 'DESC';
	// 	return parent::get_by($where,$limit);
	// }
	
}