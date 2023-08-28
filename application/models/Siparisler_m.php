<?php

defined("BASEPATH") or exit("No direct script access allowed");

class Siparisler_m extends MY_Model
{
    public function __construct()
    {
        parent::__construct("siparisler");
    }

    public function get_daily_earnings()
    {
        $this->db->select('DATE(tarih) as gun, SUM(tutar) as ciro, count(id) as adet');
        $this->db->from('siparisler');
        $this->db->group_by('DATE(tarih)');
        $this->db->order_by('gun', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

}