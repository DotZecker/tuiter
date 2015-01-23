Tuiter - Easy Twitter Archive Manager
=====================================
[![Build Status](https://scrutinizer-ci.com/g/DotZecker/tuiter/badges/build.png?b=master)](https://travis-ci.org/DotZecker/tuiter?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/DotZecker/tuiter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/DotZecker/tuiter/?branch=master)
[![Coverage Status](https://coveralls.io/repos/DotZecker/tuiter/badge.png)](https://coveralls.io/r/DotZecker/tuiter)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/4bd81f57-0612-47d1-9742-513bfe082c53/mini.png)](https://insight.sensiolabs.com/projects/4bd81f57-0612-47d1-9742-513bfe082c53)

## 1. Installation
The recommended way to install it is through [Composer](http://getcomposer.org). Run the following command in order to install it:

```sh
composer require dotzecker/tuiter
```

## 2. Usage
The API is very simple and intuitive:
```php
use Tuiter\Tuiter;

// Let's load our archive
$tweets = Tuiter::fromArchive(__DIR__ . '/your/awesome/path/to/tweets.csv');
```

Now you are able to manage your tweets in a fluent way, for example:
```php
$unwantedTweets = $tweets->retweets()->before('2014-05-20')->get();
```

This is the list of available filters:
* `->retweets($are = true)`
* `->replies($are = true)`
* `->before($date)`
* `->after($date)`
* `->between($startDate, $endDate)`
* `->contains($text, $contains = true)`
* `->containsInUrl($text)`


## 3. Delete Tweets
In order to delete tweets from your timeline, the implementation is:
```php
use Tuiter\TweetDestroyer;

$destroyer = TweetDestroyer::withParams(
    'Consumer Key', 'Consumer Secret', 'Access Token', 'Access Token Secret'
);

foreach ($unwantedTweets as $tweet) {
    $destroyer->destroy($tweet);
}
```
