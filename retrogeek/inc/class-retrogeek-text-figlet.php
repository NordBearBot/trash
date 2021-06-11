<?php
/**
 * Class to read figlet fonts and convert text ot ASCII-Art.
 * Based on the classes from evgeny Stepanischev.
 * Project home page (Russian): http://bolk.exler.ru/files/figlet/
 *
 * @package retrogeek
 * @since 22.02.2021
 */

/**
 * Class for Figlet.
 */
class Retrogeek_Text_Figlet {
	/**
	 * Height of a letter
	 *
	 * @var integer
	 *
	 * @access private
	 */

	private $height = 0;

	/**
	 * Letter baseline
	 *
	 * @var integer
	 *
	 * @access private
	 */

	private $oldlayout = 0;

	/**
	 * Flag - RTL (right to left) or LTR (left to right) text direction
	 *
	 * @var integer
	 *
	 * @access private
	 */

	private $rtol = 0;

	/**
	 * Information about special 'hardblank' character
	 *
	 * @var integer
	 *
	 * @access private
	 */

	private $hardblank = 0;

	/**
	 * Is used for keeping font
	 *
	 * @var array
	 *
	 * @access private
	 */

	private $font = array();

	/**
	 * Flag is true if smushing occured in letters printing cycle
	 *
	 * @var integer
	 *
	 * @access private
	 */

	private $smush_flag = 0;

	/**
	 * Load user font. Must be invoked first.
	 *
	 * @param string $filename font file name.
	 * @param bool   $loadgerman (optional) load German character set or not.
	 * @access public
	 * @throws \Exception File not found and file format exception.
	 * @return error or true for success.
	 */
	public function load_font( $filename, $loadgerman = true ) {
		$this->font = array();
		if ( ! file_exists( $filename ) ) {
			throw new Exception( 'File is not found', 1 );
		}

		if ( is_readable( $filename ) ) {
			$wpfs = new WP_Filesystem_Direct( array() );
			$fc   = explode( "\n", $wpfs->get_contents( $filename ) );

			/**
			 *  Schema:    flf2a$ 6 5 20 15 3 0 143 229
			 *              |  | | | |  |  | |  |   |
			 *             /  /  | | |  |  | |  |   \
			 *    Signature  /  /  | |  |  | |   \   Codetag_Count
			 *      Hardblank  /  /  |  |  |  \   Full_Layout
			 *           Height  /   |  |   \  Print_Direction
			 *           Baseline   /    \   Comment_Lines
			 *            Max_Length      Old_Layout
			 */
			$header = explode( ' ', $fc[0] );
			array_shift( $fc );

			if ( substr( $header[0], 0, 5 ) !== 'flf2a' ) {
				throw new Exception( 'Unknown FIGlet font format.', 3 );
			}

			list ( $this->hardblank, $this->height, , , $this->oldlayout, $cmt_count, $this->rtol ) = $header;
			$this->hardblank = substr( $this->hardblank, -1, 1 );

			for ( $i = 0; $i < $cmt_count; $i++ ) {
				array_shift( $fc );
			}

			// ASCII charcters.
			for ( $i = 32; $i < 127; $i++ ) {
				$this->font[ $i ] = $this->char( $fc );
			}

			foreach ( array( 91, 92, 93, 123, 124, 125, 126 ) as $i ) {
				if ( $loadgerman ) {
					$letter = $this->char( $fc );

					// Invalid character but main font is loaded and I can use it.
					if ( false === $letter ) {
						return true;
					}

					// Load if it is not blank only.
					if ( trim( implode( '', $letter ) ) !== '' ) {
						$this->font[ $i ] = $letter;
					}
				} else {
					$this->skip( $fc );
				}
			}

			// Extented characters.
			for ( $n = 0; ! empty( $fc ); $n++ ) {
				list ($i) = explode( ' ', rtrim( $fc[0] ), 2 );
				array_shift( $fc );
				if ( '' === $i ) {
					continue;
				}

				// If comment.
				if ( preg_match( '/^\-0x/i', $i ) ) {
					$this->skip( $fc );
				} else {
					// If Unicode.
					if ( preg_match( '/^0x/i', $i ) ) {
						$i = hexdec( substr( $i, 2 ) );
					} else {
						// If octal.
						if ( '0' === $i[0] && '0' !== $i || substr( $i, 0, 2 ) === '-0' ) {
							$i = octdec( $i );
						}
					}

					$letter = $this->char( $fc );

					// Invalid character but main font is loaded and I can use it.
					if ( false === $letter ) {
						return true;
					}

					$this->font[ $i ] = $letter;
				}
			}

			return true;
		} else {
			throw new Exception( 'Cannot open font file', 2 );
		}
	}

