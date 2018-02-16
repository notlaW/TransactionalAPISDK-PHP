<?php

namespace SixCRM\Test\Integration;

use SixCRM\Transaction;
use SixCRM\Test\Integration\BaseTest;

/**
 *
 */
class TrueTest extends BaseTest
{

    /**
      *
      *
      */
    public function testTrue()
    {

        $this->assertTrue(true);


        $transaction = $this->createTransaction();


    }
}

