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

        // number of requests
        $this->assertCount(
            1,
            $requests
        );

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
            'creditcard'    => [
                'address'       => [
                    'line1'         => 'ship1',
                    'line2'         => 'ship2',
                    'city'          => 'city',
                    'state'         => 'state',
                    'zip'           => 'postal',
                    'country'       => 'US',
                ],
                'number'        => '123412341234',
                'ccv'           => '321',
                'expiration'    => '022019',
                'name'          => 'Joe Blow',
            ],
            'transaction_subtype'   => 'checkout',
            'products'              => 'some product',
        ]);

        // --------------------------------------------------------------------
        // validate

        $requests = $this->getMockHistory();

        // number of requests
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
        $this->assertEquals(
            'token-via-session',
            $headers['Authorization'][0]
        );

        // validate request body
        $this->assertEquals(
            [
                'uri'       => 'https://test-base-path/order/create/badcafe0-0000-0000-0000-123456789abc',
                'method'    => 'POST',
                'request'   => [

                    'session'       => 'some-session-id',
                    'creditcard'    => [
                        'address'   => [
                            'line1'     => 'ship1',
                            'line2'     => 'ship2',
                            'city'      => 'city',
                            'state'     => 'state',
                            'zip'       => 'postal',
                            'country'   => 'US',
                        ],
                        'number'        => '123412341234',
                        'ccv'           => '321',
                        'expiration'    => '022019',
                        'name'          => 'Joe Blow',
                    ],
                    'transaction_subtype'   => 'checkout',
                    'products'              => 'some product'
                ]
            ],

            $request
        );
    }
}
