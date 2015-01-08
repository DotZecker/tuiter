Tuiter - Easy Twitter Archive Manager
=====================================
[![Build Status](https://scrutinizer-ci.com/g/DotZecker/tuiter/badges/build.png?b=master)](https://travis-ci.org/DotZecker/tuiter)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/DotZecker/tuiter/badges/quality-score.png?b=master)](https://travis-ci.org/DotZecker/tuiter)

## 1. Installation

The recommended way to install it is through [Composer](http://getcomposer.org). Run the following command in order to install it:

```sh
composer require dotzecker/tuiter
```

## 2. Usage
The api is very simple and intuitive:
```php
use Tuiter\Tuiter;

// Let's load our archive
$tweets = Tuiter::fromArchive(__DIR__ . 'your/awesome/path/to/tweets.csv');
```

Now you are able to manager your tweets in a fluent way, for example:
```php
$tweets->retweets()->before('2014-05-20')->get();
```

This is the list of filters avaliables:
* `->retweets($are = true)`
* `->replies($are = true)`
* `->before($date)`
* `->after($date)`
* `->between($startDate, $endDate)`
* `->contains($text, $contains = true)`
* `->containsInUrl($text)`

## 3. TODO
* Delete tweets in your twitter account
