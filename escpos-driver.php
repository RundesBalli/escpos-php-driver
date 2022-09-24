<?php
/**
 * PHP-Driver class for ESC/POS Thermalprinters.
 * 
 * @author    RundesBalli <webspam@rundesballi.com>
 * @copyright 2022 RundesBalli
 * @version   2.0
 * @see       https://files.support.epson.com/pdf/general/escp2ref.pdf
 * @see       https://aures-support.com/DATA/drivers/Imprimantes/Commande%20ESCPOS.pdf
 * @see       http://www.asciitable.com/
 * @license   MIT-License
 */

class printer {
  /**
   * ASCII-constants for initiating the commands.
   * ESC = ASCII-Escape
   * GS = ASCII-Group-Separator
   * NUL = ASCII-NULL
   */
  private const ESC = "\x1b";
  private const GS = "\x1d";
  private const NUL = "\x00";

  /**
   * Format array
   */
  private static $format = [
    'small' => 0,
    'bold' => 0,
    'doubleHeight' => 0,
    'doubleWidth' => 0,
    'underline' => 0
  ];

  /**
   * Initialization of the class will reset the printer
   */
  function __construct() {
    echo self::ESC.'@';
  }

  /**
   * Sets the text alignment for the following text.
   * 
   * @param string $align The desired alignment. Possible values are: left, l, center, c, right, r.
   */
  public static function setAlignment(string $align = 'left') {
    $possibleAlignments = [
      'left' => '0',
      'l' => '0',
      'center' => '1',
      'c' => '1',
      'right' => '2',
      'r' => '2'
    ];

    /**
     * Check if a valid alignment is specified, if not, it will be set to left.
     */
    if(!isset($possibleAlignments[$align])) {
      $align = 'left';
    }

    /**
     * Set the desired alignment.
     */
    echo self::ESC.'a'.chr($possibleAlignments[$align]);
  }

  /**
   * Feeds n blank lines.
   * 
   * @param int $lines Number of lines to be feeded.
   */
  public static function feed(int $lines = 1) {
    if($lines === 0) {
      return;
    }
    echo self::ESC.'d'.chr(abs($lines));
  }

  /**
   * Cuts the paper.
   */
  public static function cut() {
    echo self::GS.'VA'.chr(3);
  }

  /**
   * Let the printer beep.
   * 
   * @param int $count Number of beeps. Possible values are from 1 to 9.
   * @param int $length Length of every beep. Possible values are from 1 to 9.
   */
  public static function beep($count = 1, $length = 1) {
    /**
     * Check if the provided parameters are in the correct range.
     */
    $count = abs($count);
    if(!in_array($count, range(1, 9))) {
      $count = 1;
    }

    $length = abs($length);
    if(!in_array($length, range(1, 9))) {
      $length = 1;
    }

    /**
     * Beep!
     */
    echo self::ESC."B".chr($count).chr($length);
  }

  /**
   * Reverse colors
   * 
   * @param bool $reverse If true, the following text will be printed with white font on black background.
   */
  public static function reverseColors(bool $reverse = FALSE) {
    echo self::GS.'B'.($reverse ? chr(1) : chr(0));
  }

  /**
   * Applies previously set text styles.
   */
  private static function applyStyle() {
    echo self::ESC.'!'.chr(array_sum(self::$format));
  }

  /**
   * Small font
   * 
   * @param bool $small If true, the cpi (characters per inch) increases to 12, if false it is set to 10 (default).
   */
  public static function small(bool $small = FALSE) {
    self::$format['small'] = ($small ? 1 : 0);
    self::applyStyle();
  }

  /**
   * Bold text
   * 
   * @param bool $bold If true, the font-weight is set to bold.
   */
  public static function bold(bool $bold = FALSE) {
    self::$format['bold'] = ($bold ? 8 : 0);
    self::applyStyle();
  }

  /**
   * Double height
   * 
   * @param bool $doubleHeight If true, the text will be presented in double height.
   */
  public static function doubleHeight(bool $doubleHeight = FALSE) {
    self::$format['doubleHeight'] = ($doubleHeight ? 16 : 0);
    self::applyStyle();
  }

