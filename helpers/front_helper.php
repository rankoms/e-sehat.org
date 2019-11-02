<?php 
	
	function secondToMinutes($init){
		$hours = floor($init / 3600);
		$minutes = floor(($init / 60) % 60);
		$seconds = $init % 60;

		return $hours.' Jam '.$minutes.' Menit '.$seconds.' Detik';
	}
	function getRomawi($bln){
        switch ($bln){
            case 1: 
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
	}
	function random_angka1(){
		$_this =& get_instance();
		$angka1 = (rand(1,20));
		return $angka1;
	}

	function random_angka2(){
		$_this =& get_instance();
		$angka2 = (rand(1,20));
		return $angka2;
	}

	function random_hasil(){
		$_this =& get_instance();
		$hasil = random_angka2()+ random_angka1();
		return $hasil;
	}

	function get_sertifikat($where = null){
		$_this =& get_instance();
		// $hasil = random_angka2()+ random_angka1();
		$record = $_this->Sertifikat_model->get_by($where);
		return $record;
	}
	function get_profesi(){
		$_this =& get_instance();
		// $hasil = random_angka2()+ random_angka1();
		$record = $_this->Profesi_model->get();
		return $record;
	}
	function get_organisasi($where=null){
		$_this =& get_instance();
		// $hasil = random_angka2()+ random_angka1();
		if(get_user_info('group') == 'admin'){
			$record = $_this->Organisasi_model->get_by($where);
		}
		else if(!get_user_info('ID')){
			$record = $_this->Organisasi_model->get_by($where);
		}
		else{
			$record = $_this->Organisasi_model->get_by(array('organisasi_id'=> get_user_info('user_organisasi')));
		}
		return $record;
	}

	function get_halaman(){
		$_this =& get_instance();
		// $hasil = random_angka2()+ random_angka1();
		$record = $_this->Halaman_model->get_by(array('post_type' => 'halaman', 'post_parent' => 0));
		return $record;
	}
	function get_halaman2($id){
		$_this =& get_instance();
		// $hasil = random_angka2()+ random_angka1();
		$record = $_this->Halaman_model->get_by(array('post_parent '=> $id ));
		return $record;
	}

	function get_menu($data, $parent = 0) {
		static $i = 1;
		$tab = str_repeat("\t\t", $i);
		if (isset($data[$parent])) {
			$html = "\n$tab<ul class='nav navbar-nav navbar-main-menu'>";
			$i++;
			foreach ($data[$parent] as $v) {
				$child = get_menu($data, $v->post_ID);
				$html .= "\n\t$tab<li>";
				$html .= '<a href="'.$v->post_name.'">'.$v->post_title.'</a>';
				if ($child) {
					$i--;
					$html .= $child;
					$html .= "\n\t$tab";
				}
				$html .= '</li>';
			}
			$html .= "\n$tab</ul>";
			return $html;
		} else {
			return false;
		}
	}

	function get_profile($nilai){
		$_this =& get_instance();

		$data = $_this->User_model->get($nilai);
		return $data;
	}
	function get_title_organisasi($slug){
		$_this =& get_instance();
		$record = $_this->Organisasi_model->get_by(array('organisasi_slug '=> $slug ));
		return $record;

	}

	function get_halaman_content($slug){
		$_this =& get_instance();

		$data = $_this->Halaman_model->get_by(array('post_name'=>$slug));
		return $data;
	}
	function get_title_profesi($slug){
		$_this =& get_instance();
		$record = $_this->Profesi_model->get_by(array('profesi_slug '=> $slug ));
		return $record;

	}

	
	function get_user_instruktur(){
		$_this =& get_instance();
		if(get_user_info('group') == 'admin'){
			$record = $_this->User_model->get_by(array('group '=> 'instruktur'));
		}
		else if(!get_user_info('ID')){
			$record = $_this->User_model->get_by(array('group '=> 'instruktur'));
		}
		else{
			$record = $_this->User_model->get_by(array('group' => 'instruktur', 'user_organisasi'=> get_user_info('user_organisasi')));
		}
		return $record;

	}
	
	function get_kursus($id=null){
		$_this =& get_instance();
		$record = $_this->Kursus_model->get($id);
		return $record;
	}
	
	function get_kursus_cari($where=null){
		$_this =& get_instance();
		$record = $_this->Kursus_model->get_kursus($where);
		return $record;
	}
	
	function get_modul($id=null, $cek = FALSE){
		$_this =& get_instance();
		if($cek == 'TRUE'){
			$record = $_this->Modul_model->get_log_modul(array('modul_kursus' => $id));
		}
		else{
			$record = $_this->Modul_model->get_by(array('modul_kursus' => $id));
		}
		
		return $record;
	}
	
	function get_modul_count($type, $id){
		$_this =& get_instance();
		$record = $_this->Materi_model->count(array('materi_type' => $type, 'materi_modul' => $id));
		return $record;
	}

	function get_count_kuis($where=null){
		$_this =& get_instance();
		$record = $_this->Soal_model->count($where);
		return $record;
	}
	function get_count_latihan($where=null){
		$_this =& get_instance();
		$record = $_this->Soal_model->get_latihan($where);
		return $record;
	}
	function get_count_ujian($where=null){
		$_this =& get_instance();
		$record = $_this->Soal_model->count($where);
		return $record;
	}

	function get_count_kursus($where=null, $limit, $offset){
		$_this =& get_instance();
		if($offset == 1){
			$offset = 0;
		}
		$record = $_this->Kursus_model->get_count_kursus($where, $limit, $offset);
		return $record;
	}

	function get_materi($materi_kursus = '',$type = 'video'){
		$_this =& get_instance();
		$record = $_this->Materi_model->get_by(array('materi_type' => $type,'materi_kursus' => $materi_kursus));
		return $record;
	}

	function get_log_materi($materiid = '', $iduser=''){
		$_this =& get_instance();
		$record = $_this->Log_materi_model->get_by(array('log_materi_materiid' => $materiid, 'log_materi_iduser' => $iduser));
		return $record;
	}


	function get_log_materi_dashboard($materiid = '', $iduser=''){
		$_this =& get_instance();
		$record = $_this->Log_materi_model->get_by(array('log_materi_kursus' => $materiid, 'log_materi_iduser' => $iduser));
		return $record;
	}


	function get_materi_modul($materi_modul = '',$type = 'video', $single = FALSE, $log = FALSE){
		$_this =& get_instance();
		if($log == 'TRUE'){
			$record = $_this->Materi_model->get_materi_kursus_log(array('materi_type' => $type,'materi_modul' => $materi_modul), NULL, NULL, $single, NULL);
		}
		else{
			$record = $_this->Materi_model->get_materi_kursus(array('materi_type' => $type,'materi_modul' => $materi_modul), NULL, NULL, $single, NULL);
		}
		return $record;
	}

	function get_materi_kursus($materi_kursus = '',$type = 'video', $single = FALSE, $log = FALSE){
		$_this =& get_instance();
		if($log == 'TRUE'){
			$record = $_this->Materi_model->get_materi_kursus_log(array('materi_type' => $type,'materi_kursus' => $materi_kursus), NULL, NULL, $single, NULL);
		}
		else{
			$record = $_this->Materi_model->get_materi_kursus(array('materi_type' => $type,'materi_kursus' => $materi_kursus), NULL, NULL, $single, NULL);
		}
		return $record;
	}
	function get_materi_modul_detail($materi_modul = '',$type = 'video', $single = FALSE){
		$_this =& get_instance();
		$record = $_this->Materi_model->get_materi_kursus(array('materi_type' => $type,'materi_id' => $materi_modul), NULL, NULL, $single, NULL);
		return $record;
	}


	function get_komentar($materiid = ''){
		$_this =& get_instance();
		if(get_user_info('group') == 'admin'){
			$record = $_this->Komentar_model->get_komentar(array('comment_post_ID' => $materiid, 'comment_approved' => 'publish'));
		}
		else{
			$record = $_this->Komentar_model->get_komentar(array('comment_post_ID' => $materiid, 'comment_approved' => 'publish', 'comment_author'=> get_user_info('ID')));
		}
		return $record;
	}


	function get_log_soal($where = ''){
		$_this =& get_instance();
		$record = $_this->Log_soal_model->get_by($where);
		return $record;
	}

	function get_log_latihan($where = ''){
		$_this =& get_instance();
		$record = $_this->Log_latihan_model->get_by($where);
		return $record;
	}
	function get_time($time = ''){
		$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $time);

		sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

		return $time_seconds = $hours * 3600 + $minutes * 60 + $seconds;
	}
 ?>