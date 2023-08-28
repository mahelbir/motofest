<?php

function appDown(): void
{
	file_put_contents(CACHEPATH . 'maintenance', '');
}

function appUp(): void
{
	unlink(CACHEPATH . 'maintenance');
}

function appMaintenance(): void
{
	global $_ci;
	if (file_exists(CACHEPATH . 'maintenance')) {
		set_status_header(503);
		$_ci->blade->load('maintenance');
	}
}

appMaintenance();
