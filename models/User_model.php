<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model {
	
	protected $_table_name = 'user';
	protected $_primary_key = 'ID';
	protected $_order_by = 'ID';
	protected $_order_by_type = 'DESC';

	public $rules = array(
		'username' => array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required'
		), 
		'password' => array(
			'field' => 'password', 
			'label' => 'Password', 
			'rules' => 'trim|required|callback_password_check'
		),
	);	

	public $rules_register = array(
		'username' => array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|callback_username_check'
		), 
		'password' => array(
			'field' => 'password', 
			'label' => 'Password', 
			'rules' => 'trim|required|min_length[5]'
		),
		'email' => array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email|callback_email_check'
		), 
		'nama' => array(
            'field' => 'nama',
            'label' => 'nama',
            'rules' => 'trim|required'
		), 
	);

	/* MURID */
	public $rules_register_sertifikat = array(
		'username' => array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|callback_username_check'
		), 
		'password' => array(
			'field' => 'password', 
			'label' => 'Password', 
			'rules' => 'trim|required|min_length[5]'
		),
		'email' => array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email|callback_email_check'
		), 
		'nama' => array(
            'field' => 'nama',
            'label' => 'nama',
            'rules' => 'trim|required'
		), 
		'user_str' => array(
            'field' => 'user_str',
            'label' => 'STR',
            'rules' => 'trim|required|numeric'
		), 
		'user_nik' => array(
            'field' => 'user_nik',
            'label' => 'NIK KTP',
            'rules' => 'trim|required|numeric|min_length[16]|max_length[16]'
		), 
		'repassword' => array(
            'field' => 'repassword',
            'label' => 'Password',
            'rules' => 'trim|required|matches[password]'
		), 
		'user_type' => array(
            'field' => 'user_type',
            'label' => 'User Type',
            'rules' => 'trim|required'
		),
		'user_organisasi' => array(
            'field' => 'user_organisasi',
            'label' => 'User Organisasi',
            'rules' => 'trim|required'
		),
	);
	public $rules_register_belajar = array(
		'username' => array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|callback_username_check'
		), 
		'password' => array(
			'field' => 'password', 
			'label' => 'Password', 
			'rules' => 'trim|required|min_length[5]'
		),
		'email' => array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email|callback_email_check'
		), 
		'nama' => array(
            'field' => 'nama',
            'label' => 'nama',
            'rules' => 'trim|required'
		), 
		'repassword' => array(
            'field' => 'repassword',
            'label' => 'Password',
            'rules' => 'trim|required|matches[password]'
		), 
		'user_type' => array(
            'field' => 'user_type',
            'label' => 'User Type',
            'rules' => 'trim|required'
		),
		'user_organisasi' => array(
            'field' => 'user_organisasi',
            'label' => 'User Organisasi',
            'rules' => 'trim|required'
		),
	);

	public $rules_update_sertifikat = array(
		'nama' => array(
            'field' => 'nama',
            'label' => 'nama',
            'rules' => 'trim|required'
		), 
		'user_str' => array(
            'field' => 'user_str',
            'label' => 'STR',
            'rules' => 'trim|required|numeric'
		), 
		'user_nik' => array(
            'field' => 'user_nik',
            'label' => 'NIK',
            'rules' => 'trim|required|numeric|min_length[16]|max_length[16]'
		), 
		'user_type' => array(
            'field' => 'user_type',
            'label' => 'User Type',
            'rules' => 'trim|required'
		),
		'user_organisasi' => array(
            'field' => 'user_organisasi',
            'label' => 'User Organisasi',
            'rules' => 'trim|required'
		),
	);

	public $rules_update_belajar = array(

		'nama' => array(
            'field' => 'nama',
            'label' => 'nama',
            'rules' => 'trim|required'
		), 
		'user_type' => array(
            'field' => 'user_type',
            'label' => 'User Type',
            'rules' => 'trim|required'
		),
		'user_organisasi' => array(
            'field' => 'user_organisasi',
            'label' => 'User Organisasi',
            'rules' => 'trim|required'
		),
	);

	/* END MURID */

	public $rules_update = array(
		'username' => array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required'
		), 
		'email' => array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email'
		), 
	);

	public $reset_rules = array(
		'password' => array(
			'field' => 'password', 
			'label' => 'Password', 
			'rules' => 'trim|required|min_length[5]'
		),

		'repassword' => array(
            'field' => 'repassword',
            'label' => 'Password',
            'rules' => 'trim|required|matches[password]'
		), 
	);


	public $informasi_dasar = array(
		'nama' => array(
			'field' => 'nama', 
			'label' => 'nama', 
			'rules' => 'trim|required'
		),

		'email' => array(
            'field' => 'email',
            'label' => 'Password',
            'rules' => 'trim|required|valid_email'
		), 
	);

	public $ubah_password = array(
		'password' => array(
			'field' => 'password', 
			'label' => 'password', 
			'rules' => 'trim|required|min_length[5]|callback_password_check'
		),
		'newpassword' => array(
			'field' => 'newpassword', 
			'label' => 'password', 
			'rules' => 'trim|required|min_length[5]'
		),
		'renewpassword' => array(
			'field' => 'renewpassword', 
			'label' => 'password', 
			'rules' => 'trim|required|min_length[5]|matches[newpassword]'
		)
	);
	function __construct() {
		parent::__construct();
	}	
	
	function get_user_login($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL, $username = NULL){
		// $this->db->query("select * from tbl_user where (username = '$username' or email = '$username') and active = 1");
		$this->db->where('username', $username);
		$this->db->or_where('email', $username);
		$this->db->where('active', 1);
		return parent::get_by($where,$limit,$offset,$single,$select);
	}


	function get_user($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->join('{PRE}post', '{PRE}user.ID  = {PRE}post.post_author', 'LEFT' );
		$this->db->group_by('{PRE}user.ID');
		return parent::get_by($where,$limit,$offset,$single,$select);
	}
	function get_user_murid($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->join('{PRE}organisasi', '{PRE}organisasi.organisasi_id = {PRE}user.user_organisasi', 'LEFT');
		$this->db->join('{PRE}profesi', '{PRE}profesi.profesi_id = {PRE}organisasi.organisasi_profesi', 'LEFT');
		return parent::get_by($where,$limit,$offset,$single,$select);
	}
	function get_user_adminop($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->join('{PRE}organisasi', '{PRE}organisasi.organisasi_id = {PRE}user.user_organisasi', 'LEFT');
		$this->db->join('{PRE}profesi', '{PRE}profesi.profesi_id = {PRE}organisasi.organisasi_profesi', 'LEFT');
		// $this->db->where('{PRE}user.group', 'adminop');
		return parent::get_by($where,$limit,$offset,$single,$select);
	}
	function get_user_instruktur($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->join('{PRE}organisasi', '{PRE}organisasi.organisasi_id = {PRE}user.user_organisasi', 'LEFT');
		$this->db->join('{PRE}profesi', '{PRE}profesi.profesi_id = {PRE}organisasi.organisasi_profesi', 'LEFT');
		// $this->db->where('{PRE}user.group', 'instruktur');
		return parent::get_by($where,$limit,$offset,$single,$select);
	}

	function get_user_detail($id=NULL){
		$this->db->select('{PRE}user.*');
		$this->db->join('{PRE}user_detail', '{PRE}user.ID  = {PRE}user_detail.user_id', 'LEFT' );
		return parent::get($id);
	}

	function get_dashboard_user($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
		$this->db->group_by("DATE_FORMAT(created_on, '%Y%m')");
		return parent::get_by($where,$limit,$offset,$single,$select);
	}

	function get_count_user($where = NULL, $limit = NULL, $offset= NULL, $single=FALSE, $select=NULL){
	

	return parent::get_by($where,$limit,$offset,$single,$select);
	}

}