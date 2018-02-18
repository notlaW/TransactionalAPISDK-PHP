<?php

namespace SixCRM\Test\Unit;

use SixCRM\Transaction;
use SixCRM\Test\Unit\BaseTest;

/**
 *
 */
class InteractionTest extends BaseTest
{

    const CUSTOMER = [
            'firstname'     => 'first',
            'lastname'      => 'last',
            'email'         => 'email',
            'phone'         => 'phone',
            'billing'       => [
                'line1'         => 'ship1',
                'city'          => 'city',
                'state'         => 'state',
                'zip'           => 'postal',
                'country'       => 'US',
            ],
            'address'       => [
                'line1'         => 'ship1',
                'line2'         => 'ship2',
                'city'          => 'city',
                'state'         => 'state',
                'zip'           => 'postal',
                'country'       => 'US',
            ]
        ];

    /**
      *
      *
      */
    public function testCreateLead()
    {
        $client = $this->mockGuzzle([
            [200, [], json_encode(
                [
                    'response'   => [
                        'id'    => 'some-session-id',
                    ]
                ]
            )],
        ]);

        $transaction = $this->createBasicTransaction($client, true);

        $transaction->createLead(self::CUSTOMER);

        // --------------------------------------------------------------------
        // validate

        $requests = $this->getMockHistory();

        $this->assertEquals(
            'some-session-id',
            $_SESSION['session_id']
        );

        $request = $requests[0];

        $headers = $request['headers'];
        unset($request['headers']);

        // validate auth headers
        $this->assertCount(
            1,
            $headers['Authorization']
        );
        $this->assertEquals(
            'token-via-session',
            $headers['Authorization'][0]
        );

        // validate request body
        $this->assertEquals(
            [
                'uri'       => 'https://test-base-path/lead/create/badcafe0-0000-0000-0000-123456789abc',
                'method'    => 'POST',
                'request'   => [
                    'customer'      => self::CUSTOMER,
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

    public function testCreateOrder()
    {
        $client = $this->mockGuzzle([
            [200, [], json_encode(
                [
                    'response'   => [
                    ]
                ]
            )],
        ]);

        $_SESSION['session_id'] = 'some-session-id';

        $transaction = $this->createBasicTransaction($client, true);

        $success = $transaction->createOrder([
            'number'        => 12345,
            'expiration'    => 121970,
            'ccv'           => 321,
        ]);

        // --------------------------------------------------------------------
        // validate


        $requests = $this->getMockHistory();

//        print_r($requests);

    }
}
