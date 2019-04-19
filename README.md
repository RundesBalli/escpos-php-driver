# escpos-php-driver
A driver for Epson ESC/POS thermal printers written in PHP.

## Usage:
Include the `/src/driver.php` file in your project.
The printer is initialized inside the `driver.php` file.

To output some text you have to `echo` your text with the `printer_text('your text here');` function.

## Example:

>print.php

```php
<?php
require_once("/path/to/driver.php");
echo printer_text("foo bar");
printer_feed(2);
printer_cut();
?>
```

You need to know the IP-address of your printer and print via `netcat`:
$ `php print.php | nc ip.of.your.printer 9100 -w 1`

## Functions:

To check out every available function, please refer to the comments in `driver.php`