<?php

$GLOBALS["_ci"] =& get_instance();

function loadBeforeAll(): void
{
    global $_ci;
	$_ci->load->library("user_agent");
	$_ci->load->library("Sets");
    $_ci->load->library("Blade");
    $_ci->load->library("Mecrypt");
}

loadBeforeAll();
