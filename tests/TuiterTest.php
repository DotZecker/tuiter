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
        $reader = $this
            ->getMockBuilder('League\Csv\Reader')
            ->disableOriginalConstructor()
            ->getMock();

        $reader->method('fetchAssoc')->willReturn([
            [
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
            ]
        ]);

        $this->tuiter = new Tuiter($reader);
    }

    /**
     * @test
     */
    public function itShouldReturnTheTweetRepository()
    {
        $this->assertInstanceOf(
            'Tuiter\TweetRepository', $this->tuiter->tweets()
        );
    }

}
