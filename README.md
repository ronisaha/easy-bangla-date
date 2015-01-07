Easy Bangla Date
=================

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