  /**
   * Double width
   * 
   * @param bool $doubleWidth If true, the text will be presented in double width.
   */
  public static function doubleWidth(bool $doubleWidth = FALSE) {
    self::$format['doubleWidth'] = ($doubleWidth ? 32 : 0);
    self::applyStyle();
  }

  /**
   * Underline
   * 
   * @param bool $underline If true, the text will be presented underlined.
   */
  public static function underline(bool $underline = FALSE) {
    self::$format['underline'] = ($underline ? 128 : 0);
    self::applyStyle();
  }

  /**
   * Barcode
   * 
   * Available barcode types:
   * 0: UPC-A
   * 1: UPC-E
   * 2: EAN13
   * 3: EAN8
   * 4: CODE39
   * 5: ITF
   * 6: CODABAR
   * 
   * @param int $height Height of the barcode in points. Possible values are in the range 1-255, but anything less than 15 is nonsense, so the possible values are in the range 15-255.
   * @param int $type Barcode type, see list above.
   * @param string $content Content of the barcode.
   */
  public static function barcode(int $height = 120, int $type = 4, string $content = 'Test') {
    /**
     * Check the height.
     */
    if($height < 15 OR $height > 255) {
      $height = 120;
    }

    /**
     * Check the barcode type.
     */
    if(!in_array($type, range(0, 6), TRUE)) {
      $type = 4;
    }

    echo self::GS."h".chr($height).self::GS."k".chr($type).$content.self::NUL;
  }

  /**
   * Horizontal line
   * 
   * @param string $char Character which is repeated the whole line.
   */
  public static function horizontalLine(string $char = '-') {
    echo str_repeat(substr($char, 0, 1), 48);
  }

