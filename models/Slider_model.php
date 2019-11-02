<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Slider_model extends MY_Model {
	
	protected $_table_name = 'slider';
	protected $_primary_key = 'slider_id';
	protected $_order_by = 'slider_id';
	protected $_order_by_type = 'ASC';
	// protected $_type = 'artikel';

	public $rules = array(
		'slider_title' => array(
			'field' => 'slider_title', 
			'label' => 'Title Slider', 
			'rules' => 'trim'
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

	function get_related($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->join('{PRE}organisasi', '{PRE}organisasi.organisasi_id  = {PRE}slider.slider_organisasi', 'LEFT' );
		$this->db->where('{PRE}slider.slider_type', 'related');
		return parent::get_by($where,$limit,$offset,$single,$select);
	}
}