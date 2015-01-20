<?php namespace Tuiter;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

/**
 * Class TwitterClient
 * @package Tuiter
 */
class TwitterClient extends Client
{
    /**
     * @param $consumerKey
     * @param $consumerSecret
     * @param $token
     * @param $tokenSecret
     *
     * @return self
     */
    public static function factory(
        $consumerKey,
        $consumerSecret,
        $token,
        $tokenSecret
    ) {
        $client = new self([
            'base_url' => [
                'https://api.twitter.com/{version}/',
                ['version' => 'v1.1']
            ],
            'defaults' => ['auth' => 'oauth']
        ]);

        $oauth = new Oauth1([
            'consumer_key' => $consumerKey,
            'consumer_secret' => $consumerSecret,
            'token' => $token,
            'token_secret' => $tokenSecret
        ]);

        $client->getEmitter()->attach($oauth);

        return $client;
    }
}
