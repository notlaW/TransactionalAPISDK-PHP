<?php

namespace SixCRM\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use SixCRM\Transaction;

/**
 *
 */
abstract class BaseTest extends TestCase
{
    protected $history = [];

    public function setup()
    {

    }

    /**
     *
     */
    protected function createBasicTransaction($client, $session = true)
    {

        if ($session) {
            $_SESSION['token'] = 'token-via-session';
        }

        $transaction = new Transaction(
            [
                'access_key'        => 'fakeaccesskeyabcefghijklmnop',
                'account'           => 'badcafe0-0000-0000-0000-123456789abc',
                'affiliates'        => [
                    'affiliate'         => 'AFFILIATE',
                    'subaffiliate_1'    => 'SUBAFFILIA',
                ],
                'api_base_path'     => 'https://test-base-path/',
                'campaign'          => 'deadbeef-0000-0000-0000-123456789abc',
                'secret_key'        => 'ca11a1b1c0d300000000000000==',
                'signature'         => '1234567890123456789012345678901234567890'
            ],
            false,
            $client
        );

        return $transaction;
    }

    /**
     *
     */
    protected function mockGuzzle(
        $responses
    ) {

        $mockHandler    = new MockHandler();
        $handlerStack   = HandlerStack::create($mockHandler);

        foreach ($responses as $r) {
            if (is_array($r)) {
                $mockHandler->append(new Response(...$r));
            } else {
                $mockHandler->append(new Response($r));
            }
        }

        $historyMiddleware = Middleware::history($this->history);
        $handlerStack->push($historyMiddleware);

        $client = new Client(['handler' => $handlerStack]);

        return $client;
    }

    /**
     *
     */
    protected function getMockHistory()
    {
        $return = [];

        foreach ($this->history as $transaction) {

            $request = $transaction['request'];

            $uri = $request->getUri();

            $redux = [
                'uri'       => (string)$uri,
                'method'    => $request->getMethod(),
                'request'   => json_decode((string)$request->getBody(), true),
                'headers'   => $request->getHeaders(),
            ];

            $return[] = $redux;
        }

        return $return;
    }
}
