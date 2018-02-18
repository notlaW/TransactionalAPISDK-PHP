<?php

namespace SixCRM\Test\Unit;

use SixCRM\Transaction;
use SixCRM\Test\Unit\BaseTest;

/**
 * @covers SixCRM\Form\Helper
 */
class HelperTest extends BaseTest
{
    const ADDRESS_SAMPLE = [
        'firstName'         => 'first',
        'lastName'          => 'last',
        'shipAddress1'      => 'ship1',
        'shipAddress2'      => 'ship2',
        'shipCity'          => 'city',
        'shipPostalCode'    => 'postal',
        'shipState'         => 'state',
        'phoneNumber'       => 'phone',
        'emailAddress'      => 'email'
    ];

    const CC_SAMPLE = [
        'cardNumber'        => '12345',
        'cardSecurityCode'  => '321',
        'cardMonth'         => '12',
        'cardYear'          => '1970',
    ];

    /**
      * Tests helper function parseCustomer
      */
    public function testParseFormCustomer()
    {
        $client = $this->mockGuzzle([]);
        $transaction = $this->createBasicTransaction($client);

        $customer = $transaction->parseCustomer(self::ADDRESS_SAMPLE);

        // --------------------------------------------------------------------
        // validation

        $this->assertEquals(
            [
                'customer' => [
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
                ]
            ],
            $customer
        );
    }


    /**
      * Tests helper function parseCreditCard
      */
    public function testParseCreditCard()
    {

        $client = $this->mockGuzzle([]);
        $transaction = $this->createBasicTransaction($client);

        $cc = $transaction->parseCreditCard(self::CC_SAMPLE);

        // --------------------------------------------------------------------
        // validation

        $this->assertEquals(
            [
                'number'        => 12345,
                'expiration'    => 121970,
                'ccv'           => 321,
            ],
            $cc
        );
    }
}
