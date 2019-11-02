<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Backend_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$data = array();
		$this->site->is_url_admin();
		if(get_user_info('group') == 'admin'){
			$data['record'] = $this->db->query("SELECT organisasi_nama, organisasi_id, 
				count(jumlah_kursus) AS jumlah_kursus,
				SUM(jumlah_peserta) AS jumlah_peserta,
				SUM(jumlah_video) AS jumlah_video,
				SUM(jumlah_file) AS jumlah_file,
				SUM(jumlah_ujian) AS jumlah_ujian,
				SUM(jumlah_latihan) AS jumlah_latihan,
				SUM(jumlah_user) AS jumlah_user
				 FROM (
				select a.organisasi_nama, a.organisasi_id,b.kursus_id jumlah_kursus,
				(select count(*) from tbl_log_kursus x where x.dk_kursusid = b.kursus_id) as jumlah_peserta,
				(select sum(case when materi_type = 'video' then 1 else 0 end) video from tbl_materi yx where b.kursus_id = yx.materi_kursus) as jumlah_video,
				(select sum(case when materi_type = 'file' then 1 else 0 end) file from tbl_materi yz where b.kursus_id = yz.materi_kursus) as jumlah_file,
				(select sum(case when soal_type = 'ujian' then 1 else 0 end) ujian from tbl_soal zx where b.kursus_id = zx.soal_kursusid) as jumlah_ujian,
				(select sum(case when soal_type = 'latihan' then 1 else 0 end) latihan from tbl_soal zy where b.kursus_id = zy.soal_kursusid) as jumlah_latihan,
				(select sum(case when `group` = 'murid' then 1 else 0 end) user from tbl_user z where z.user_organisasi = a.organisasi_id and z.status = 'new') as jumlah_user
				from tbl_organisasi a
				left join
				tbl_kursus b on a.organisasi_id = b.kursus_organisasi
				) x
				GROUP BY organisasi_nama, organisasi_id")->result();
		}
		else{
			$data['record'] = $this->db->query("SELECT organisasi_nama, organisasi_id, 
				count(jumlah_kursus) AS jumlah_kursus,
				SUM(jumlah_peserta) AS jumlah_peserta,
				SUM(jumlah_video) AS jumlah_video,
				SUM(jumlah_file) AS jumlah_file,
				SUM(jumlah_ujian) AS jumlah_ujian,
				SUM(jumlah_latihan) AS jumlah_latihan,
				SUM(jumlah_user) AS jumlah_user
				 FROM (
				select a.organisasi_nama, a.organisasi_id,b.kursus_id jumlah_kursus,
				(select count(*) from tbl_log_kursus x where x.dk_kursusid = b.kursus_id) as jumlah_peserta,
				(select sum(case when materi_type = 'video' then 1 else 0 end) video from tbl_materi yx where b.kursus_id = yx.materi_kursus) as jumlah_video,
				(select sum(case when materi_type = 'file' then 1 else 0 end) file from tbl_materi yz where b.kursus_id = yz.materi_kursus) as jumlah_file,
				(select sum(case when soal_type = 'ujian' then 1 else 0 end) ujian from tbl_soal zx where b.kursus_id = zx.soal_kursusid) as jumlah_ujian,
				(select sum(case when soal_type = 'latihan' then 1 else 0 end) latihan from tbl_soal zy where b.kursus_id = zy.soal_kursusid) as jumlah_latihan,
				(select sum(case when `group` = 'murid' then 1 else 0 end) user from tbl_user z where z.user_organisasi = a.organisasi_id and z.status = 'new') as jumlah_user
				from tbl_organisasi a
				left join
				tbl_kursus b on a.organisasi_id = b.kursus_organisasi

				where organisasi_id = ".get_user_info('user_organisasi')."
				) x
				GROUP BY organisasi_nama, organisasi_id")->result();
			}
	
		$this->site->view('index', $data);
	}

	public function action($param){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if($param=='ambil'){
				$this->load->model(array('Komentar_model', 'Artikel_model'));
				echo json_encode(array(
						'record_komentar' => $this->Komentar_model->get_by(NULL, 5),
						'record_artikel' => $this->Artikel_model->get_by(array('post_type' => 'artikel'), 3)
					));
			}
		}
	}	
	
	public function arsip($param){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		}
		else{
			$data = array();
			$this->site->view('arsip', $data);
		}

	}
	public function dashboard($param){
		$post = $this->input->post(NULL,TRUE);
		if($param == 'user'){
			$organisasi = $post['organisasi'];
			$tgl = $post['tgl'];
			if($organisasi){

				$data = $this->db->query("SELECT COUNT(*) as jumlah, b.bulan as bulantahun
				FROM tbl_user a
				JOIN (SELECT DISTINCT DATE_FORMAT(created_on, '%Y%m') AS bulan FROM tbl_user WHERE user_organisasi = $organisasi) b
				ON DATE_FORMAT(a.created_on, '%Y%m') <= b.bulan
				WHERE a.user_organisasi = $organisasi and a.group = 'murid'
				AND status = 'new' GROUP BY b.bulan")->result();
			}
			else{
				$data = $this->db->query("SELECT COUNT(*) as jumlah, b.bulan as bulantahun FROM tbl_user a JOIN 
				(SELECT DISTINCT DATE_FORMAT(created_on, '%Y%m') AS bulan FROM tbl_user) b 
				ON DATE_FORMAT(a.created_on, '%Y%m') <= b.bulan 
				WHERE a.group = 'murid' AND status = 'new' GROUP BY b.bulan")->result();
			}
			echo json_encode(array('data'=> $data, 'query'=>$this->db->last_query()));
		}
		else if($param == 'materi'){
			$organisasi = $post['organisasi'];
			$tgl = $post['tgl'];
			if($organisasi){

				$data = $this->db->query("SELECT COUNT(*) as jumlah, b.bulan as bulantahun 
				from view_materi a 
				join (SELECT DISTINCT DATE_FORMAT(materi_created, '%Y%m') AS bulan from view_materi where kursus_organisasi = $organisasi) b
				on DATE_FORMAT(a.materi_created, '%Y%m')<=b.bulan
				WHERE a.kursus_organisasi = $organisasi 
				group by b.bulan")->result();
			}
			else{
				$data = $this->db->query("SELECT COUNT(*) as jumlah, b.bulan as bulantahun  from view_materi a  
				join (SELECT DISTINCT DATE_FORMAT(materi_created, '%Y%m') AS bulan from view_materi) b on DATE_FORMAT(a.materi_created, '%Y%m')<=b.bulan 
				 group by b.bulan")->result();
			}
			echo json_encode(array('data'=> $data, 'query'=>$this->db->last_query()));
		}
		else if($param == 'sertifikat'){
			$organisasi = $post['organisasi'];
			$tgl = $post['tgl'];

			if($organisasi){

				$data = $this->db->query("SELECT COUNT(*) as jumlah, b.bulan as bulantahun
				FROM tbl_user a
				JOIN (SELECT DISTINCT DATE_FORMAT(created_on, '%Y%m') AS bulan FROM tbl_user WHERE user_organisasi = $organisasi) b
				ON DATE_FORMAT(a.created_on, '%Y%m') <= b.bulan
				WHERE a.user_organisasi = $organisasi and a.group = 'murid' and user_type = 'SERTIFIKAT'
				AND status = 'new' GROUP BY b.bulan")->result();
			}
			else{
				$data = $this->db->query("SELECT COUNT(*) as jumlah, b.bulan as bulantahun FROM tbl_user a JOIN (SELECT DISTINCT 
				DATE_FORMAT(created_on, '%Y%m') AS bulan FROM tbl_user) b 
				ON DATE_FORMAT(a.created_on, '%Y%m') <= b.bulan
				 WHERE a.group = 'murid' and user_type = 'SERTIFIKAT' AND status = 'new' GROUP BY b.bulan")->result();
			}
			// echo $this->db->last_query();
			// print_r($data);
			echo json_encode(array('data'=> $data, 'query'=>$this->db->last_query()));
		}
		else if($param == 'belajar'){
			$organisasi = $post['organisasi'];
			$tgl = $post['tgl'];
			if($organisasi){
				$data = $this->db->query("SELECT COUNT(*) as jumlah, b.bulan as bulantahun
				FROM tbl_user a
				JOIN (SELECT DISTINCT DATE_FORMAT(created_on, '%Y%m') AS bulan FROM tbl_user WHERE user_organisasi = $organisasi) b
				ON DATE_FORMAT(a.created_on, '%Y%m') <= b.bulan
				WHERE a.user_organisasi = $organisasi and a.group = 'murid' and user_type = 'BELAJAR'
				AND status = 'new' GROUP BY b.bulan")->result();
			}
			else{
				$data = $this->db->query("SELECT COUNT(*) as jumlah, b.bulan as bulantahun FROM tbl_user a JOIN (SELECT DISTINCT 
				DATE_FORMAT(created_on, '%Y%m') AS bulan FROM tbl_user) b ON DATE_FORMAT(a.created_on, '%Y%m') <= b.bulan 
				WHERE a.group = 'murid' and user_type = 'BELAJAR' AND status = 'new' GROUP BY b.bulan")->result();
			}
			// echo $this->db->last_query();
			// print_r($data);
			echo json_encode(array('data'=> $data, 'query'=>$this->db->last_query()));
		}
		else if($param == 'organisasi'){
			$data = $this->db->query("select organisasi_id, organisasi_nama from tbl_organisasi")->result();
			echo json_encode(array('data'=>$data, 'query'=> $this->db->last_query()));
		}

		/* TAMBAHAN LIKO */

		else if($param == 'user2'){
			$organisasi = $post['organisasi'];
			$tgl = $post['tgl'];
			if($organisasi){

				$data = $this->db->query("select count(*) as jumlah, DATE_FORMAT(a.created_on, '%Y%m') as bulantahun from tbl_user a
					WHERE a.user_organisasi = $organisasi and `group` = 'murid' AND status = 'new' GROUP BY DATE_FORMAT(a.created_on, '%Y%m')")->result();
			}
			else{
				$data = $this->db->query("select count(*) as jumlah, DATE_FORMAT(a.created_on, '%Y%m') as bulantahun from tbl_user a
					WHERE `group` = 'murid' AND status = 'new' GROUP BY DATE_FORMAT(a.created_on, '%Y%m')")->result();
			}
			echo json_encode(array('data'=> $data, 'query'=>$this->db->last_query()));

		}
		else if($param == 'materi2'){
			$organisasi = $post['organisasi'];
			$tgl = $post['tgl'];
			if($organisasi){

				$data = $this->db->query("select count(*) as jumlah, DATE_FORMAT(materi_created, '%Y%m') as bulantahun from view_materi a where kursus_organisasi = $organisasi group by DATE_FORMAT(materi_created, '%Y%m') order by 2")->result();
			}
			else{
				$data = $this->db->query("select count(*) as jumlah, DATE_FORMAT(materi_created, '%Y%m') as bulantahun from view_materi a group by DATE_FORMAT(materi_created, '%Y%m') order by 2")->result();
			}
			echo json_encode(array('data'=> $data, 'query'=>$this->db->last_query()));
		}
		else if($param == 'sertifikat2'){
			$organisasi = $post['organisasi'];
			$tgl = $post['tgl'];

			if($organisasi){

				$data = $this->db->query("select count(*) as jumlah, DATE_FORMAT(a.created_on, '%Y%m') as bulantahun from tbl_user a
					WHERE a.user_organisasi = $organisasi and `group` = 'murid' and user_type = 'SERTIFIKAT' AND status = 'new' GROUP BY DATE_FORMAT(a.created_on, '%Y%m')")->result();
			}
			else{
				$data = $this->db->query("select count(*) as jumlah, DATE_FORMAT(a.created_on, '%Y%m') as bulantahun from tbl_user a
					WHERE `group` = 'murid' and user_type = 'SERTIFIKAT' AND status = 'new' GROUP BY DATE_FORMAT(a.created_on, '%Y%m')")->result();
			}
			echo json_encode(array('data'=> $data, 'query'=>$this->db->last_query()));			
		}
		else if($param == 'belajar2'){
			$organisasi = $post['organisasi'];
			$tgl = $post['tgl'];

			if($organisasi){

				$data = $this->db->query("select count(*) as jumlah, DATE_FORMAT(a.created_on, '%Y%m') as bulantahun from tbl_user a
					WHERE a.user_organisasi = $organisasi and `group` = 'murid' and user_type = 'BELAJAR' AND status = 'new' GROUP BY DATE_FORMAT(a.created_on, '%Y%m')")->result();
			}
			else{
				$data = $this->db->query("select count(*) as jumlah, DATE_FORMAT(a.created_on, '%Y%m') as bulantahun from tbl_user a
					WHERE `group` = 'murid' and user_type = 'BELAJAR' AND status = 'new' GROUP BY DATE_FORMAT(a.created_on, '%Y%m')")->result();
			}
			echo json_encode(array('data'=> $data, 'query'=>$this->db->last_query()));			
		}

		/* NEW TAMBAHAN LIKO */


		if($param == 'user3'){
			$organisasi = $post['organisasi3'];
			$tgl = $post['tgl'];
			if($organisasi){

				$data = $this->db->query("SELECT COUNT(*) as jumlah, b.bulan as bulantahun
				FROM tbl_user a
				JOIN (SELECT DISTINCT DATE_FORMAT(created_on, '%Y%m') AS bulan FROM tbl_user WHERE user_organisasi = $organisasi) b
				ON DATE_FORMAT(a.created_on, '%Y%m') <= b.bulan
				WHERE a.user_organisasi = $organisasi and a.group = 'murid'
				AND status = 'arsip' GROUP BY b.bulan")->result();
			}
			else{
				$data = $this->db->query("SELECT COUNT(*) as jumlah, b.bulan as bulantahun FROM tbl_user a JOIN 
				(SELECT DISTINCT DATE_FORMAT(created_on, '%Y%m') AS bulan FROM tbl_user) b 
				ON DATE_FORMAT(a.created_on, '%Y%m') <= b.bulan 
				WHERE a.group = 'murid' AND status = 'arsip' GROUP BY b.bulan")->result();
			}
			echo json_encode(array('data'=> $data, 'query'=>$this->db->last_query()));
		}
		else if($param == 'materi3'){
			$organisasi = $post['organisasi'];
			$tgl = $post['tgl'];
			if($organisasi){

				$data = $this->db->query("SELECT COUNT(*) as jumlah, b.bulan as bulantahun 
				from view_materi a 
				join (SELECT DISTINCT DATE_FORMAT(materi_created, '%Y%m') AS bulan from view_materi where kursus_organisasi = $organisasi) b
				on DATE_FORMAT(a.materi_created, '%Y%m')<=b.bulan
				WHERE a.kursus_organisasi = $organisasi 
				group by b.bulan")->result();
			}
			else{
				$data = $this->db->query("SELECT COUNT(*) as jumlah, b.bulan as bulantahun  from view_materi a  
				join (SELECT DISTINCT DATE_FORMAT(materi_created, '%Y%m') AS bulan from view_materi) b on DATE_FORMAT(a.materi_created, '%Y%m')<=b.bulan 
				 group by b.bulan")->result();
			}
			echo json_encode(array('data'=> $data, 'query'=>$this->db->last_query()));
		}
		else if($param == 'sertifikat3'){
			$organisasi = $post['organisasi'];
			$tgl = $post['tgl'];

			if($organisasi){

				$data = $this->db->query("SELECT COUNT(*) as jumlah, b.bulan as bulantahun
				FROM tbl_user a
				JOIN (SELECT DISTINCT DATE_FORMAT(created_on, '%Y%m') AS bulan FROM tbl_user WHERE user_organisasi = $organisasi) b
				ON DATE_FORMAT(a.created_on, '%Y%m') <= b.bulan
				WHERE a.user_organisasi = $organisasi and a.group = 'murid' and user_type = 'SERTIFIKAT'
				AND status = 'arsip' GROUP BY b.bulan")->result();
			}
			else{
				$data = $this->db->query("SELECT COUNT(*) as jumlah, b.bulan as bulantahun FROM tbl_user a JOIN (SELECT DISTINCT 
				DATE_FORMAT(created_on, '%Y%m') AS bulan FROM tbl_user) b 
				ON DATE_FORMAT(a.created_on, '%Y%m') <= b.bulan
				 WHERE a.group = 'murid' and user_type = 'SERTIFIKAT' AND status = 'arsip' GROUP BY b.bulan")->result();
			}
			// echo $this->db->last_query();
			// print_r($data);
			echo json_encode(array('data'=> $data, 'query'=>$this->db->last_query()));
		}
		else if($param == 'belajar3'){
			$organisasi = $post['organisasi'];
			$tgl = $post['tgl'];
			if($organisasi){
				$data = $this->db->query("SELECT COUNT(*) as jumlah, b.bulan as bulantahun
				FROM tbl_user a
				JOIN (SELECT DISTINCT DATE_FORMAT(created_on, '%Y%m') AS bulan FROM tbl_user WHERE user_organisasi = $organisasi) b
				ON DATE_FORMAT(a.created_on, '%Y%m') <= b.bulan
				WHERE a.user_organisasi = $organisasi and a.group = 'murid' and user_type = 'BELAJAR'
				AND status = 'arsip' GROUP BY b.bulan")->result();
			}
			else{
				$data = $this->db->query("SELECT COUNT(*) as jumlah, b.bulan as bulantahun FROM tbl_user a JOIN (SELECT DISTINCT 
				DATE_FORMAT(created_on, '%Y%m') AS bulan FROM tbl_user) b ON DATE_FORMAT(a.created_on, '%Y%m') <= b.bulan 
				WHERE a.group = 'murid' and user_type = 'BELAJAR' AND status = 'arsip' GROUP BY b.bulan")->result();
			}
			// echo $this->db->last_query();
			// print_r($data);
			echo json_encode(array('data'=> $data, 'query'=>$this->db->last_query()));
		}

		/* TAMBAHAN LIKO */

		else if($param == 'user4'){
			$organisasi = $post['organisasi'];
			$tgl = $post['tgl'];
			if($organisasi){

				$data = $this->db->query("select count(*) as jumlah, DATE_FORMAT(a.created_on, '%Y%m') as bulantahun from tbl_user a
					WHERE a.user_organisasi = $organisasi and `group` = 'murid' AND status = 'arsip' GROUP BY DATE_FORMAT(a.created_on, '%Y%m')")->result();
			}
			else{
				$data = $this->db->query("select count(*) as jumlah, DATE_FORMAT(a.created_on, '%Y%m') as bulantahun from tbl_user a
					WHERE `group` = 'murid' AND status = 'arsip' GROUP BY DATE_FORMAT(a.created_on, '%Y%m')")->result();
			}
			echo json_encode(array('data'=> $data, 'query'=>$this->db->last_query()));

		}
		else if($param == 'materi4'){
			$organisasi = $post['organisasi'];
			$tgl = $post['tgl'];
			if($organisasi){

				$data = $this->db->query("select count(*) as jumlah, DATE_FORMAT(materi_created, '%Y%m') as bulantahun from view_materi a where kursus_organisasi = $organisasi group by DATE_FORMAT(materi_created, '%Y%m') order by 2")->result();
			}
			else{
				$data = $this->db->query("select count(*) as jumlah, DATE_FORMAT(materi_created, '%Y%m') as bulantahun from view_materi a group by DATE_FORMAT(materi_created, '%Y%m') order by 2")->result();
			}
			echo json_encode(array('data'=> $data, 'query'=>$this->db->last_query()));
		}
		else if($param == 'sertifikat4'){
			$organisasi = $post['organisasi'];
			$tgl = $post['tgl'];

			if($organisasi){

				$data = $this->db->query("select count(*) as jumlah, DATE_FORMAT(a.created_on, '%Y%m') as bulantahun from tbl_user a
					WHERE a.user_organisasi = $organisasi and `group` = 'murid' and user_type = 'SERTIFIKAT' AND status = 'arsip' GROUP BY DATE_FORMAT(a.created_on, '%Y%m')")->result();
			}
			else{
				$data = $this->db->query("select count(*) as jumlah, DATE_FORMAT(a.created_on, '%Y%m') as bulantahun from tbl_user a
					WHERE `group` = 'murid' and user_type = 'SERTIFIKAT' AND status = 'arsip' GROUP BY DATE_FORMAT(a.created_on, '%Y%m')")->result();
			}
			echo json_encode(array('data'=> $data, 'query'=>$this->db->last_query()));			
		}
		else if($param == 'belajar4'){
			$organisasi = $post['organisasi'];
			$tgl = $post['tgl'];

			if($organisasi){

				$data = $this->db->query("select count(*) as jumlah, DATE_FORMAT(a.created_on, '%Y%m') as bulantahun from tbl_user a
					WHERE a.user_organisasi = $organisasi and `group` = 'murid' and user_type = 'BELAJAR' AND status = 'arsip' GROUP BY DATE_FORMAT(a.created_on, '%Y%m')")->result();
			}
			else{
				$data = $this->db->query("select count(*) as jumlah, DATE_FORMAT(a.created_on, '%Y%m') as bulantahun from tbl_user a
					WHERE `group` = 'murid' and user_type = 'BELAJAR' AND status = 'arsip' GROUP BY DATE_FORMAT(a.created_on, '%Y%m')")->result();
			}
			echo json_encode(array('data'=> $data, 'query'=>$this->db->last_query()));			
		}
	}
}
