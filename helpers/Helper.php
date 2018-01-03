<?php
class GUID {
	public static function getGUID() {
		mt_srand ( ( double ) microtime () * 10000 ); // optional for php 4.2.0 and up.
		$charid = strtoupper ( md5 ( uniqid ( rand (), true ) ) );
		$hyphen = chr ( 45 ); // "-"
		                   // $uuid = chr(123)// "{"
		$uuid = substr ( $charid, 0, 8 ) . $hyphen . substr ( $charid, 8, 4 ) . $hyphen . substr ( $charid, 12, 4 ) . $hyphen . substr ( $charid, 16, 4 ) . $hyphen . substr ( $charid, 20, 12 );
		// .chr(125);// "}"
		return $uuid;
	}
}
class Validate {
	public static function validateMandatory($input, $mandatory) {
		$noOfFields=count($mandatory);
		$nullFields='';
		for($x = 0; $x <= $noOfFields-1; $x ++) {
			if (!array_key_exists($mandatory[$x],$input)){
				if (!empty($nullFields))
					$nullFields=$nullFields.', '.$mandatory[$x];
					else 
					$nullFields=$mandatory[$x];
			}
		}
		if (!empty($nullFields))
		{
			header ( "HTTP/1.1" . " 406 " . " Not Acceptable " );
			echo "Mandatory fields missing: (" . $nullFields . ") ";
			exit ();
		}
		else 
			return true;
	}
}

?>