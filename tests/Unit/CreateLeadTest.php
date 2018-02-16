<?php

namespace SixCRM\Test\Unit;

use SixCRM\Transaction;
use SixCRM\Test\Unit\BaseTest;

/**
 *
 */
class CreateLeadTest extends BaseTest
{

    /**
      *
      *
      */
    public function testCreatLead()
    {
        $client = $this->mockGuzzle([
            [200, [], json_encode(['response'   => 'testing-token'])]
        ]);

        $transaction = $this->createBasicTransaction($client);


        // STUB
        //$transaction->createLead();





    }
}
