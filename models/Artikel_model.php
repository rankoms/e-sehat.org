<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Artikel_model extends MY_Model {
	
	protected $_table_name = 'post';
	protected $_primary_key = 'post_ID';
	protected $_order_by = 'post_ID';
	protected $_order_by_type = 'DESC';
	protected $_type = 'artikel';

	public $rules = array(
		'post_title' => array(
			'field' => 'post_title', 
			'label' => 'Judul Artikel', 
			'rules' => 'trim|required'
		)
	);	

	function __construct() {
		parent::__construct();
	}	
	
	function get_artikel($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL, $where_in=array(), $where_not_in=array()){
		// $this->db->join('table_name', 'table_name.field = table_name.field')
		if(count($where_in) > 0){
			$this->db->where_in('post_ID', $where_in);
		}
		if(count($where_not_in) > 0){
			$this->db->where_not_in('post_ID', $where_not_in);
		}
		$this->db->join('{PRE}user', '{PRE}'.$this->_table_name.'.post_author = {PRE}user.ID', 'LEFT' );
		$this->db->where('post_type',$this->_type);
		return parent::get_by($where,$limit,$offset,$single,$select);
	}

	function get_popular($where = NULL, $limit = NULL){
		$this->_order_by = 'post_counter';
		$this->_order_by_type = 'DESC';
		return parent::get_by($where,$limit);
	}
	
}