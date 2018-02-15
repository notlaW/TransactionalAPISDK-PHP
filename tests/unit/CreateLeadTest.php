<?php

namespace SixCRM\Test;

use SixCRM\Transaction;
use SixCRM\Test\BaseTest;

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
