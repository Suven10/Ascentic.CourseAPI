<?php
class Logger
{
	function LogAppender($data,$msgType) {
		$txt;
		switch ($msgType)
		{
			case "Error":
				$txt="red";
				break;
			case "Info":
				$txt="blue";
				break;
			default:
				break;
		}
		if(is_array($data))
		{
			$output = '<span style="color:'.$txt.';text-align:center;">'.$data.'</span>'.'</br>';
// 			$output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
			$outLog=implode(',', $data);
			if(LogAppender)
				$this->logToFile($outLog,$msgType);
		}
		else
		{
			$output = '<span style="color:'.$txt.';text-align:center;">'.$data.'</span>'.'</br>';
// 			$output = '<span style="color:blue;text-align:center;">'.$data.'</span>'.'</br>';
// 			$output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";
			if(LogAppender)
				$this->logToFile($data,$msgType);
		}
		echo $output;
	}
	
	function logToFile($msg,$msgType)
	{		
		$ourFileName = "../../../logs/uom.txt";
		date_default_timezone_set('Asia/Colombo');
		$logMsg=$msgType." [" . date("Y/m/d h:i:s", time()) . "] " .$msg;
		$myfile = file_put_contents($ourFileName, $logMsg.PHP_EOL , FILE_APPEND);
	}
}
?>