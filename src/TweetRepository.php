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
     * @param bool $show
     *
     * @return TweetRepository
     */
    public function before($date, $show = true)
    {
        $date = $this->formatDate($date);

        return $this->filter(function (Tweet $tweet) use ($date, $show) {
            return $show === $tweet->createdAt < $date;
        });
    }

    /**
     * @param $date
     *
     * @return self
     */
    public function after($date)
    {
        return $this->before($date, false);
    }

    /**
     * @param $startDate
     * @param $endDate
     *
     * @return self
     */
    public function between($startDate, $endDate)
    {
        $startDate = $this->formatDate($startDate);
        $endDate = $this->formatDate($endDate);

        return $this->filter(function (Tweet $tweet) use ($startDate, $endDate) {
            return ($tweet->createdAt > $startDate)
               and ($tweet->createdAt < $endDate);
        });
    }

    /**
     * @param $text
     * @param bool $contains
     *
     * @return self
     */
    public function contains($text, $contains = true)
    {
        return $this->filter(function (Tweet $tweet) use ($text, $contains) {
            return $contains === (bool) strpos($tweet->text, $text);
        });
    }

    /**
     * @param $text
     * @param bool $contains
     *
     * @return self
     */
    public function containsInUrl($text, $contains = true)
    {
        return $this->filter(function (Tweet $tweet) use ($text, $contains) {
            $urls = implode(',', $tweet->expandedUrls);

            return $contains === (bool) strpos($urls, $text);
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

    /*public function deleteFromAccount()
    {
        // TODO
    }*/

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

    /**
     * @param $date
     *
     * @return \DateTime
     */
    private function formatDate($date)
    {
        return $date instanceof \DateTime ? $date : new \DateTime($date);
    }
}
