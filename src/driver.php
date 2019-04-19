<?php
/**
 * PHP-Driver for ESC/POS Thermalprinters.
 * 
 * PHP Version 5, 7
 * 
 * @author    RundesBalli <rundesballi@rundesballi.com>
 * @copyright 2019 RundesBalli
 * @version   1.0.0 - 06. JAN 2019
 * @see       http://content.epson.de/fileadmin/content/files/RSD/downloads/escpos.pdf
 * @see       http://www.asciitable.com/
 * @license   MIT-License
 */

/**
 * ASCII-constants for initiating the commands.
 * ESC = ASCII-Escape
 * GS = ASCII-Group-Separator
 * NUL = ASCII-NULL
 */
const ESC = "\x1b";
const GS = "\x1d";
const NUL = "\x00";

/**
 * Initialize the printer.
 * 
 * @return NULL Prints the prepared ASCII-string instead.
 */
function printer_reset() {
	echo ESC."@";
}

/**
 * Sets the text-alignment to left.
 * 
 * @return NULL Prints the prepared ASCII-string instead.
 */
function printer_left() {
	echo ESC."a".chr(0);
}

/**
 * Sets the text-alignment to center.
 * 
 * If the number of chars is uneven, the text will be printed further left than right.
 * For example:
 * ··Lorem ipsum dolor sit amet, consetetur sadi···
 * NOT:
 * ···Lorem ipsum dolor sit amet, consetetur sadi··
 * 
 * @return NULL Prints the prepared ASCII-string instead.
 */
function printer_center() {
	echo ESC."a".chr(1);
}

/**
 * Sets the text-alignment to right.
 * 
 * The text foobar will be printed as follows:
 * ··········································foobar
 * NOT:
 * ··········································raboof
 * 
 * @return NULL Prints the prepared ASCII-string instead.
 */
function printer_right() {
	echo ESC."a".chr(2);
}

/**
 * Feeds n blank lines.
 * 
 * @param int Number of lines to be feeded.
 * 
 * @return NULL Prints the prepared ASCII-string instead and feeds n lines.
 */
function printer_feed($lines = 1) {
	echo ESC."d".chr($lines);
}

/**
 * Cuts the Paper.
 * 
 * @return NULL Prints the prepared ASCII-string instead.
 */
function printer_cut() {
	echo GS."VA".chr(3);
}

/**
 * Let the printer beep.
 * 
 * @param int Number of beeps. Possible values are from 1 to 9.
 * @param int Length of every beep. Possible values are from 1 to 9.
 * 
 * @return NULL Prints the prepared ASCII-string instead and beeps.
 */
function printer_beep($count = 3, $length = 2) {
	echo ESC."B".chr($count).chr($length);
}

/**
 * The following text will be printed white on black background.
 * 
 * @return NULL Prints the prepared ASCII-string instead.
 */
function printer_bw() {
	echo GS."B".chr(1);
}

/**
 * The following text will be printed black on white background.
 * 
 * @return NULL Prints the prepared ASCII-string instead.
 */
function printer_wb() {
	echo GS."B".chr(0);
}

/**
 * Sets the printmode for the following text.
 * 
 * This function is for setting the different styling options for the output.
 * 
 * @param int	Sets the used font (possible values: 0 and 1)
 * @param int Sets the font-weight to bold  (possible values: 0 and 1)
 * @param int Sets the font-height twice as high as normal (possible values: 0 and 1)
 * @param int Sets the font-width twice as wide as normal (possible values: 0 and 1)
 * @param int Underlines the text (possible values: 0 and 1)
 * 
 * @return NULL Prints the prepared ASCII-string instead.
 */
function printer_setmode($font = 0, $bold = 0, $doubleheight = 0, $doublewidth = 0, $underline = 0) {
	$options = 0;
	if($font == 1) {
		$options = $options+1;
	}
	if($bold == 1) {
		$options = $options+8;
	}
	if($doubleheight == 1) {
		$options = $options+16;
	}
	if($doublewidth == 1) {
		$options = $options+32;
	}
	if($underline == 1) {
		$options = $options+128;
	}
	echo ESC."!".chr($options);
}

/**
 * Prints every printmode.
 * 
 * See printer_setmode() for more.
 * 
 * @return NULL Prints the prepared output directly instead.
 */
