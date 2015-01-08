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
     * @param mixed $path
     *
     * @return TweetRepository
     */
    public static function fromArchive($path)
    {
        $reader = $path instanceof Reader ? $path : new Reader($path);

        $self = new self($reader);

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
     * @param mixed $default
     *
     * @return mixed
     */
    private function getFrom($array, $key, $default = null)
    {
        return (array_key_exists($key, $array) && $array[$key] !== '')
            ? $array[$key]
            : $default;
    }
}