  /**
   * Print text
   * 
   * @param string $text The text to be printed.
   */
  public static function text(string $text) {
    /**
     * Replace all ASCII control chars with nothing.
     */
    $text = preg_replace('/[\x00-\x1F\x7F]/', '', $text);

    /**
     * Replace chars with the decoded ASCII character.
     */
    $search  = [' ',      '!',      '"',      '#',      '$',      '%',      '&',      "'",      '(',      ')',      '*',      '+',      ',',      '-',      '.',      '/',      '0',      '1',      '2',      '3',      '4',      '5',      '6',      '7',      '8',      '9',      ':',      ';',      '<',      '=',      '>',      '?',      '@',      'A',      'B',      'C',      'D',      'E',      'F',      'G',      'H',      'I',      'J',      'K',      'L',      'M',      'N',      'O',      'P',      'Q',      'R',      'S',      'T',      'U',      'V',      'W',      'X',      'Y',      'Z',      '[',      '\\',     ']',      '^',      '_',      '`',      'a',      'b',      'c',      'd',      'e',      'f',      'g',      'h',      'i',      'j',      'k',      'l',      'm',      'n',      'o',      'p',      'q',      'r',      's',      't',      'u',      'v',      'w',      'x',      'y',      'z',      '{',      '|',      '}',      '~',      'Ç',      'ü',      'é',      'â',      'ä',      'à',      'å',      'ç',      'ê',      'ë',      'è',      'ï',      'î',      'ì',      'Ä',      'Å',      'É',      'æ',      'Æ',      'ô',      'ö',      'ò',      'û',      'ù',      'ÿ',      'Ö',      'Ü',      'ø',      '£',      'Ø',      '×',      'ƒ',      'á',      'í',      'ó',      'ú',      'ñ',      'Ñ',      'ª',      'º',      '¿',      '®',      '¬',      '½',      '¼',      '­¡',      '«',      '»',      '░',      '▒',      '▓',      '│',      '┤',      'Á',      'Â',      'À',      '©',      '╣',      '║',      '╗',      '╝',      '¢',      '¥',      '┐',      '└',      '┴',      '┬',      '├',      '─',      '┼',      'ã',      'Ã',      '╚',      '╔',      '╩',      '╦',      '╠',      '═',      '╬',      '¤',      'ð',      'Ð',      'Ê',      'Ë',      'È',      'ı',      'Í',      'Î',      'Ï',      '┘',      '┌',      '█',      '▄',      '¦',      'Ì',      '▀',      'Ó',      'ß',      'Ô',      'Ò',      'õ',      'Õ',      'µ',      'þ',      'Þ',      'Ú',      'Û',      'Ù',      'ý',      'Ý',      '¯',      '´',      '≡',      '±',      '‗',      '¾',      '¶',      '§',      '÷',      '¸',      '°',      '¨',      '·',      '¹',      '³',      '²',      '■'];
    $replace = [chr(32),  chr(33),  chr(34),  chr(35),  chr(36),  chr(37),  chr(38),  chr(39),  chr(40),  chr(41),  chr(42),  chr(43),  chr(44),  chr(45),  chr(46),  chr(47),  chr(48),  chr(49),  chr(50),  chr(51),  chr(52),  chr(53),  chr(54),  chr(55),  chr(56),  chr(57),  chr(58),  chr(59),  chr(60),  chr(61),  chr(62),  chr(63),  chr(64),  chr(65),  chr(66),  chr(67),  chr(68),  chr(69),  chr(70),  chr(71),  chr(72),  chr(73),  chr(74),  chr(75),  chr(76),  chr(77),  chr(78),  chr(79),  chr(80),  chr(81),  chr(82),  chr(83),  chr(84),  chr(85),  chr(86),  chr(87),  chr(88),  chr(89),  chr(90),  chr(91),  chr(92),  chr(93),  chr(94),  chr(95),  chr(96),  chr(97),  chr(98),  chr(99),  chr(100), chr(101), chr(102), chr(103), chr(104), chr(105), chr(106), chr(107), chr(108), chr(109), chr(110), chr(111), chr(112), chr(113), chr(114), chr(115), chr(116), chr(117), chr(118), chr(119), chr(120), chr(121), chr(122), chr(123), chr(124), chr(125), chr(126), chr(128), chr(129), chr(130), chr(131), chr(132), chr(133), chr(134), chr(135), chr(136), chr(137), chr(138), chr(139), chr(140), chr(141), chr(142), chr(143), chr(144), chr(145), chr(146), chr(147), chr(148), chr(149), chr(150), chr(151), chr(152), chr(153), chr(154), chr(155), chr(156), chr(157), chr(158), chr(159), chr(160), chr(161), chr(162), chr(163), chr(164), chr(165), chr(166), chr(167), chr(168), chr(169), chr(170), chr(171), chr(172), chr(173), chr(174), chr(175), chr(176), chr(177), chr(178), chr(179), chr(180), chr(181), chr(182), chr(183), chr(184), chr(185), chr(186), chr(187), chr(188), chr(189), chr(190), chr(191), chr(192), chr(193), chr(194), chr(195), chr(196), chr(197), chr(198), chr(199), chr(200), chr(201), chr(202), chr(203), chr(204), chr(205), chr(206), chr(207), chr(208), chr(209), chr(210), chr(211), chr(212), chr(213), chr(214), chr(215), chr(216), chr(217), chr(218), chr(219), chr(220), chr(221), chr(222), chr(223), chr(224), chr(225), chr(226), chr(227), chr(228), chr(229), chr(230), chr(231), chr(232), chr(233), chr(234), chr(235), chr(236), chr(237), chr(238), chr(239), chr(240), chr(241), chr(242), chr(243), chr(244), chr(245), chr(246), chr(247), chr(248), chr(249), chr(250), chr(251), chr(252), chr(253), chr(254)];
    $text = str_replace($search, $replace, $text);

    echo $text."\n";
  }

  /**
   * Feed two lines and cut paper at the end of every printing.
   */
  function __destruct() {
    self::feed(2);
    self::cut();
  }
}

/**
 * Initialize the printer.
 */
$printer = new printer();
?>
