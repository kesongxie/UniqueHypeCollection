<?php
	//core.inc.php includes global functions 
	session_start();
	include_once 'global_constant.php'; 
	
	function deleteCookie($cookie_name){
		unset($_COOKIE[$cookie_name]);
		setcookie ($cookie_name, null, -1, '/');
	}	
	
	
	
	
	function clearLoginCredential(){
		unset($_SESSION['id']);
		deleteCookie("identifier");
		deleteCookie("token");
	}
	
	function getRandomString(){
		return bin2hex(openssl_random_pseudo_bytes(12));
	}				

	function getMediaFileExtension($file){
		return strtolower(pathinfo(basename($file["name"]),PATHINFO_EXTENSION));
	}
	
	
	
	function validateDate($date, $format = 'Y-m-d H:i:s')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
		
	function validateTime($time){
		//02:00 AM
		$segments = explode(' ',$time);
		$valid_time_arary = array('00:30','01:00','01:30','02:00','02:30', '03:00','03:30','04:00','04:30','05:00','05:30', '06:00'
								,'06:30','07:00','07:30','08:00','08:30', '09:00','09:30', '10:00','10:30','11:00','11:30', '12:00'
		);
		if(in_array($segments[0],$valid_time_arary) && (strcasecmp($segments[1],'AM')|| strcasecmp($segments[1],'PM')) ){
			
			if($segments[1]=='PM'){
				if($segments[0] != '12:00'){
					$segments[0]=(substr($segments[0],0,2)+12).substr($segments[0],2);
				}
			}else{
				if($segments[0] == '12:00'){
					$segments[0]='0'.(substr($segments[0],0,2)-12).substr($segments[0],2);
				}
			}
			return $segments[0].':00';
		}
		return false;
	}	
		
	function convertDateToNewFormat($date, $format){
		$d = DateTime::createFromFormat($format, $date);
		return $d;
	}	
	
	function isMediaDisplayable($media_url){
		if($media_url !== false){
			$file_headers = @get_headers($media_url);
			return $file_headers[0] == 'HTTP/1.1 200 OK';
		}
		return false;
	}
	
	function displayMedia($media_url){
		if(isMediaDisplayable($media_url) !== false){
			return $media_url;
		}
		return DEFAULT_IMAGE;
	}
	
	

	/*
		from format Y-m-d H:i:s to ago
	*/
	function convertDateTimeToAgo($str, $withAgo, $shorter = false, $ignore_plural = false, $shorter_now = false){
		list($date, $time) = explode(' ', $str);
    	list($year, $month, $day) = explode('-', $date);
    	list($hour, $minute, $second) = explode(':', $time);
    	$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
    	
    	$difference = time() - $timestamp;
    	if($shorter){
   		   $periods = array("s", "m", "h", "d", "week", "mon", "year", "decade");
   		}else{
   		   $periods = array("sec", "min", "hr", "day", "week", "mon", "year", "decade");
   		}
   		$lengths = array("60","60","24","7","4.35","12","10");
   		for($j = 0; $difference >= $lengths[$j]; $j++)
   			$difference /= $lengths[$j];
   		$difference = round($difference);
		if($difference != 0){	
			if(!$ignore_plural){
				if($difference != 1) $periods[$j].= "s";
			}
			$text = "$difference $periods[$j]";
			if($withAgo){
				$text.=" ago";
			}
   		}else{
   			if($shorter_now){
   				$text = 'now';
   			}else{
   				$text = 'Just now';
   			}
   		}
   		return $text;
    }
		
		
	function getWeekDayFromDate($date){
		$timestamp=strtotime($date);
		$day_numeric= date('w',$timestamp);//0-6,Sun-Sat
		switch ($day_numeric)
		{
		case 0:return 'Sun';break;
		case 1:return 'Mon';break;
		case 2:return 'Tue';break;
		case 3:return 'Wed';break;
		case 4:return'Thur';break;
		case 5:return'Fri';break;
		case 6:return'Sat';break;
	
		}
	}	
		
	function convertTimeToAmPm($time){
		$time=trim($time);
		$h=(int)substr($time,0,2);
		$m=substr($time,3,2);
		if($h<12){
			return $h.':'.$m.' AM';
		}
		else{
			if($h==12){
				return $h.':'.$m.' PM';
			}
			else if ($h>12){
				$h-=12;
				return $h.':'.$m.' PM';
			}
		}
	}
		
		
		
	/*
		for example, 2015 03 13 -> March 13 if the current year is the same, otherwise March 13 - 2013 
	*/
	function returnShortDate($date, $delimeter = '-'){
		$ts = strtotime($date);
		if(isToday($date)){
			return 'Today';	
		}
		if(date('Y',$ts) ==  date('Y'))
			return date('M d',$ts);
		else{
			return date('M d '.$delimeter.' Y',$ts);
		}
	}	
	
	
	function isToday($date){
		return date('Ymd') == date('Ymd', strtotime($date));
	}
	
	
	
	function convertThumbPathToOriginPath($src){
		return str_replace('thumb_','',$src);
	}	
	
	
	function checkPasswordValid($p){
		return preg_match('/(?=.*\d)(?=.*[a-zA-Z]).{6,}/', $p ) == 1;
	}
	
	function multiexplode ($delimiters,$string) {
   	 	$ready = str_replace($delimiters, $delimiters[0], $string);
   	 	$launch = explode($delimiters[0], $ready);
    	return  $launch;
	}
	
	
	function enRichText($text){
		$result = "";
		$text_array = multiexplode(array(',',' '), $text);
		foreach($text_array as $word){
			if(stripos($word, '#') !== false){
				$word = '<span class="red-act plain-lk pointer">'.$word.'</span>';
 			}
 			$result.=$word.' ';
		}
		return $result;
	}
	
	function redirectLocation($location){
		return 'http://maps.google.com/?q='.$location;
	}
		
	spl_autoload_register( 'autoload' );	
	  /**
	   * autoload
	   *
	   * @author Joe Sexton <joe.sexton@bigideas.com>
	   * @param  string $class
	   * @param  string $dir
	   * @return bool
	   */
	  function autoload( $class, $dir = null ) {
		if ( is_null( $dir ) )
		  $dir = DOCUMENT_ROOT.'php_inc/';
		foreach ( scandir( $dir ) as $file ) {
		  // directory?
		  if ( is_dir( $dir.$file ) && substr( $file, 0, 1 ) !== '.' )
			autoload( $class, $dir.$file.'/' );
		  // php file?
		  if ( substr( $file, 0, 2 ) !== '._' && preg_match( "/.php$/i" , $file ) ) {
			// filename matches class?
			if ( str_replace( '.php', '', $file ) == $class || str_replace( '.class.php', '', $file ) == $class ) {
				include $dir . $file;
			}
		  }
		}
	  }
  
  	function getCurrencyFormat($num){
  		setlocale(LC_MONETARY, 'en_US.UTF-8');
		return money_format('%.2n', $num);
  	}
	
	function validatesAsInt($number)
	{
		$number = filter_var($number, FILTER_VALIDATE_INT);
		return ($number !== FALSE);
	}
	
	function trimExplode($string, $delimiter){
		return preg_split('/'.$delimiter.'/', $string, -1, PREG_SPLIT_NO_EMPTY);
	}
	
	function var_dump_pre($mixed = null) {
	  echo '<pre>';
	  var_dump($mixed);
	  echo '</pre>';
	  return null;
	}
	
	function outPutOrderStatus($status){
		switch($status){
			case 'not ship yet':
			case 'waiting for refund':
				return '<span class="need-action">'.$status.'</span>';break;
			case 'shipped':
			case 'refunded';	
				return '<span class="no-need-action">'.$status.'</span>';break;
			default:
				return '<span>unknown status</span>';break;
			
		}
	}

		
	
?>