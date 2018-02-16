<?php

namespace SixCRM\Test\Integration;

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

    public function setup()
    {

    }

    /**
     *
     */
    protected function createTransaction()
    {

        $config = json_decode(
            file_get_contents(__DIR__ . '/../../six-config.json'),
            true
        );

        $transaction = new Transaction($config);

        return $transaction;
    }


}
