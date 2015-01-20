<?php

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use Tuiter\Tweet;
use Tuiter\TweetDestroyer;

class TweetDestroyerTest extends PHPUnit_Framework_TestCase
{
    private $client;

    protected function setUp()
    {
        $client = $this
            ->getMockBuilder('Tuiter\TwitterClient')
            ->disableOriginalConstructor()
            ->getMock();

        $this->client = $client;
    }

    /** @test */
    public function itIsInitializableByFactoryMethod()
    {
        $this->assertInstanceOf(
            'Tuiter\TweetDestroyer',
            TweetDestroyer::withParams('1234', '1234', '1234', '1234')
        );
    }

    /**
     * @test
     * @link https://dev.twitter.com/rest/reference/post/statuses/destroy/%3Aid
     */
    public function itIsAbleToDestroyASingleTweet()
    {
        $tweetId = '240854986559455234';

        $client = $this->client;
        $client
            ->method('post')
            ->with($this->equalTo("statuses/destroy/{$tweetId}.json"))
            ->willReturn(new Response(200, [], Stream::factory(
                file_get_contents(__DIR__ . '/result/ok.json')
            )));

        $tweetDestroyer = new TweetDestroyer($client);

        $this->assertArrayHasKey('id', $tweetDestroyer->destroy($tweetId));
        $this->assertArrayHasKey('id', $tweetDestroyer->destroy(
            $this->tweetById($tweetId)
        ));
    }

    /** @test */
    public function itFailsWhenTweetIsNotFound()
    {
        $tweetId = '1234';

        $client = $this->client;
        $client
            ->method('post')
            ->with($this->equalTo("statuses/destroy/{$tweetId}.json"))
            ->will($this->throwException(new RequestException(
                "Url Not Found",
                new Request(
                    'POST',
                    "https://api.twitter.com/1.1/statuses/destroy/{$tweetId}.json"
                ),
                new Response(404)
            )));

        $tweetDestroyer = new TweetDestroyer($client);

        $this->assertNull($tweetDestroyer->destroy($tweetId));
    }

    private function tweetById($tweetId)
    {
        return new Tweet(
            $tweetId,
            'text',
            'source',
            '2014-01-01',
            'in_reply_to_status_id',
            'retweeted_status_id',
            'expanded_urls'
        );
    }
}
