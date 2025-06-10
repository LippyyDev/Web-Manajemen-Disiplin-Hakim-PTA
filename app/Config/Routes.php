<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->get("login", "Login::index");
$routes->post("login/auth", "Login::auth");
$routes->get("logout", "Login::logout");

$routes->group("admin", ["filter" => "authFilter"], function ($routes) {
    $routes->get("dashboard", "AdminController::dashboard");
    $routes->get("kelola_user", "AdminController::kelolaUser");
    $routes->get("kelola_laporan", "AdminController::kelolaLaporan");
    $routes->get("rekap_kedisiplinan", "AdminController::rekapKedisiplinan");
    $routes->get("notifikasi", "AdminController::notifikasi");
    $routes->get("profil", "AdminController::profil");
});

$routes->group("user", ["filter" => "authFilter"], function ($routes) {
    $routes->get("dashboard", "UserController::dashboard");
    $routes->get("input_pegawai", "UserController::inputPegawai");
    $routes->get("input_kedisiplinan", "UserController::inputKedisiplinan");
    $routes->get("input_tanda_tangan", "UserController::inputTandaTangan");
    $routes->get("rekap_laporan", "UserController::rekapLaporan");
    $routes->get("rekap_laporan", "UserController::rekapLaporan"); 
    $routes->post("rekap_laporan/export_pdf", "UserController::exportPdf"); 
    $routes->post("rekap_laporan/export_excel", "UserController::exportExcel"); 
    $routes->get("rekap_bulanan", "UserController::rekapBulanan");
    $routes->get("upload_file", "UserController::uploadFile");
    $routes->get("notifikasi", "UserController::notifikasi");
    $routes->get("profil", "UserController::profil");
    $routes->post("profil/update", "UserController::updateProfil");
    $routes->post("profil/update_foto", "UserController::updateFotoProfil"); 
});

$routes->get('/user/getFile/(:any)', 'UserController::getFile/$1');


$routes->post("kelola_user/add", "AdminController::addUser");
$routes->post("kelola_user/update", "AdminController::updateUser");
$routes->get("kelola_user/delete/(:num)", "AdminController::deleteUser/$1");




$routes->get("kelola_laporan/view/(:num)", "AdminController::viewLaporan/$1");
$routes->post("kelola_laporan/approve", "AdminController::approveLaporan");
$routes->post("kelola_laporan/reject", "AdminController::rejectLaporan");
$routes->post("kelola_laporan/delete", "AdminController::deleteLaporan");




$routes->get("rekap_kedisiplinan", "AdminController::rekapKedisiplinan");




$routes->get("notifikasi", "AdminController::notifikasi");




$routes->post("admin/profil/update", "AdminController::updateProfil");
$routes->post("admin/profil/update_foto", "AdminController::updateFotoProfil");




$routes->post("user/input_pegawai/add_satker", "UserController::addSatker");
$routes->post("user/input_pegawai/add", "UserController::addPegawai");
$routes->post("user/input_pegawai/update", "UserController::updatePegawai");
$routes->get("user/input_pegawai/delete/(:num)", "UserController::deletePegawai/$1");




$routes->post("user/input_kedisiplinan/add", "UserController::addKedisiplinan");
$routes->post("user/input_kedisiplinan/update", "UserController::updateKedisiplinan");
$routes->get("user/input_kedisiplinan/delete/(:num)", "UserController::deleteKedisiplinan/$1");




$routes->post("user/input_tanda_tangan/add", "UserController::addTandaTangan");
$routes->post("user/input_tanda_tangan/update", "UserController::updateTandaTangan");
$routes->get("user/input_tanda_tangan/delete/(:num)", "UserController::deleteTandaTangan/$1");




$routes->get("user/rekap_laporan", "UserController::rekapLaporan");




$routes->get("user/rekap_bulanan", "UserController::rekapBulanan");




$routes->post("user/upload_file/add", "UserController::addFile");
$routes->post("user/upload_file/reupload", "UserController::reuploadFile");
$routes->post("user/upload_file/delete", "UserController::deleteFile");