function printer_printmodes() {
	for($e=0;$e<2;$e++) {
		for($d=0;$d<2;$d++) {
			for($c=0;$c<2;$c++) {
				for($b=0;$b<2;$b++) {
					for($a=0;$a<2;$a++) {
						printer_setmode($a, $b, $c, $d, $e);
						echo "FONT: $a; BOLD: $b; DH: $c; DW: $d, UL: $e".PHP_EOL;
					}
				}
			}
		}
	}
}

/**
 * Prints every printable Char.
 * 
 * See http://www.asciitable.com/ for all ASCII Chars.
 * Non-printable ASCII-characters are omitted (therefore start at 32).
 * 
 * To print one char there are eight spaces needed. In total 48 spaces per line.
 * Therefore six Chars per Line. Breaks after six Chars and ends with a newline.
 * For example:
 * 218:·┌··219:·█··220:·▄·· and so on.
 * 
 * @return NULL Prints the output directly instead.
 */
function printer_printchars() {
	$innercounter = 0;
	for($i=32;$i<256;$i++) {
		if($i<100) {
			echo " ";
		}
		echo $i.": ".chr($i);
		$innercounter++;
		if($innercounter == 6 OR $i==255) {
			$innercounter = 0;
			echo PHP_EOL;
		} else {
			echo "  ";
		}
	}
}

/**
 * Prints a barcode.
 * 
 * @param int			Height of the barcode in dots.
 * @param int			Barcodetype. See below for types.
 * @param string	Barcodecontent.
 * 
 * Barcodetype:
 * 1: UPC-A
 * 2: UPC-E
 * 3: JAN13 (EAN13)
 * 4: JAN8 (EAN8)
 * 5: ITF
 * 6: CODABAR
 * 
 * For more information about possible barcodecontents visit:
 * http://content.epson.de/fileadmin/content/files/RSD/downloads/escpos.pdf on page 14
 * 
 * @return NULL Prints the prepared Barcode-ASCII-string instead.
 */
function printer_barcode($dots = 120, $barcodetype = 4, $content = "") { //Barcode drucken
	echo GS."h".chr($dots).GS."k".chr($barcodetype).$content.NUL;
}

/**
 * Prints prepared text.
 * 
 * ASCII-chars outside the range of [0-9a-zA-Z] can not be printed directly.
 * This function converts all chars outside the above-mentioned range to the correct ASCII-char.
 * 
 * chr(132) will be "ä".
 * 
 * @param string The text to be converted. Will be cropped to 48 characters.
 * 
 * @return string The converted string. Must be outputted seperately via echo.
 */