	/**
	 * Print string using font loaded by load_Font method
	 *
	 * @param string $str string for printing.
	 * @param bool   $inhtml (optional) output mode - HTML (true) or plain text (false).
	 * @access public
	 * @return string contains
	 */
	public function line_echo( $str, $inhtml = false ) {
		$out = array();

		$sl = strlen( $str );
		for ( $i = 0; $i < $sl; $i++ ) {
			// Pseudo Unicode support.
			if ( substr( $str, $i, 2 ) === '%u' ) {
				$ss = substr( $str, $i + 2, 4 );
				$lt = hexdec( $ss );
				$i += 6;
			} else {
				$lt = ord( $str[ $i ] );
			}

			$hb = preg_quote( $this->hardblank, '/' );
			$sp = "$hb\\x00\\s";

			// If chosen character not found try to use default.
			// If default character is not defined skip it..

			if ( ! isset( $this->font[ $lt ] ) ) {
				if ( isset( $this->font[0] ) ) {
					$lt = 0;
				} else {
					continue;
				}
			}

			for ( $j = 0; $j < $this->height; $j++ ) {
				$line = $this->font[ $lt ][ $j ];

				// Replace hardblanks.
				if ( isset( $out[ $j ] ) ) {
					if ( $this->rtol ) {
						$out[ $j ] = $line . $out[ $j ];
					} else {
						$out[ $j ] .= $line;
					}
				} else {
					$out[ $j ] = $line;
				}
			}

			if ( $this->oldlayout > -1 && $i ) {
				// Calculate minimal distance between two last letters.

				$mindiff = -1;

				for ( $j = 0; $j < $this->height; $j++ ) {
					if ( preg_match( "/\S(\s*\\x00\s*)\S/", $out[ $j ], $r ) ) {
						$mindiff = -1 === $mindiff ? strlen( $r[1] ) : min( $mindiff, strlen( $r[1] ) );
					}
				}

				// Remove spaces between two last letter.
				// dec mindiff for exclude \x00 symbol.

				if ( --$mindiff > 0 ) {
					for ( $j = 0; $j < $this->height; $j++ ) {
						if ( preg_match( "/\\x00(\s{0,{$mindiff}})/", $out[ $j ], $r ) ) {
							$l         = strlen( $r[1] );
							$b         = $mindiff - $l;
							$out[ $j ] = preg_replace( "/\s{0,$b}\\x00\s{{$l}}/", "\0", $out[ $j ], 1 );

						}
					}
				}
				// Smushing.
				$this->smush_flag = 0;

				for ( $j = 0; $j < $this->height; $j++ ) {
					$out[ $j ] = preg_replace_callback( "#([^$sp])\\x00([^$sp])#", array( &$this, 'rep' ), $out[ $j ] );
				}

				// Remove one space if smushing.
				// and remove all \x00 except tail whenever.

				if ( $this->smush_flag ) {
					$pat = array( "/\s\\x00(?!$)|\\x00\s/", "/\\x00(?!$)/" );
					$rep = array( '', '' );
				} else {
					$pat = "/\\x00(?!$)/";
					$rep = '';
				}

				for ( $j = 0; $j < $this->height; $j++ ) {
					$out[ $j ] = preg_replace( $pat, $rep, $out[ $j ] );
				}
			}
		}

		$trans = array(
			"\0"             => '',
			$this->hardblank => ' ',
		);

		$str = strtr( implode( "\n", $out ), $trans );

		if ( $inhtml ) {
			return '<span style="white-space: nowrap;">' . nl2br( str_replace( ' ', '&nbsp;', htmlspecialchars( $str, ENT_COMPAT, 'UTF-8' ) ) ) . '</span>';
		}

		return $str;
	}

	/**
	 * It is preg_replace callback function that makes horizontal letter smushing
	 *
	 * @param  array $r array for horizontal smushing.
	 * @return string
	 * @access private
	 */
	private function rep( $r ) {
		if ( $this->oldlayout & 1 && $r[1] === $r[2] ) {
			$this->smush_flag = 1;
			return $r[1];
		}

		if ( $this->oldlayout & 2 ) {
			$symb = '|/\\[]{}()<>';

			if ( '_' === $r[1] && strpos( $symb, $r[2] ) !== false ||
				'_' === $r[2] && strpos( $symb, $r[1] ) !== false ) {
				$this->smush_flag = 1;
				return $r[1];
			}
		}

		if ( $this->oldlayout & 4 ) {
			$classes = '|/\\[]{}()<>';

			$left = strpos( $classes, $r[1] );
			if ( false !== $left ) {
				$right = strpos( $classes, $r[2] );
				if ( false !== $right ) {
					$this->smush_flag = 1;
					return $right > $left ? $r[2] : $r[1];
				}
			}
		}

		if ( $this->oldlayout & 8 ) {
			$t = array(
				'[' => ']',
				']' => '[',
				'{' => '}',
				'}' => '{',
				'(' => ')',
				')' => '(',
			);

			if ( isset( $t[ $r[2] ] ) && $r[1] === $t[ $r[2] ] ) {
				$this->smush_flag = 1;
				return '|';
			}
		}

		if ( $this->oldlayout & 16 ) {
			$t = array(
				'/\\' => '|',
				'\\/' => 'Y',
				'><'  => 'X',
			);

			if ( isset( $t[ $r[1] . $r[2] ] ) ) {
				$this->smush_flag = 1;
				return $t[ $r[1] . $r[2] ];
			}
		}

		if ( $this->oldlayout & 32 ) {
			if ( $r[1] === $r[2] && $r[1] === $this->hardblank ) {
				$this->smush_flag = 1;
				return $this->hardblank;
			}
		}

		return $r[1] . "\00" . $r[2];
	}


	/**
	 * Function loads one character in the internal array from file
	 *
	 * @param  resource $fp handle of font file.
	 * @return mixed lines of the character or false if foef occured.
	 * @access private
	 */
	private function char( &$fp ) {
		$out = array();

		for ( $i = 0; $i < $this->height; $i++ ) {
			if ( empty( $fp ) ) {
				return false;
			}

			$line = rtrim( $fp[0], "\r\n" );
			array_shift( $fp );
			if ( preg_match( '/(.){1,2}$/', $line, $r ) ) {
				$line = str_replace( $r[1], '', $line );
			}

			$line .= "\x00";

			$out[] = $line;
		}

		return $out;
	}

	/**
	 * Function for skipping one character in a font file
	 *
	 * @param  resource $fp handle of font file.
	 * @return bool always return true
	 * @access private
	 */
	private function skip( &$fp ) {
		for ( $i = 0; $i < $this->height && empty( $fp ); $i++ ) {
			array_shift( $fp );
		}

		return true;
	}
}
