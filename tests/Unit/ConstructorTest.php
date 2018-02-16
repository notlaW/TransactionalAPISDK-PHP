<?php

namespace SixCRM\Test\Unit;

use SixCRM\Transaction;
use SixCRM\Test\Unit\BaseTest;

/**
 *
 */
class ConstructorTest extends BaseTest
{
    /**
      * Constructs a Transaction and validates that a request is
      * made via guzzle to acquire a token
      */
    public function testConstruct()
    {
        $client = $this->mockGuzzle([
            [200, [], json_encode(['response'   => 'testing-token'])]
        ]);

        $transaction = $this->createBasicTransaction($client, false);

        // --------------------------------------------------------------------
        // validation

        $requests = $this->getMockHistory();

        // one request only
        $this->assertCount(
            1,
            $requests
        );

        $request = $requests[0];

        $headers = $request['headers'];
        unset($request['headers']);

        // validate auth headers
        $this->assertCount(
            1,
            $headers['Authorization']
        );
        $this->assertRegExp(
            '/^[^:]+:[\d]+:........................................$/',
            $headers['Authorization'][0]
        );


        // validate request body
        $this->assertEquals(
            [
                'uri'       => 'https://test-base-path/token/acquire/badcafe0-0000-0000-0000-123456789abc',
                'method'    => 'POST',
                'request'   => [
                    'campaign'      => 'deadbeef-0000-0000-0000-123456789abc',
                    'affiliates'    => [
                        'affiliate'         => 'AFFILIATE',
                        'subaffiliate_1'    => 'SUBAFFILIA',
                    ]
                ]
            ],
            $request
        );
    }

    /**
      * Constructs a Transaction and validates that the token is
      * taken from the session
      */
    public function testConstructWithSessionToken()
    {
        $client = $this->mockGuzzle([
            [200, [], json_encode(['response'   => 'testing-token'])]
        ]);

        $transaction = $this->createBasicTransaction($client);

        // --------------------------------------------------------------------
        // validation

        $requests = $this->getMockHistory();
        $token = $transaction->getToken();

        // one request only
        $this->assertCount(
            0,
            $requests
        );

        $this->assertEquals(
            'token-via-session',
            $token
        );
    }
}
