<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_model extends MY_Model {
	
	protected $_table_name = 'transaction';
	protected $_primary_key = 'transaction_id';
	protected $_order_by = '{PRE}transaction.transaction_id';
	protected $_order_by_type = 'DESC';

	function __construct() {
		parent::__construct();
	}	

	function count($where = NULL){
		$this->db->join('{PRE}shipping', '{PRE}'.$this->_table_name.'.transaction_id = {PRE}shipping.transaction_id', 'LEFT' );		
		return parent::count($where);
	}

	function get_transaksi($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL, $where_in=array(), $where_not_in=array()){
		// $this->db->join('table_name', 'table_name.field = table_name.field')		
		$this->db->join('{PRE}shipping', '{PRE}'.$this->_table_name.'.transaction_id = {PRE}shipping.transaction_id', 'LEFT' );		
		return parent::get_by($where,$limit,$offset,$single,$select);
	}	

}