Easy Bangla Date
=================
[![Build Status](https://travis-ci.org/ronisaha/easy-bangla-date.png?branch=master)](https://travis-ci.org/ronisaha/easy-bangla-date)
[![HHVM Status](http://hhvm.h4cc.de/badge/ronisaha/easy-bangla-date.svg)](http://hhvm.h4cc.de/package/ronisaha/easy-bangla-date)
[![Coverage Status](https://coveralls.io/repos/ronisaha/easy-bangla-date/badge.svg?branch=master)](https://coveralls.io/r/ronisaha/easy-bangla-date?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ronisaha/easy-bangla-date/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ronisaha/easy-bangla-date/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/ronisaha/easy-bangla-date/v/stable.png)](https://packagist.org/packages/ronisaha/easy-bangla-date)
[![Total Downloads](https://poser.pugx.org/ronisaha/easy-bangla-date/downloads.png)](https://packagist.org/packages/ronisaha/easy-bangla-date)

Utility Library to Display Bangla Date and Time.

Key Features
------------
* Easy to use
* Works same as php native DateTime Class
* Support almost all format option like DateTime class
* Can customize the start of day hour by setting morning option(for BnDateTime)
* Can be used to convert English-Bangla-English date format


Synopsis
-----------

```php
<?php
use EasyBanglaDate\Types\BnDateTime;
use EasyBanglaDate\Types\DateTime as EnDateTime;

require 'autoload.php'

$bongabda = new BnDateTime('2016-04-22 05:26:47 pm', new DateTimeZone('Asia/Dhaka'));
$bongabda->setDate(1398, 1, 1);

echo $bongabda->format('l jS F Y b h:i:s') . PHP_EOL ;
echo $bongabda->enFormat('l jS F Y h:i:s a') . PHP_EOL;
echo $bongabda->getDateTime()->format('l jS F Y b h:i:s'). PHP_EOL;
echo $bongabda->getDateTime()->enFormat('l jS F Y h:i:s A') . PHP_EOL;

```

![Output](/screenshot.jpeg?raw=true "Output")


## Installation/Usage

If you're using Composer to manage dependencies, you can include the following
in your composer.json file:

    "require": {
        "ronisaha/easy-bangla-date": "dev-master"
    }

Then, after running `composer update` or `php composer.phar update`, you can
load the class using Composer's autoloading:

```php
require 'vendor/autoload.php';
```

Otherwise, you can simply require the given `autoload.php` file:

```php
require_once 'PATH_TO_LIBRARY/autoload.php';

```

And in either case, I'd suggest using an alias for `EasyBanglaDate\Types\DateTime` Class to distinguish between native DateTime Class.

```php
use EasyBanglaDate\Types\DateTime as EnDateTime;
```

## Methods/Features

Both `EasyBanglaDate\Types\DateTime` and `EasyBanglaDate\Types\BnDateTime` has the member functions as native DateTime class.

##### DateTime
* you can use `enFormat` function to get output in english.

##### BnDateTime
* `EasyBanglaDate\Types\BnDateTime` got extra method setMorning to define a hour for start of day. By default day start at 6.
* Along with all format options of native DateTime class, we have extra option `b` which will print ('ভোর', 'সকাল', 'দুপুর', 'বিকাল', 'সন্ধ্যা', 'রাত')
* Use `setDate($year, $month, $day)` to set bengali date
* `getDateTime` method will return object of `EasyBanglaDate\Types\DateTime` for current object.


## Cookbook

##### English date in Bangla

```php
<?php
use EasyBanglaDate\Types\DateTime;

require 'autoload.php'

$date = new DateTime('now', new DateTimeZone('Asia/Dhaka'));

echo $date->format('l jS F Y b h:i:s');

```

##### Native format functionality

```php
<?php
use EasyBanglaDate\Types\DateTime;

require 'autoload.php'

$date = new DateTime('now', new DateTimeZone('Asia/Dhaka'));

echo $date->enFormat('l jS F Y b h:i:s');

```

##### Convert English to Bangla Date

```php
<?php
use EasyBanglaDate\Types\BnDateTime;

require 'autoload.php'

$bongabda = new BnDateTime('now', new DateTimeZone('Asia/Dhaka'));

echo $bongabda->format('l jS F Y b h:i:s');

```

##### Convert Bangla to English Date

```php
<?php
use EasyBanglaDate\Types\BnDateTime;

require 'autoload.php'

$bongabda = new BnDateTime('now', new DateTimeZone('Asia/Dhaka'));

echo $bongabda->format('l jS F Y b h:i:s');
//Set Bengali date
$bongabda->setDate(1405,1,1);
//Get english date in bangla
echo $bongabda->getDateTime()->format('l jS F Y b h:i:s');
//Get english date in english
echo $bongabda->getDateTime()->enFormat('l jS F Y h:i:s');

```

## Contributing to Library

If you find a bug or want to add a feature to EasyBanglaDate, great! In order to make it easier and quicker for me to verify and merge changes in, it would be amazing if you could follow these few basic steps:

1. Fork the project.
2. **Branch out into a new branch. `git checkout -b name_of_new_feature_or_bug`**
3. Make your feature addition or bug fix.
4. **Add tests for it. This is important so I don’t break it in a future version unintentionally.**
5. Commit.
6. Send me a pull request!


### Some Similar PHP libraries you may like to see:

* https://github.com/mhmithu/bangla-date-and-time
* https://github.com/tareq1988/bangla-date
* https://github.com/shahalom/translate-date-in-bangla
