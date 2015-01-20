<?php

use Tuiter\TwitterClient;

class TwitterClientTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function itIsInitializableByFactoryMethod()
    {
        $this->assertInstanceOf('Tuiter\TwitterClient', TwitterClient::factory(
            '1234', '1234', '1234', '1234'
        ));
    }
}
