<?php

# PHP Error enabled #
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once('config.php');
require_once('UnitPay.php');

$unitPay = new UnitPay(Config::SECRET_KEY);

# Check incoming recuest UnitPay #
$unitPay->checkHandlerRequest();

list($method, $params) = array($_GET['method'], $_GET['params']);

switch ($method) {
	case 'check':
		if ($unitPay->getPaymentByUnitpayId($params['unitpayId'])) {
			print $unitPay->getResponseError('Payment already exists.');
		} 
		else if (!$unitPay->getAccountByName($params['account'])) {
			print $unitPay->getResponseError('Login not found.');
		}
		else if (!$unitPay->createPayment($params['unitpayId'], $params['account'], $params['sum'])) {
			print $unitPay->getResponseError('Unable to create payment database.');
		} 
		else {
			print $unitPay->getResponseSuccess('Check Success. Ready to pay.');
		}
		break;
	case 'pay':	
		$payment = $unitPay->getPaymentByUnitpayId($params['unitpayId']);
		
		if ($payment && $payment->status == 1) {
			print $unitPay->getResponseSuccess('Payment has already been paid.');
		} 
		else {
			$unitPay->updatePaymentByUnitpayId($params['unitpayId']);
			$unitPay->donateForAccount($params['account'], floor($params['sum']));
			print $unitPay->getResponseSuccess('Pay Success');	
		}
		break;
}