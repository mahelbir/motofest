<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Admin_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $login_redirect = true;
        if (!$this->session->logged_in) {
            $cookie = get_cookie("_ADMIN");
            if ($cookie) {
                $json = json_decode($this->mecrypt->decode($cookie), true);
                if (isset($json["ip"]) && isset($json["agent"]) && isset($json["key"]) && isset($json["admin"])) {
                    $this->load->model("Admins_m");
                    if (get_ip() == $json["ip"] && $this->agent->agent_string() == $json["agent"] && setup("encryption_key") == $json["key"] && $this->Admins_m->select($json["admin"], false, ["id"])) {
                        $this->session->set_userdata("logged_in", $json["admin"]);
                        $login_redirect = false;
                    }
                }
            }
        } else {
            $login_redirect = false;
        }

        if ($login_redirect) {
            delete_cookie("_ADMIN");
            redirect(base_url("auth/login"));
            exit;
        }
    }
}