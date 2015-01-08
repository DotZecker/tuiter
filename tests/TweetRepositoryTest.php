<?php

use Tuiter\Tweet;
use Tuiter\TweetRepository;

class TweetRepositoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldFilterByRetweets()
    {
        $tweetRepository = $this->getRepository([
            $this->normalTweet(),
            $this->normalTweet(),
            $this->retweetedTweet()
        ]);

        $retwittedTweets = $tweetRepository->retweets()->get();
        $this->assertEquals(1, count($retwittedTweets));

        $nonRetwittedTweets = $tweetRepository->retweets(false)->get();
        $this->assertEquals(2, count($nonRetwittedTweets));
    }

    /**
     * @test
     */
    public function itShouldFilterByReplies()
    {
        $tweetRepository = $this->getRepository([
            $this->normalTweet(),
            $this->normalTweet(),
            $this->replyTweet(),
        ]);

        $retwittedTweets = $tweetRepository->replies()->get();
        $this->assertEquals(1, count($retwittedTweets));

        $nonRetwittedTweets = $tweetRepository->replies(false)->get();
        $this->assertEquals(2, count($nonRetwittedTweets));
    }

    /**
     * @test
     */
    public function itShouldFilterByTime()
    {
        $tweetRepository = $this->getRepository([
            $this->normalTweet('2009-01-01 12:12:12 +0000'),
            $this->normalTweet('2011-01-01 12:12:12 +0000'),
            $this->normalTweet('2012-01-01 12:12:12 +0000'),
            $this->normalTweet('2014-01-01 12:12:12 +0000')
        ]);

        $beforeTweets = $tweetRepository->before('2013-01-01')->get();

        $this->assertEquals(3, count($beforeTweets));

        $afterTweets = $tweetRepository->after('2013-01-01')->get();
        $this->assertEquals(1, count($afterTweets));

        $betweenTweets = $tweetRepository->between('2010-01-01', '2013-01-01')->get();
        $this->assertEquals(2, count($betweenTweets));
    }

    /**
     * @test
     */
    public function itShouldFilterByText()
    {
        $tweet1 = $this->normalTweet(null, 'I am rockin baby');
        $tweet2 = $this->normalTweet(null, 'Baby baby baby ohh');
        $tweet3 = $this->normalTweet(null, 'The Lord Of Rings');

        $tweetRepository = $this->getRepository([
            $tweet1, $tweet2, $tweet3
        ]);

        $babyTweets = $tweetRepository->contains('baby');
        $this->assertSame([$tweet1, $tweet2], $babyTweets->get());

        $noBabyTweets = $tweetRepository->contains('baby', false);
        $this->assertSame([$tweet3], $noBabyTweets->get());
    }

    /**
     * @test
     */
    public function itShouldFilterByUrl()
    {
        $tweet1 = $this->tweetWithUrls('http://rafa.im');
        $tweet2 = $this->tweetWithUrls('http://rafa.sexy,http://twitter.com/dotzecker');
        $tweet3 = $this->tweetWithUrls('http://twitter.com/dotzecker');

        $tweetRepository = $this->getRepository([
            $tweet1, $tweet2, $tweet3
        ]);

        $twitterTweets = $tweetRepository->containsInUrl('twitter');
        $this->assertSame([$tweet2, $tweet3], $twitterTweets->get());
    }

    /**
     * @test
     */
    public function itShouldCountTheTotalTweets()
    {
        $tweetRepository = $this->getRepository([
            $this->normalTweet(),
            $this->normalTweet(),
            $this->replyTweet(),
        ]);

        $this->assertEquals(3, $tweetRepository->count());
    }


    private function normalTweet($timestamp = null, $text = null)
    {
        return $this->makeTweet([
            'tweet_id' => '1',
            'in_reply_to_status_id' => null,
            'in_reply_to_user_id' => null,
            'timestamp' => $timestamp ?: '2014-01-06 15:18:05 +0000',
            'source' => '<a href="http://twitter.com/download/android" rel="nofollow">Twitter for Android</a>',
            'text' => $text ?: 'I am the very best!',
            'retweeted_status_id' => null,
            'retweeted_status_user_id' => null,
            'retweeted_status_timestamp' => null,
            'expanded_urls' => null
        ]);
    }

    private function replyTweet()
    {
        return $this->makeTweet([
            'tweet_id' => '2',
            'in_reply_to_status_id' => '1',
            'in_reply_to_user_id' => '1',
            'timestamp' => '2014-01-06 15:19:23 +0000',
            'source' => '<a href="http://twitter.com/download/android" rel="nofollow">Twitter for Android</a>',
            'text' => 'NO!',
            'retweeted_status_id' => null,
            'retweeted_status_user_id' => null,
            'retweeted_status_timestamp' => null,
            'expanded_urls' => null
        ]);
    }

    private function retweetedTweet()
    {
        return $this->makeTweet([
            'tweet_id' => '3',
            'in_reply_to_status_id' => null,
            'in_reply_to_user_id' => null,
            'timestamp' => '2015-01-07 12:17:01 +0000',
            'source' => '<a href="http://twitter.com/download/android" rel="nofollow">Twitter for Android</a>',
            'text' => 'RT @dotzecker: I am the very best!',
            'retweeted_status_id' => '1',
            'retweeted_status_user_id' => '1',
            'retweeted_status_timestamp' => '2014-01-06 15:18:05 +0000',
            'expanded_urls' => null,
        ]);
    }

    private function tweetWithUrls($urls)
    {
        return $this->makeTweet([
            'tweet_id' => '4',
            'in_reply_to_status_id' => null,
            'in_reply_to_user_id' => null,
            'timestamp' => '2015-01-07 20:28:25 +0000',
            'source' => '<a href="http://twitter.com/download/android" rel="nofollow">Twitter for Android</a>',
            'text' => 'This moonpig security thing is just too extreme to be true: http://t.co/jjJZ5GhaAh',
            'retweeted_status_id' => null,
            'retweeted_status_user_id' => null,
            'retweeted_status_timestamp' => null,
            'expanded_urls' => $urls
        ]);
    }

    private function makeTweet(array $args)
    {
        return new Tweet(
            $args['tweet_id'],
            $args['text'],
            $args['source'],
            $args['timestamp'],
            $args['in_reply_to_status_id'],
            $args['retweeted_status_id'],
            $args['expanded_urls']
        );
    }

    private function getRepository(array $tweetRepository)
    {
        return new TweetRepository($tweetRepository);
    }
}
