<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kursus_model extends MY_Model {
	
	protected $_table_name = 'kursus';
	protected $_primary_key = 'kursus_id';
	protected $_order_by = 'kursus_id';
	protected $_order_by_type = 'DESC';
	// protected $_type = 'artikel';

	public $rules = array(
		'kursus_nama' => array(
			'field' => 'kursus_nama', 
			'label' => 'Kursus Nama', 
			'rules' => 'trim|required'
		)
	);	


	public $rules_related = array(
		'related_organisasi' => array(
			'field' => 'related_organisasi', 
			'label' => 'Title Related', 
			'rules' => 'trim|required'
		)
	);	
	function __construct() {
		parent::__construct();
	}	

	function get_kursus($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->join('{PRE}organisasi', '{PRE}organisasi.organisasi_id  = {PRE}kursus.kursus_organisasi', 'LEFT' );
		// $this->db->join('{PRE}profesi', '{PRE}profesi.profesi_id  = {PRE}organisasi.organisasi_profesi', 'LEFT' );
		$this->db->join('{PRE}sertifikat', '{PRE}sertifikat.sertifikat_kursus  = {PRE}kursus.kursus_id', 'LEFT' );
		return parent::get_by($where,$limit,$offset,$single,$select);
	}
	function get_count_kursus($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->select('count(dk_kursusid) as jumlah, {PRE}kursus.*, {PRE}organisasi.*');
		$this->db->join('{PRE}organisasi', '{PRE}organisasi.organisasi_id  = {PRE}kursus.kursus_organisasi', 'LEFT' );
		$this->db->join('{PRE}log_kursus', '{PRE}log_kursus.dk_kursusid = {PRE}kursus.kursus_id', 'LEFT');
		$this->db->group_by('{PRE}kursus.kursus_id');
		return parent::get_by($where,$limit,$offset,$single,$select);
	}
	function get_row_count_kursus($where = NULL){
		$this->db->select('*');
		$this->db->join('{PRE}organisasi', '{PRE}organisasi.organisasi_id  = {PRE}kursus.kursus_organisasi', 'LEFT' );
		$this->db->join('{PRE}log_kursus', '{PRE}log_kursus.dk_kursusid = {PRE}kursus.kursus_id', 'LEFT');
		// $q->num_rows();
		return parent::count($where);
		// return parent::get_by($where,$limit,$offset,$single,$select);
	}

	function get_dashboard_kursus($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->join('{PRE}organisasi', '{PRE}organisasi.organisasi_id  = {PRE}kursus.kursus_organisasi', 'LEFT' );
		$this->db->join('{PRE}log_kursus', '{PRE}log_kursus.dk_kursusid  = {PRE}kursus.kursus_id', 'LEFT' );
		$this->db->group_by('{PRE}kursus.kursus_id');
		return parent::get_by($where,$limit,$offset,$single,$select);
	}
}