<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['admin'] = 'admin/dashboard';
$route['admin/login'] = 'admin/user/login';
$route['admin/logout'] = 'admin/user/logout';

/* ROUTE UNTUK ARTIKEL */
$route['artikel/hal'] = "artikel/index/hal";
$route['artikel/hal/(:num)'] = "artikel/index/hal/$1";
$route['artikel/(:any)'] = "artikel/kategori/$1";
$route['artikel/(:any)/hal'] = "artikel/kategori/$1/hal";
$route['artikel/(:any)/hal/(:num)'] = "artikel/kategori/$1/hal/$2";
$route['artikel/(:any)/(:any)'] = "artikel/detil/$1/$2";

/* ROUTE UNTUK produk */
$route['produk/transaksi'] = "produk/transaksi/tambah";
$route['produk/transaksi/(:any)'] = "produk/transaksi/$1";
$route['produk/tarif_jne'] = "produk/tarif_jne";
$route['produk/hal'] = "produk/index/hal";
$route['produk/hal/(:num)'] = "produk/index/hal/$1";
$route['produk/(:any)'] = "produk/kategori/$1";
$route['produk/(:any)/hal'] = "produk/kategori/$1/hal";
$route['produk/(:any)/hal/(:num)'] = "produk/kategori/$1/hal/$2";
$route['produk/(:any)/(:any)'] = "produk/detil/$1/$2";

/* ROUTE UNTUK HALAMAN */
$route['halaman/(:any)'] = "halaman/detil/$1";
$route['halaman/(:any)/(:any)'] = "halaman/detil/$1/$2";
$route['halaman/(:any)/(:any)/(:any)'] = "halaman/detil/$1/$2/$3";




$route['tentang-kami'] = "front/about";
$route['cara-penggunaan'] = "front/caraguna";
$route['kontak-kami'] = "front/kontak";
$route['kontak-kami/(:any)'] = "front/kontak/$1";

// DASHBOARD
$route['dashboard'] = "profile";
$route['profile'] = "profile/profile";
$route['profile/(:any)'] = "profile/profile/$1";
$route['kursus-saya'] = "profile/kursus_saya";
$route['kursus-saya/hal'] = "profile/kursus_saya";
$route['kursus-saya/hal/(:any)'] = "profile/kursus_saya";
$route['sertifikat'] = "profile/sertifikat";
$route['sertifikat/get_sertifikat/(:any)/(:any)'] = "profile/get_sertifikat";
$route['report-progress/(:any)/(:any)'] = "profile/report_progress";


// COURSES
$route['courses'] = "courses";
$route['courses/hal'] = "courses";
$route['courses/hal/(:num)'] = "courses";
$route['courses/detail/(:any)/(:any)'] = "courses/detail";
$route['courses/video/(:any)/(:any)'] = "courses/video_kursus";


$route['courses/latihan/(:any)/(:any)'] = "courses/latihan";
$route['courses/ujian/(:any)/(:any)'] = "courses/ujian";



/* UNTUK NONTON KURSUS NYA*/
$route['courses/video/(:any)/(:any)/(:any)/(:any)'] = "courses/video_kursus";
$route['courses/learn/(:any)/(:any)'] = "courses/learn";
$route['courses/komentar/kirim'] = "courses/komentar";




/* LOGIN REGISTER */
$route['login'] = "auth";
$route['login/(:any)'] = "auth/$1";
$route['register'] = "auth/register";
$route['register/(:any)'] = "auth/register/$1";
$route['auth/(:any)'] = "auth/$1";
$route['reset-password/(:any)'] = "auth/reset_password";
$route['aktivasi/(:any)'] = "auth/aktivasi";


/* KONTEN */
$route['content/(:any)'] = "content";
$route['profesi/(:any)'] = "content/profesi";
$route['organisasi/(:any)'] = "content/organisasi";
$route['related-link/(:any)/(:any)'] = "content/related";


$route['halaman'] = 'front/get_halaman';


$route['email'] = 'front/email';


$route['default_controller'] = 'front';
$route['404_override'] = 'error';
$route['translate_uri_dashes'] = FALSE;
