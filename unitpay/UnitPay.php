<?php

# PHP Error enabled #
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

class UnitPay
{
	# UnitPay Methods #
    private $supportedPartnerMethods = array('check', 'pay', 'error');
	
	# UnitPay IP's #
    private $supportedUnitpayIp = array(
        '31.186.100.49',
        '178.132.203.105',
        '127.0.0.1'
    );
	
	# Set params Array #
	private $params = array();

	# Module Constructor #
	public function __construct()
	{
        $this->mysqli = @new mysqli (Config::DB_HOST, Config::DB_USER, Config::DB_PASS, Config::DB_NAME);

        if (mysqli_connect_errno()) {
            throw new Exception('Не удалось подключиться к бд');
        }
	}

	# Get URL for pay through the form #
	public function form($donate_account, $donate_sum, $donate_desc)
	{
		$donateParams = array(
			'account'  => $donate_account,
			'desc'	   => $donate_desc,
			'sum'	   => $donate_sum
		);

		$this->params = array_merge($this->params, $donateParams);

		if (Config::SECRET_KEY) {
			$this->params['signature'] = $this->getSignature($donateParams);
		}

		return 'https://unitpay.money/pay/'.Config::PUBLIC_KEY.'/card?'.http_build_query($this->params);
	}
	# Check login in DB #
    public function getAccountByName($account)
    {
        $sql = "
            SELECT
                *
            FROM
               accounts
            WHERE
               login = '".$this->mysqli->real_escape_string($account)."'
            LIMIT 1
         ";
         
        $result = $this->mysqli->query($sql);

        if (!$result){
            throw new Exception($this->mysqli->error);
        }

        return $result->fetch_object();
    }
	
	# Create payment in DB #
    public function createPayment($unitpayId, $account, $sum)
    {
        $query = '
            INSERT INTO
                payments (unitpay_id, account, sum, date_create, status)
            VALUES
                (
                    "'.$this->mysqli->real_escape_string($unitpayId).'",
                    "'.$this->mysqli->real_escape_string($account).'",
                    "'.$this->mysqli->real_escape_string($sum).'",
                    NOW(),
                    0
                )
        ';

        return $this->mysqli->query($query);
    }
	
	# Get payment info in DB by id #
    public function getPaymentByUnitpayId($unitpayId)
    {
        $query = '
                SELECT * FROM
                    payments
                WHERE
                    unitpay_id = "'.$this->mysqli->real_escape_string($unitpayId).'"
                LIMIT 1
            ';
            
        $result = $this->mysqli->query($query);

        if (!$result){
            throw new Exception($this->mysqli->error);
        }

        return $result->fetch_object();
    }
	
	# Update payment in DB by id #
    public function updatePaymentByUnitpayId($unitpayId)
    {
        $query = '
                UPDATE
                    payments
                SET
					date_complete = NOW(),
                    status = 1
                WHERE
                    unitpay_id = "'.$this->mysqli->real_escape_string($unitpayId).'"
                LIMIT 1
            ';
        return $this->mysqli->query($query);
    }
    
	# Create server donation in DB #
    public function donateForAccount($account, $count)
    {
		$count = floor($count / Config::ITEM_PRICE);
        $query = '
            INSERT INTO
                completed (srv, account, amount)
            VALUES
                (
                    "1",
                    "'.$this->mysqli->real_escape_string($account).'",
                    "'.$this->mysqli->real_escape_string($count).'"
                )
        ';
        
        return $this->mysqli->query($query);
    }
	
    # Check request on handler from UnitPay #
    public function checkHandlerRequest()
    {
        $ip = $this->getIp();
        if (!isset($_GET['method'])) {
            print $this->getResponseError('Method is null');
        }

        if (!isset($_GET['params'])) {
           print $this->getResponseError('Params is null');
        }

        list($method, $params) = array($_GET['method'], $_GET['params']);

        if (!in_array($method, $this->supportedPartnerMethods)) {
            print $this->getResponseError('Method is not supported');
        }

        if (!isset($params['signature']) || $params['signature'] != $this->getSignature($params, $method)) {
            print $this->getResponseError('Wrong signature');
        }
		
		/*
        if (!in_array($ip, $this->supportedUnitpayIp)) {
            print $this->getResponseError('IP address Error');
        }
		*/
        return true;
    }
	
    # Return IP address #
    protected function getIp()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
	
	# Create SHA-256 digital signature #
    private function getSignature(array $params, $method = null)
    {
        ksort($params);
        unset($params['sign']);
        unset($params['signature']);
        array_push($params, Config::SECRET_KEY);
		
        if ($method) {
            array_unshift($params, $method);
        }

        return hash('sha256', join('{up}', $params));
    }
	
	# Response for UnitPay if handle success #
    public function getResponseSuccess($message)
    {
        return json_encode(
            array(
                'result' => array(
                    'message' => $message
                )
            )
        );
    }

	# Response for UnitPay if handle error #
    public function getResponseError($message)
    {
        return json_encode(
            array(
                'error' => array(
                    'message' => $message
                )
            )
        );
    }
}
