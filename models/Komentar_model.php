<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Komentar_model extends MY_Model {
	
	protected $_table_name = 'comment';
	protected $_primary_key = 'comment_ID';
	protected $_order_by = 'comment_ID';
	protected $_order_by_type = 'DESC';

	public $rules = array(
		'comment_author_name' => array(
			'field' => 'comment_author_name', 
			'label' => 'Nama', 
			'rules' => 'trim|required'
		),
		'comment_author_email' => array(
			'field' => 'comment_author_email', 
			'label' => 'Email', 
			'rules' => 'trim|required'
		),
		'comment_content' => array(
			'field' => 'comment_content', 
			'label' => 'Komentar', 
			'rules' => 'required'
		),

	);	

	function __construct() {
		parent::__construct();
	}	

	function get_komentar($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		// $this->db->join('table_name', 'table_name.field = table_name.field')
		$this->db->join('{PRE}user', '{PRE}'.$this->_table_name.'.comment_author = {PRE}user.ID', 'LEFT' );	
		$this->db->join('{PRE}materi', '{PRE}materi.materi_id = {PRE}comment.comment_post_ID', 'LEFT');
		$this->db->join('{PRE}kursus', '{PRE}kursus.kursus_id = {PRE}materi.materi_kursus', 'LEFT');	
		return parent::get_by($where,$limit,$offset,$single,$select);
	}	
	
	
	// function get_user_murid($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
	// 	$this->db->join('{PRE}organisasi', '{PRE}organisasi.organisasi_id = {PRE}user.user_organisasi', 'LEFT');
	// 	$this->db->join('{PRE}profesi', '{PRE}profesi.profesi_id = {PRE}organisasi.organisasi_profesi', 'LEFT');
	// 	return parent::get_by($where,$limit,$offset,$single,$select);
	// }
}