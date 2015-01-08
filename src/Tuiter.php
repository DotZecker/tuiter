<?php namespace Tuiter;

use League\Csv\Reader;

/**
 * Class Tuiter
 * @package Tuiter
 */
final class Tuiter
{
    /**
     * @var TweetRepository
     */
    private $tweetRepository;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $tweets = [];
        foreach ($reader->fetchAssoc() as $tweet) {
            $tweets[] = new Tweet(
                $this->getFrom($tweet, 'tweet_id'),
                $this->getFrom($tweet, 'text'),
                $this->getFrom($tweet, 'source'),
                $this->getFrom($tweet, 'timestamp'),
                $this->getFrom($tweet, 'in_reply_to_status_id'),
                $this->getFrom($tweet, 'retweeted_status_id'),
                $this->getFrom($tweet, 'expanded_urls')
            );
        }

        $this->tweetRepository = new TweetRepository($tweets);
    }

    /**
     * @param $path
     *
     * @return TweetRepository
     */
    public static function fromArchive($path)
    {
        $self = new self(new Reader($path));

        return $self->tweets();
    }

    /**
     * @return TweetRepository
     */
    public function tweets()
    {
        return $this->tweetRepository;
    }

    /**
     * @param $array
     * @param string $key
     * @param null $default
     *
     * @return null
     */
    private function getFrom($array, $key, $default = null)
    {
        return (array_key_exists($key, $array) and $array[$key] !== '')
            ? $array[$key]
            : $default;
    }
}
