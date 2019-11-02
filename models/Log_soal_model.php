<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Log_soal_model extends MY_Model {
	
	protected $_table_name = 'log_soal';
	protected $_primary_key = 'log_soal_id';
	protected $_order_by = 'log_soal_id';
	protected $_order_by_type = 'DESC';
	// protected $_type = 'artikel';

	// public $rules_kuis = array(
	// 	'soal_materiid' => array(
	// 		'field' => 'soal_materiid', 
	// 		'label' => 'Judul Artikel', 
	// 		'rules' => 'trim|required'
	// 	)
	// );	

	function __construct() {
		parent::__construct();
	}	
	
	// function get_kuis_kursus($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
	// 	$this->db->select('*');
	// 	$this->db->join('{PRE}materi', '{PRE}materi.materi_id  = {PRE}soal.soal_materiid', 'LEFT' );
	// 	$this->db->where($where);
	// 	return parent::get_by($where,$limit,$offset,$single,$select);
	// }
	//  UNTUK MENCARI KUIS YANG BELUM DI KERJAKAN 
	// function get_kuis_not_done($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
	// 	$this->db->select('*');
	// 	$this->db->join('{PRE}log_soal', '{PRE}soal.soal_id = {PRE}log_soal.log_soal_soalid', 'LEFT');
	// 	$this->db->where($where);
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