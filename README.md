# :printer::page_facing_up: escpos-php-driver
A driver for Epson ESC/POS thermal printers written in PHP.<br>
My printer is the [Hoinprinter 80mm Thermal Printer](http://hoinprinter.com/en/products/show/80mm-Thermal-Receipt-Printer-2).

## Usage:
Include the `/escpos-driver.php` file in your project.  
The printer is initialized inside the `escpos-driver.php` file and is useable via the `$printer` variable. It resets itself when the driver is included. At the end the printer feeds two lines and cuts the print.

## Functions:
For detailed documentation, please refer to the commented functions in the driver file.
- `$printer::setAlignment($alignment)` - Sets the text alignment.
  - Possible values are: `left, l, center, c, right, r`
- `$printer::feed($n)` - Feeds n blank lines.
- `$printer::cut()` - Cuts the paper.
- `$printer::beep($count, $length)` - Let the printer beep.
  - Possible values are: 1-9
- `$printer::reverseColors($bool)` - Prints white text on black background (true) or normal (false).
- Text styles (default values in *italic*):
  - `$printer::small($bool)` - Small text (true) or *normal text (false)*.
  - `$printer::bold($bool)` - Bold text (true) or *normal text (false)*.
  - `$printer::doubleHeight($bool)` - Double height text (true) or *normal height text (false)*.
  - `$printer::doubleWidth($bool)` - Double width text (true) or *normal width text (false)*.
  - `$printer::underline($bool)` - Underlined text (true) or *not underlined text (false)*.
- `$printer::barcode($height, $type, $content)` - Print barcode with `$height` dots height.
  - Available types: UPC-A (0), UPC-E (1), EAN13 (2), EAN8 (3), CODE39 (4), ITF (5), CODABAR (6)
- `$printer::horizontalLine($char)` - Prints a horizontal line consisting of $char.
- `$printer::text($text)` - Prints text.

## Examples:
Please refer to the examples directory.

## Send to printer:
You need to know the IP-address of your printer and print via `netcat`:  
$ `php yourPrintFile.php | nc ip.of.your.printer 9100 -w 1`

### Useful links to ESCPOS documentation:
- [escp2ref.pdf](https://files.support.epson.com/pdf/general/escp2ref.pdf) from Epson
- [Commande ESCPOS.pdf](https://aures-support.com/DATA/drivers/Imprimantes/Commande%20ESCPOS.pdf) from aures-support
