<?php

use Tuiter\Tuiter;

class TuiterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Tuiter
     */
    private $tuiter;

    protected function setUp()
    {
        $reader = $this->getReader();

        $this->tuiter = new Tuiter($reader);
    }

    /**
     * @test
     */
    public function itShouldReturnTheTweetRepository()
    {
        $reader = $this->getReader();

        $this->assertInstanceOf(
            'Tuiter\TweetRepository', $this->tuiter->tweets()
        );

        $this->assertInstanceOf(
            'Tuiter\TweetRepository', Tuiter::fromArchive($reader)
        );
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getReader()
    {
        $reader = $this->getMockBuilder('League\Csv\Reader')->disableOriginalConstructor()->getMock();

        $reader->method('fetchAssoc')->willReturn([[
            'tweet_id' => '1',
            'in_reply_to_status_id' => '',
            'in_reply_to_user_id' => '',
            'timestamp' => '2014-01-06 15:18:05 +0000',
            'source' => '<a href="http://twitter.com/download/android" rel="nofollow">Twitter for Android</a>',
            'text' => 'I am the very best!',
            'retweeted_status_id' => '',
            'retweeted_status_user_id' => '',
            'retweeted_status_timestamp' => '',
            'expanded_urls' => ''
        ]]);

        return $reader;
    }

}
