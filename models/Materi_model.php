<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Materi_model extends MY_Model {
	
	protected $_table_name = 'materi';
	protected $_primary_key = 'materi_id';
	protected $_order_by = 'materi_id';
	protected $_order_by_type = 'ASC';
	// protected $_type = 'artikel';

	public $rules_video = array(
		// 'materi_projectid'  => array(
		// 	'field' => 'materi_projectid',
		// 	'label' => 'Project ID',
		// 	'rules' => 'trim|required'
		// ),
		'materi_videoid'  => array(
			'field' => 'materi_videoid',
			'label' => 'Video ID',
			'rules' => 'trim|required'
		),
		// 'materi_duration'  => array(
		// 	'field' => 'materi_projectid',
		// 	'label' => 'Durasi Video',
		// 	'rules' => 'trim|required'
		// ),
		'materi_nama'  => array(
			'field' => 'materi_nama',
			'label' => 'Nama Video',
			'rules' => 'trim|required'
		),
	);


	public $rules_materi = array(
		'materi_modul' => array(
			'field' => 'materi_modul_file',
			'label' => 'Modul',
			'rules' => 'trim|required'
		)
	);

	function __construct() {
		parent::__construct();
	}	


	function get_materi_kursus($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->select('*');
		$this->db->join('{PRE}kursus', '{PRE}kursus.kursus_id  = {PRE}materi.materi_kursus', 'LEFT' );
		$this->db->join('{PRE}modul', '{PRE}modul.modul_id  = {PRE}materi.materi_modul', 'LEFT' );
		$this->db->where($where);
		return parent::get_by($where,$limit,$offset,$single,$select);
	}


	function get_materi_kursus_log($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->select('*');
		$this->db->join('{PRE}kursus', '{PRE}kursus.kursus_id  = {PRE}materi.materi_kursus', 'LEFT' );
		$this->db->join('{PRE}modul', '{PRE}modul.modul_id  = {PRE}materi.materi_modul', 'LEFT' );
		$this->db->join('{PRE}log_materi', '{PRE}log_materi.log_materi_materiid  = {PRE}materi.materi_id', 'LEFT' );
		$this->db->where($where);
		return parent::get_by($where,$limit,$offset,$single,$select);
	}

	function get_statistik_materi($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->join('{PRE}modul', '{PRE}modul.modul_id  = {PRE}materi.materi_modul', 'LEFT' );
		$this->db->join('{PRE}kursus', '{PRE}kursus.kursus_id  = {PRE}materi.materi_kursus', 'LEFT' );
		$this->db->group_by("DATE_FORMAT(materi_created, '%Y%m')");
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