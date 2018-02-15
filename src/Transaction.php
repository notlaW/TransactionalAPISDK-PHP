<?php
namespace SixCRM;

use GuzzleHttp\Client as GuzzleClient;

class Transaction {

    private $api_base_path;
    private $secret_key;
    private $access_key;
    private $affiliates;
    private $account;
    private $signature;
    private $campaign;
    private $token;
    private $client;

	function __construct(
        $config = array(),
        $session = false,
        $client = false
    ){

        if ($session) {
            session_start();
        }

        // FIXME
		// Technical Debt:  We need to validate the contents of the transaction configuration object

		$this -> set('api_base_path',   $config['api_base_path']);
		$this -> set('secret_key',      $config['secret_key']);
		$this -> set('access_key',      $config['access_key']);

		$this -> set('affiliates',      $config['affiliates']);
		$this -> set('account',         $config['account']);
		$this -> set('signature',       $config['signature']);
		$this -> set('campaign',        $config['campaign']);

		$this -> set('client',          $client);

        if (!$this->client) {
            $client = new GuzzleClient();
        }

		$this->getToken();
	}

    /**
      * Memoized token getter
      */
	public function getToken()
    {

		if (is_null($this->token)) {

			if (isset($_SESSION['token'])){
                $this->token = $_SESSION['token'];
			}
            else{
                $_SESSION['token'] = $this->token = $this->acquireToken();
            }
        }

        return $this->token;
    }

    /**
      * Gets a security token from SixCRM.  Usually better to call getToken().
      */
	public function acquireToken()
    {

		$signature      = $this -> generateAcquireTokenSignature();
		$account        = $this -> get('account');

        $response = $this->client->request(
            'POST',
            $this->getFullyQualifiedEndpoint('token/acquire/' . $account),
            [
                'headers'   => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => $signature,
                ],
                'json'      => [
                    'campaign'      => $this->get('campaign'),
                    'affiliates'    => $this->get('affiliates')
                ]
            ]
        );

        return json_decode($response->getBody());
	}

    /**
      *
      */
	public function createLead($lead = array())
    {

		$token = $this -> getToken();
		$account = $this -> get('account');

		$lead['campaign'] = $this -> get('campaign');
		$lead['affiliates'] = $this -> get('affiliates');

		$response = $this -> performPost(
			'lead/create/'.$account,
			array(
				'Content-Type: application/json',
				'Authorization: '.$token
			),
			$lead
		);


		//Technical Debt:  This should be a seperate function
		$decoded_response = json_decode($response);

		$this -> setSessionId($decoded_response -> response -> id);

		return $response;
	}

    /**
      *
      */
	public function createOrder($order =  array())
    {

		$token = $this -> getToken();

		$account = $this -> get('account');

		$order['session'] =  $this -> getSessionId();

		$response = $this -> performPost(
			'order/create/'.$account,
			array(
				'Content-Type: application/json',
				'Authorization: '.$token
			),
			$order
		);

		return $response;

	}

    /**
      *
      */
	public function createUpsell($upsell = array())
    {

		$token = $this -> getToken();

		$account = $this -> get('account');

		$upsell['session'] =  $this -> getSessionId();

		$response = $this -> performPost(
			'order/create/'.$account,
			array(
				'Content-Type: application/json',
				'Authorization: '.$token
			),
			$upsell
		);

		return $response;

	}

    /**
      *
      */
	public function confirmOrder()
    {

		$token = $this -> getToken();

		$account = $this -> get('account');

		$session = $this -> getSessionId();

		$response = $this -> performGet(
			'order/confirm/'.$account.'?session='.$session,
			array(
				'Content-Type: application/json',
				'Authorization: '.$token
			)
		);

		return $response;

	}

    // ------------------------------------------------------------------------

    /**
      *
      */
	private function generateAcquireTokenSignature()
    {

		$access_key         = $this -> get('access_key');

		$request_time       = $this -> getRequestTime();

		$signature          = $this -> generateSignature($request_time);

		return implode(':', array($access_key, $request_time, $signature));

	}

    /**
      *
      */
	private function generateSignature($request_time)
    {

		$secret = $this -> get('secret_key');

		$prehash = $secret.$request_time;

		return sha1($prehash);

	}

    /**
      *
      */
	private function setSessionId($session_id)
    {

		$session_id = (string) $session_id;

		$this -> set('session_id', $session_id);

		$_SESSION['session_id'] = $session_id;

		return true;

	}

    /**
      *
      */
	private function getSessionId()
    {

		$session_id = $this -> get('session_id');

		if ($session_id){

			return $session_id;

		} else {

			if (isset($_SESSION['session_id'])){

				$this -> set('session_id', $_SESSION['session_id']);

				return $this -> getSessionId();

			}

		}

		return false;

	}

    /**
      *
      */
	private function getAcquireBody()
    {

		return array(
			"campaign" => $this -> get('campaign'),
			"affiliates" => $this -> get('affiliates')
		);

	}

    /**
      *
      */
	private function performPost($endpoint, $body)
    {
		$fqe = $this -> getFullyQualifiedEndpoint($endpoint);

        $res = $this->client->request('POST', $fqe,
            [
                'headers'   => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => $this->getToken()
                ],
                'json'      => $body
            ]
        );

        return $res->getBody();
	}

    /**
      *
      */
	private function performGet($endpoint)
    {
		$fqe = $this -> getFullyQualifiedEndpoint($endpoint);

        $res = $this->client->request('GET', $fqe,
            [
                'headers'   => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => $this->getToken()
                ],
            ]
        );

        return $res->getBody();
	}

    /**
      *
      */
	private function getFullyQualifiedEndpoint($endpoint)
    {

		return $this -> get('api_base_path').$endpoint;

	}

    /**
      *
      */
	private function set($key, $value)
    {

		$this -> {$key} = $value;

	}

    /**
      *
      */
	private function get($key)
    {

		if (property_exists($this, "$key")){

			return $this -> {$key};

		}

		return null;

	}

    /**
      *
      */
	private function getRequestTime()
    {

		//return time()*1000;
		return '1491243564378';

	}
}

?>
