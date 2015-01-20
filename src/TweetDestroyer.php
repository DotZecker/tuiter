<?php namespace Tuiter;

use GuzzleHttp\Exception\RequestException;

/**
 * Class TweetDestroyer
 * @package Tuiter
 */
final class TweetDestroyer
{
    /**
     * @var TwitterClient
     */
    private $client;

    /**
     * @param TwitterClient $client
     */
    public function __construct(TwitterClient $client)
    {
        $this->client = $client;
    }

    /**
     * Factory
     *
     * @param $consumerKey
     * @param $consumerSecret
     * @param $token
     * @param $tokenSecret
     *
     * @return self
     */
    public static function withParams(
        $consumerKey,
        $consumerSecret,
        $token,
        $tokenSecret
    ) {
        return new self(TwitterClient::factory(
            $consumerKey, $consumerSecret, $token, $tokenSecret
        ));
    }

    /**
     * @param $tweetId
     *
     * @return mixed
     */
    public function destroy($tweetId)
    {
        $tweetId = $this->extractTweetId($tweetId);

        try {
            $response = $this->client->post("statuses/destroy/{$tweetId}.json");
        } catch (RequestException $e) {
            $response = $e->getResponse();
        }

        return $response->json();
    }

    /**
     * @param $tweetId
     *
     * @return string
     */
    private function extractTweetId($tweetId)
    {
        return $tweetId instanceof Tweet ? $tweetId->id : $tweetId;
    }
}
