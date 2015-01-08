<?php namespace Tuiter;

/**
 * Class Tweet
 * @package Tuiter
 */
final class Tweet
{
    /**
     * @var
     */
    public $id;

    /**
     * @var
     */
    public $text;

    /**
     * @var
     */
    public $source;

    /**
     * @var \DateTime
     */
    public $createdAt;

    /**
     * @var null
     */
    public $inReplyToStatusId;

    /**
     * @var null
     */
    public $retweetedStatusId;

    /**
     * @var array
     */
    public $expandedUrls;

    /**
     * @param $id
     * @param $text
     * @param $source
     * @param $createdAt
     * @param null $inReplyToStatusId
     * @param null $retweetedStatusId
     * @param null $expandedUrls
     */
    public function __construct(
        $id,
        $text,
        $source,
        $createdAt,
        $inReplyToStatusId = null,
        $retweetedStatusId = null,
        $expandedUrls = null
    ) {
        $this->id = $id;
        $this->text = $text;
        $this->source = $source;
        $this->createdAt = new \DateTime($createdAt);
        $this->inReplyToStatusId = $inReplyToStatusId;
        $this->retweetedStatusId = $retweetedStatusId;
        $this->expandedUrls = explode(',', $expandedUrls);
    }

    /**
     * @return bool
     */
    public function isRetweet()
    {
        return $this->retweetedStatusId !== null;
    }

    /**
     * @return bool
     */
    public function isReply()
    {
        return $this->inReplyToStatusId !== null;
    }

}