function printer_text($text = NULL) {
	$search  = [' ',      '!',      '"',      '#',      '$',      '%',      '&',      "'",      '(',      ')',      '*',      '+',      ',',      '-',      '.',      '/',      '0',      '1',      '2',      '3',      '4',      '5',      '6',      '7',      '8',      '9',      ':',      ';',      '<',      '=',      '>',      '?',      '@',      'A',      'B',      'C',      'D',      'E',      'F',      'G',      'H',      'I',      'J',      'K',      'L',      'M',      'N',      'O',      'P',      'Q',      'R',      'S',      'T',      'U',      'V',      'W',      'X',      'Y',      'Z',      '[',      '\\',     ']',      '^',      '_',      '`',      'a',      'b',      'c',      'd',      'e',      'f',      'g',      'h',      'i',      'j',      'k',      'l',      'm',      'n',      'o',      'p',      'q',      'r',      's',      't',      'u',      'v',      'w',      'x',      'y',      'z',      '{',      '|',      '}',      '~',      'Ç',      'ü',      'é',      'â',      'ä',      'à',      'å',      'ç',      'ê',      'ë',      'è',      'ï',      'î',      'ì',      'Ä',      'Å',      'É',      'æ',      'Æ',      'ô',      'ö',      'ò',      'û',      'ù',      'ÿ',      'Ö',      'Ü',      'ø',      '£',      'Ø',      '×',      'ƒ',      'á',      'í',      'ó',      'ú',      'ñ',      'Ñ',      'ª',      'º',      '¿',      '®',      '¬',      '½',      '¼',      '­¡',      '«',      '»',      '░',      '▒',      '▓',      '│',      '┤',      'Á',      'Â',      'À',      '©',      '╣',      '║',      '╗',      '╝',      '¢',      '¥',      '┐',      '└',      '┴',      '┬',      '├',      '─',      '┼',      'ã',      'Ã',      '╚',      '╔',      '╩',      '╦',      '╠',      '═',      '╬',      '¤',      'ð',      'Ð',      'Ê',      'Ë',      'È',      'ı',      'Í',      'Î',      'Ï',      '┘',      '┌',      '█',      '▄',      '¦',      'Ì',      '▀',      'Ó',      'ß',      'Ô',      'Ò',      'õ',      'Õ',      'µ',      'þ',      'Þ',      'Ú',      'Û',      'Ù',      'ý',      'Ý',      '¯',      '´',      '≡',      '±',      '‗',      '¾',      '¶',      '§',      '÷',      '¸',      '°',      '¨',      '·',      '¹',      '³',      '²',      '■'];
  $replace = [chr(32),  chr(33),  chr(34),  chr(35),  chr(36),  chr(37),  chr(38),  chr(39),  chr(40),  chr(41),  chr(42),  chr(43),  chr(44),  chr(45),  chr(46),  chr(47),  chr(48),  chr(49),  chr(50),  chr(51),  chr(52),  chr(53),  chr(54),  chr(55),  chr(56),  chr(57),  chr(58),  chr(59),  chr(60),  chr(61),  chr(62),  chr(63),  chr(64),  chr(65),  chr(66),  chr(67),  chr(68),  chr(69),  chr(70),  chr(71),  chr(72),  chr(73),  chr(74),  chr(75),  chr(76),  chr(77),  chr(78),  chr(79),  chr(80),  chr(81),  chr(82),  chr(83),  chr(84),  chr(85),  chr(86),  chr(87),  chr(88),  chr(89),  chr(90),  chr(91),  chr(92),  chr(93),  chr(94),  chr(95),  chr(96),  chr(97),  chr(98),  chr(99),  chr(100), chr(101), chr(102), chr(103), chr(104), chr(105), chr(106), chr(107), chr(108), chr(109), chr(110), chr(111), chr(112), chr(113), chr(114), chr(115), chr(116), chr(117), chr(118), chr(119), chr(120), chr(121), chr(122), chr(123), chr(124), chr(125), chr(126), chr(128), chr(129), chr(130), chr(131), chr(132), chr(133), chr(134), chr(135), chr(136), chr(137), chr(138), chr(139), chr(140), chr(141), chr(142), chr(143), chr(144), chr(145), chr(146), chr(147), chr(148), chr(149), chr(150), chr(151), chr(152), chr(153), chr(154), chr(155), chr(156), chr(157), chr(158), chr(159), chr(160), chr(161), chr(162), chr(163), chr(164), chr(165), chr(166), chr(167), chr(168), chr(169), chr(170), chr(171), chr(172), chr(173), chr(174), chr(175), chr(176), chr(177), chr(178), chr(179), chr(180), chr(181), chr(182), chr(183), chr(184), chr(185), chr(186), chr(187), chr(188), chr(189), chr(190), chr(191), chr(192), chr(193), chr(194), chr(195), chr(196), chr(197), chr(198), chr(199), chr(200), chr(201), chr(202), chr(203), chr(204), chr(205), chr(206), chr(207), chr(208), chr(209), chr(210), chr(211), chr(212), chr(213), chr(214), chr(215), chr(216), chr(217), chr(218), chr(219), chr(220), chr(221), chr(222), chr(223), chr(224), chr(225), chr(226), chr(227), chr(228), chr(229), chr(230), chr(231), chr(232), chr(233), chr(234), chr(235), chr(236), chr(237), chr(238), chr(239), chr(240), chr(241), chr(242), chr(243), chr(244), chr(245), chr(246), chr(247), chr(248), chr(249), chr(250), chr(251), chr(252), chr(253), chr(254)];
	$text = str_replace($search, $replace, $text);
	$text = substr($text, 0, 48);
	return $text;
}

/**
 * Begin of own output.
 * 
 * Example 1:
 * Print centered caption and left justified text. Feed two lines at the end and cut the paper.
 * 
 * printer_reset();
 * printer_center();
 * echo printer_text("HAIR SALON");
 * printer_left();
 * echo printer_text("Thank you for your visit.");
 * ...
 * printer_feed(2);
 * printer_cut();
 * 
 */
printer_reset();
/**
 * Place your code here.
 * Don't forget to feed and cut at the end.
 */
?>