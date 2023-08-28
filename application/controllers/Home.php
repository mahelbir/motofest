<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }



    public function giris()
    {
        $this->session->set_userdata('logged_in', time());
        redirect(base_url());
    }

    public function my_404()
    {
        my_404();
    }
}
