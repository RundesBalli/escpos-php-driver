<?php
require_once(realpath(__DIR__."/..").DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR."driver.php");

$text = 
"Type some text here
and some here

Enter more text.";

printer_setmode(0,0,0,0,0);
foreach (explode("\n", $text) as $val) {
  echo printer_text($val.PHP_EOL);
}
printer_feed(2);
printer_cut();
?>
