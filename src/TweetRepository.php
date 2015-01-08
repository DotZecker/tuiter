<?php namespace Tuiter;

/**
 * Class TweetRepository
 * @package Tuiter
 */
final class TweetRepository
{
    /**
     * @var Tweet[]
     */
    private $tweets;

    /**
     * @param Tweet[] $tweets
     */
    public function __construct(array $tweets)
    {
        $this->tweets = $tweets;
    }

    /**
     * @param bool $are
     *
     * @return self
     */
    public function retweets($are = true)
    {
        return $this->filter(function (Tweet $tweet) use ($are) {
            return $are === $tweet->isRetweet();
        });
    }

    /**
     * @param bool $are
     *
     * @return self
     */
    public function replies($are = true)
    {
        return $this->filter(function (Tweet $tweet) use ($are) {
            return $are === $tweet->isReply();
        });
    }

    /**
     * @param $date
     *
     * @return self
     */
    public function before($date)
    {
        $date = $date instanceof \DateTime ? $date : new \DateTime($date);

        return $this->filter(function (Tweet $tweet) use ($date) {
            return $tweet->createdAt < $date;
        });
    }

    /**
     * @param $date
     *
     * @return self
     */
    public function after($date)
    {
        $date = $date instanceof \DateTime ? $date : new \DateTime($date);

        return $this->filter(function (Tweet $tweet) use ($date) {
            return $tweet->createdAt > $date;
        });
    }

    /**
     * @param $startDate
     * @param $endDate
     *
     * @return self
     */
    public function between($startDate, $endDate)
    {
        $startDate = $startDate instanceof \DateTime
            ? $startDate
            : new \DateTime($startDate);

        $endDate = $endDate instanceof \DateTime
            ? $endDate
            : new \DateTime($endDate);

        return $this->filter(function (Tweet $tweet) use ($startDate, $endDate) {
            return ($tweet->createdAt > $startDate)
               and ($tweet->createdAt < $endDate);
        });
    }

    /**
     * @param $text
     * @param bool $contains
     *
     * @return TweetRepository
     */
    public function contains($text, $contains = true)
    {
        return $this->filter(function (Tweet $tweet) use ($text, $contains) {
            return $contains === (bool) strpos($tweet->text, $text);
        });
    }

    /**
     * @param $text
     *
     * @return self
     */
    public function containsInUrl($text)
    {
        return $this->filter(function (Tweet $tweet) use ($text) {
            foreach ($tweet->expandedUrls as $url) {
                if (strpos($url, $text)) {
                    return true;
                }
            }

            return false;
        });
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->tweets);
    }

    /**
     * @return Tweet[]
     */
    public function get()
    {
        return $this->tweets;
    }

    /**
     * TODO
     */
    public function deleteFromAccount()
    {
        // TODO
    }

    /**
     * @param callable $filter
     *
     * @return self
     */
    public function filter(callable $filter)
    {
        $tweets = array_filter($this->tweets, $filter);
        return new self(array_values($tweets));
    }


}
