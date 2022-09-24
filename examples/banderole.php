<?php
/**
 * Snowflake ASCII Art with some text, multiplied 10 times to wrap around presents :-)
 * 
 * @see https://ascii.co.uk/art/snowflakes
 */
require_once(realpath(__DIR__."/..").DIRECTORY_SEPARATOR."escpos-driver.php");

$text = 
"\__    __/
/_/ /\ \_\
__ \ \/ / __
\_\_\/\/_/_/
__/\___\_\/_/___/\__
\/ __/_/\_\__ \/
/_/ /\/\ \_\
__/ /\ \__
\_\ \/ /_/
/        \\

Your
Text
Here";

$printer::setAlignment('center');
$printer::bold(true);

for($i=0;$i<10;$i++) {
  foreach (explode("\n", $text) as $val) {
    $printer::text($val);
  }
  $printer::feed(2);
}
?>
