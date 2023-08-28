<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->session->logged_in) {
            $this->load->model("Siparisler_m");
            $this->blade->load('admin', [
                'siparisler' => $this->Siparisler_m->get_daily_earnings()
            ]);
        } else
            $this->blade->load('error', ['error' => 'Giriş yapılmamış!']);
    }

    public function urun()
    {
        $this->load->model("Urunler_m");
        $id = $this->Urunler_m->insert([
            "isim" => $this->input->post("isim"),
            "fiyat" => $this->input->post("fiyat"),
            "resim" => $this->input->post("resim")
        ]);
        if ($id) {
            echo json_encode([
                "id" => $id
            ]);
        } else {
            echo json_encode([
                "error" => "Veritabanı hatası!"
            ]);
        }
    }

}
