<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Siparis extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->session->logged_in) {
            $this->load->model("Urunler_m");
            $urunler = $this->Urunler_m->select([], true, [], 'ASC');
            $json = [];
            foreach ($urunler as $urun)
                $json[$urun['id']] = $urun;
            $this->blade->load('siparis', [
                'urunler' => $urunler,
                'json' => json_encode($json)
            ]);
        } else
            $this->blade->load('error', ['error' => 'Giriş yapılmamış!']);
    }

    public function yeni()
    {
        $this->load->model("Siparisler_m");
        $id = $this->Siparisler_m->insert([
            "tutar" => $this->input->post("tutar"),
            "icerik" => $this->input->post("icerik"),
            "tarih" => date("Y-m-d H:i:s")
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
