Easy Bangla Date
=================
[![Build Status](https://travis-ci.org/ronisaha/easy-bangla-date.png?branch=master)](https://travis-ci.org/ronisaha/easy-bangla-date)
[![HHVM Status](http://hhvm.h4cc.de/badge/ronisaha/easy-bangla-date.svg)](http://hhvm.h4cc.de/package/ronisaha/easy-bangla-date)
[![Coverage Status](https://coveralls.io/repos/ronisaha/easy-bangla-date/badge.png)](https://coveralls.io/r/ronisaha/easy-bangla-date)
[![Total Downloads](https://poser.pugx.org/ronisaha/easy-bangla-date/downloads.png)](https://packagist.org/packages/ronisaha/easy-bangla-date)

Utility Library For Bangla Date and Time. The conversion logic got from https://github.com/mhmithu/bangla-date-and-time

Basic usage
-----------

```php
<?php
use EasyBanglaDate\BnDateTime;
use EasyBanglaDate\DateTime as EnDateTime;

$gregorian = new EnDateTime('now', new DateTimeZone('Asia/Dhaka'));
$bongabda = new BnDateTime('now', new DateTimeZone('Asia/Dhaka'));

echo $gregorian->format('l jS F Y b h:i:s');
echo $bongabda->format('l jS F Y b h:i:s');

```

Output
-------

![Alt text](/screenshot.jpeg?raw=true "Output")