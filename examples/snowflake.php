<?php
require_once(realpath(__DIR__."/..").DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR."driver.php");

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

printer_center();

printer_setmode(0,1,0,0,0);
foreach (explode("\n", $text) as $val) {
  echo printer_text($val.PHP_EOL);
}
printer_feed(2);
printer_cut();
?>
