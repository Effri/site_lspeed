<?php

# PHP Error enabled #
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once('config.php');
require_once('UnitPay.php');

$unitPay = new UnitPay(Config::SECRET_KEY);

if($_POST) {
	# Donate Data #
	$donate_account = $_POST['donate_account'];
	$donate_sum = $_POST['donate_sum'];
	$donate_desc = "Lspeed: Пополнение счета";

	# Gen Donate Form #
	$redirectUrl = $unitPay->form(
		$donate_account,
		$donate_sum,
		$donate_desc
	);
	# Redirect to UnitPay site #
	header("Location: " . $redirectUrl);
}