<?php


namespace App\Services\Number;

class NumberExtensive {

	const CURRENCY = 1;
	const NUMBER = 2;

	private $number;

	private $mil_sing = ['mil','milhão','bilhão','trilhão','quadrilhão','quintilhão'];
	private $mil_plur = ['mil','milhões','bilhões','trilhões','quadrilhões','quintilhões'];

	private $hundreds = [
		1 => 'cento',
        2 => 'duzentos',
        3 => 'trezentos',
        4 => 'quatrocentos',
		5 => 'quinhentos',
        6 => 'seicentos',
        7 => 'setecentos',
		8 => 'oitocentos',
        9 => 'novecentos'
	];

	private $tens = [
		1 => 'dez',
        2 => 'vinte',
        3 => 'trinta',
        4 => 'quarenta',
		5 => 'cinquenta',
        6 => 'sessenta',
        7 => 'setenta',
		8 => 'oitenta',
        9 => 'noventa'
	];

	private $ateVinte = [
		11 => 'onze',
        12 => 'doze',
        13 => 'treze',
        14 => 'quatorze',
		15 => 'quinze',
        16 => 'dezesseis',
        17 => 'dezessete',
		18 => 'dezoito',
        19 => 'dezenove'
	];

	private $ateDez = [
		1 => 'um',
        2 => 'dois',
        3 => 'três',
        4 => 'quatro',
        5 => 'cinco',
		6 => 'seis',
        7 => 'sete',
        8 => 'oito',
        9 => 'nove'
	];

    public function __construct($value)
    {
        $this->value($value);
    }

	private function value($value) {
		$this->number = number_format($value,2,',','.');

        return $this;
	}

	private function break() {
		return preg_split("/[,\.]+/", (string)$this->number);
	}

    public function toCurrency()
    {
        return $this->number(self::CURRENCY);
    }

    public function toNumber()
    {
        return $this->number(self::NUMBER);
    }

	public function number($tipo = null) {
		$valueBreak = $this->break();

		$cent = (string)$valueBreak[ count($valueBreak)-1 ];
		unset( $valueBreak[ count($valueBreak)-1 ] );

		$valueBreak = array_reverse($valueBreak);

		foreach ($valueBreak as $key => $value) {
			$valueBreak[ $key ] = $this->convert( $value );

			if ( $key > 0 ) {
				$valueBreak[ $key ] .= ( (int)$value == 1 )
					? " " . $this->mil_sing[ $key-1 ]
					: " " . $this->mil_plur[ $key-1 ];
			}
		}

		$valueBreak = array_filter($valueBreak, function($value) {
			return ( !empty( trim($value) ) );
		});

		$valueBreak = array_reverse($valueBreak);
		$cent = $this->convert($cent);

		$dec = ( $cent !== 'um' )
			? 's'
			: null;

		$str = implode(" e ", $valueBreak );

		switch ($tipo) {
			case self::CURRENCY:
				return ( strlen($cent) > 0 )
					? "{$str} reais e {$cent} centavo{$dec}"
					: "{$str} reais";
				break;

			default:
				return ( strlen($cent) > 0 )
					? "{$str} e {$cent} centésimo{$dec}"
					: $str;
				break;
		}
	}

	private function convert($v) {
		$v = str_pad($v, 3, '0', STR_PAD_LEFT);

		$c = $this->hundred($v);

		$d = $this->ten($v);

		$u = $this->unity($v);

		$p  = ( strlen($c) > 0 ) ? '1' : '0';
		$p .= ( strlen($d) > 0 ) ? '1' : '0';
		$p .= ( strlen($u) > 0 ) ? '1' : '0';

		switch ($p) {
			case '100':
				return $c;
				break;

			case '110':
				return "{$c} e {$d}";
				break;

			case '111':
				return "{$c} e {$d} e {$u}";
				break;

			case '011':
				return "{$d} e {$u}";
				break;

			case '001':
				return "{$u}";
				break;

			case '010':
				return "{$d}";
				break;

			case '101':
				return "{$c} e {$u}";
				break;
		}
	}

	private function hundred($v) {
		$s  = substr($v, 0, 1);
		$s1 = substr($v, 1, 2);

		if ( $s == '0' ) {
			return null;
		}

		if ( $s1 == '00' && $s == '1') {
			//if ( $s == '1' ) {
				return 'cem';
			//}
		} else {
			return $this->hundreds[ (int)$s ];
		}
	}

	private function ten(&$v) {
		$d = '';

		$s = substr( $v, 1, 2 );

		if ( isset( $this->tens[ $s[0] ] ) ) {
			if ( (int)$s % 10 == 0 ) {
				$v = '000';
			}
			$d = $this->tens[ $s[0] ];
		}

		if ( $s > 10 && $s < 20 ) {
			$v = '000';
			$d = $this->ateVinte[ $s ];
		}

		return $d;
	}

	private function unity($v) {
		$unity = '';

		$s = substr( $v, 2, 1 );

		if ( $s !== '0' ) {
			$unity = $this->ateDez[ (int)$s ];
		}

		return $unity;
	}
}
