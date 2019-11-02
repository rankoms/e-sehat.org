<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modul_model extends MY_Model {
	
	protected $_table_name = 'modul';
	protected $_primary_key = 'modul_id';
	protected $_order_by = 'modul_id';
	protected $_order_by_type = 'ASC';
	// protected $_type = 'artikel';

	public $rules = array(
		'modul_name'  => array(
			'field' => 'modul_name',
			'label' => 'Nama Modul',
			'rules' => 'trim|required'
		),
		'modul_title'  => array(
			'field' => 'modul_title',
			'label' => 'Title Modul',
			'rules' => 'trim|required'
		),

	);

	// public $rules_related = array(
	// 	'related_organisasi' => array(
	// 		'field' => 'related_organisasi', 
	// 		'label' => 'Title Related', 
	// 		'rules' => 'trim|required'
	// 	)
	// );	
	function __construct() {
		parent::__construct();
	}	

	function get_log_modul($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->select('*');
		$this->db->join('{PRE}log_modul', '{PRE}log_modul.log_modul_modulid  = {PRE}modul.modul_id', 'LEFT' );
		$this->db->where($where);
		return parent::get_by($where,$limit,$offset,$single,$select);
	}


	function get_kursus_modul($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->select('*');
		$this->db->join('{PRE}kursus', '{PRE}kursus.kursus_id  = {PRE}modul.modul_kursus', 'LEFT' );
		$this->db->where($where);
		return parent::get_by($where,$limit,$offset,$single,$select);
	}
	// function get_kursus($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
	// 	$this->db->join('{PRE}organisasi', '{PRE}organisasi.organisasi_id  = {PRE}kursus.kursus_organisasi', 'LEFT' );
	// 	return parent::get_by($where,$limit,$offset,$single,$select);
	// }
	// function get_count_kursus($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
	// 	$this->db->select('count(dk_kursusid) as jumlah, {PRE}kursus.*, {PRE}organisasi.*');
	// 	$this->db->join('{PRE}organisasi', '{PRE}organisasi.organisasi_id  = {PRE}kursus.kursus_organisasi', 'LEFT' );
	// 	$this->db->join('{PRE}log_kursus', '{PRE}log_kursus.dk_kursusid = {PRE}kursus.kursus_id', 'LEFT');
	// 	$this->db->group_by('{PRE}kursus.kursus_id');
	// 	return parent::get_by($where,$limit,$offset,$single,$select);
	// }
}