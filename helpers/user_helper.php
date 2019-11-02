<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function bCrypt($pass,$cost){
      $chars='./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
 
      // Build the beginning of the salt
      $salt=sprintf('$2a$%02d$',$cost);
 
      // Seed the random generator
      mt_srand();
 
      // Generate a random salt
      for($i=0;$i<22;$i++) $salt.=$chars[mt_rand(0,63)];
 
     // return the hash
    return crypt($pass,$salt);
}

function get_user_info($param=null){
	$_this =& get_instance();
	if($param != null){
		return $_this->session->userdata($param);
	}
	else{
		return $_this->session->userdata;
	}
}

function send_mail($from,$subject,$message,$to,$bcc=NULL)
{
  $_this =& get_instance();
  $config = [
   'protocol'  => 'smtp',
   'smtp_host' => 'mail.e-sehat.org',
   'smtp_port' => 587,
   'smtp_user' => 'noreply@e-sehat.org', // change it to yours
   'smtp_pass' => '5W?JY9!!0o', // change it to yours
   'mailtype'  => 'html',
   'charset'   => 'iso-8859-1',
   'wordwrap'  => TRUE,
   'crlf'      => "\r\n",
   'newline'   => "\r\n",
   'wordwrap'  => TRUE,
  ];

  $_this->load->library('email');
  $_this->email->initialize($config);
  //konfigurasi pengiriman
  $_this->email->from($from, 'Admin Postel');
  $_this->email->subject($subject);
  $_this->email->message($message);
  $send = FALSE;
  if ($to!=NULL) {
    $_this->email->to($to);
    $send = TRUE;
  }elseif(($to&&$bcc)!=NULL){
    $_this->email->to($to);
    $_this->email->bcc($bcc);
    $send = TRUE;
  }elseif($to==NULL && $bcc!=NULL){
    $_this->email->bcc($bcc);
    $send = TRUE;
  }
  return $_this->email->send();
}
?>