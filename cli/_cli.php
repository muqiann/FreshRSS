<?php
if (php_sapi_name() !== 'cli') {
	die('FreshRSS error: This PHP script may only be invoked from command line!');
}

require(dirname(__FILE__) . '/../constants.php');
require(LIB_PATH . '/lib_rss.php');

Minz_Configuration::register('system',
	DATA_PATH . '/config.php',
	DATA_PATH . '/config.default.php');
FreshRSS_Context::$system_conf = Minz_Configuration::get('system');
Minz_Translate::init();

FreshRSS_Context::$isCli = true;

function fail($message) {
	fwrite(STDERR, $message . "\n");
	die(1);
}

function cliInitUser($username) {
	if (!ctype_alnum($username)) {
		fail('FreshRSS error: invalid username: ' . $username . "\n");
	}

	$usernames = listUsers();
	if (!in_array($username, $usernames)) {
		fail('FreshRSS error: user not found: ' . $username . "\n");
	}

	FreshRSS_Context::$user_conf = get_user_configuration($username);
	if (FreshRSS_Context::$user_conf == null) {
		fail('FreshRSS error: invalid configuration for user: ' . $username . "\n");
	}
	new Minz_ModelPdo($username);

	return $username;
}
