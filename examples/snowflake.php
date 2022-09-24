<?php
require_once(realpath(__DIR__."/..").DIRECTORY_SEPARATOR."escpos-driver.php");

//see: https://ascii.co.uk/art/snowflakes No. 6
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
/        \\";

$printer::setAlignment('center');
$printer::bold(true);

foreach (explode("\n", $text) as $val) {
  $printer::text($val);
}
?>
