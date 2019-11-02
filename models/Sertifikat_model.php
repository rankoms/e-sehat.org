<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sertifikat_model extends MY_Model {
	
	protected $_table_name = 'sertifikat';
	protected $_primary_key = 'sertifikat_id';
	protected $_order_by = 'sertifikat_id';
	protected $_order_by_type = 'DESC';
	// protected $_type = 'artikel';

	public $rules = array(
		'sertifikat_kursus' => array(
			'field' => 'sertifikat_kursus', 
			'label' => 'Organisasi', 
			'rules' => 'trim|required'
		)
	);	

	function __construct() {
		parent::__construct();
	}	
	function get_sertifikat($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->join('{PRE}kursus', '{PRE}kursus.kursus_id  = {PRE}sertifikat.sertifikat_kursus', 'LEFT' );
		$this->db->join('{PRE}organisasi', '{PRE}organisasi.organisasi_id  = {PRE}kursus.kursus_organisasi', 'LEFT' );
		return parent::get_by($where,$limit,$offset,$single,$select);
	}
	function get_log_sertifikat($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->join('{PRE}log_sertifikat', '{PRE}log_sertifikat.log_ser_kursusid  = {PRE}sertifikat.sertifikat_kursus', 'LEFT' );
		$this->db->join('{PRE}kursus', '{PRE}kursus.kursus_id  = {PRE}sertifikat.sertifikat_kursus', 'LEFT' );
		$this->db->join('{PRE}organisasi', '{PRE}organisasi.organisasi_id  = {PRE}kursus.kursus_organisasi', 'LEFT' );
		$this->db->join('{PRE}user', '{PRE}user.ID  = {PRE}log_sertifikat.log_ser_userid', 'LEFT' );
		return parent::get_by($where,$limit,$offset,$single,$select);
	}
	
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