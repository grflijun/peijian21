<?php
	require_once 'include/commonvar.php';

	session_start();

	$g_lan_id = LAN_ID_CN;

	if(isset($_SESSION['lan_id']) && LAN_ID_EN == $_SESSION['lan_id'])
	{
		$g_lan_id = LAN_ID_EN;
	}
?>
