<?php

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}


	qa_register_plugin_module('widget', 'best-users.php', 'BestUsers', 'Best users');
//	qa_register_plugin_layer('qa-most-popular-layer.php', 'Most popular questions Layer');
