<?php
function string_to_array($string, $poczatek)
{
	$max = 10000;
	$wyjscie = false;
	$stan = false;
	
	while(strpos($string, '"') !== false) {
		/*TEST
		echo "<br>".$string."<br>";
		*/
		
		if(($poz1 = strpos($string, '"')) !== false) {} else {$poz1 = $max;}
		if(($poz2 = strpos($string, '{')) !== false) {} else {$poz2 = $max;}
		$pozMin = min($poz1, $poz2);
		if((($poz = strpos($string, '{')) !== false) && ($poz == $pozMin) && ($poczatek == false)) {
			$od = $poz + 1;
			
			$temp = substr($string, $od);
			//echo "++1 temp=".$temp."<br>===>>><br>";
			$result = string_to_array($temp, $poczatek);
			$wartosc = $result[0];
			//echo "<br><<<===<br>++1 wartosc=";
			//print_r(array_slice($wartosc, 0));
			//echo "<br>";
			$string = $result[1];
			//echo "++1 string=".$string."<br>";
			$stan = true;
		}
		elseif(($poz = strpos($string, '"')) !== false) {
			$od = $poz + 1;
			
			$temp = substr($string, $od);
			if(($do = strpos($temp, '"')) !== false) {}
			$klucz = substr($temp, 0, $do);
			$string = $temp;
			//echo "000 string=".$string."<br>";
		}
		
		if($poczatek == true) {
			$poczatek = false;
		}
		
		if($stan == false) {
			if(($poz1 = strpos($string, '":"')) !== false) {} else {$poz1 = $max;}
			if(($poz2 = strpos($string, '":')) !== false) {} else {$poz2 = $max;}
			if(($poz3 = strpos($string, '":{"')) !== false) {} else {$poz3 = $max;}
			if(($poz4 = strpos($string, '{"')) !== false) {} else {$poz4 = $max;}
			if(($poz5 = strpos($string, '":[')) !== false) {} else {$poz5 = $max;}
			$pozMin = min($poz1, $poz2, $poz3, $poz4, $poz5);
			if((($poz = strpos($string, '":[')) !== false) && ($poz == $pozMin)) {
				$od = $poz + 3;
				
				$temp = substr($string, $od);
				//echo "1 temp=".$temp."<br>===>>><br>";
				$result = string_to_array($temp, $poczatek);
				$wartosc = $result[0];
				//echo "<br><<<===<br>1 wartosc=";
				//print_r(array_slice($wartosc, 0));
				//echo "<br>";
				$string = $result[1];
				//echo "1 string=".$string."<br>";
			}
			elseif((($poz = strpos($string, '":{"')) !== false) && ($poz == $pozMin)) {
				$od = $poz + 3;
				
				$temp = substr($string, $od);
				//echo "2 temp=".$temp."<br>===>>><br>";
				$result = string_to_array($temp, $poczatek);
				$wartosc = $result[0];
				//echo "<br><<<===<br>2 wartosc=";
				//print_r(array_slice($wartosc, 0));
				//echo "<br>";
				$string = $result[1];
				//echo "2 string=".$string."<br>";
			}
			elseif((($poz = strpos($string, '{"')) !== false) && ($poz == $pozMin)) {
				$od = $poz + 1;
				
				$temp = substr($string, $od);
				$result = string_to_array($temp, $poczatek);
				$wartosc = $result[0];
				$string = $result[1];
				//echo "3 string=".$string."<br>";
			}
			elseif((($poz = strpos($string, '":"')) !== false) && ($poz == $pozMin)) {
				$od = $poz + 3;
				
				$temp = substr($string, $od);
				if(($do1 = strpos($temp, '"')) !== false) {} else {$do1 = $max;}
				if(($do2 = strpos($temp, '"}')) !== false) {} else {$do2 = $max;}
				if(($do3 = strpos($temp, '}')) !== false) {} else {$do3 = $max;}
				$doMin = min($do1, $do2, $do3);
				if((($do = strpos($temp, '"}')) !== false) && ($do == $doMin)) {
					$wartosc = substr($temp, 0, $do);
					$string = $temp;
					$wyjscie = true;
				}
				elseif((($do = strpos($temp, '}')) !== false) && ($do == $doMin)) {
					$wartosc = substr($temp, 0, $do);
					$string = $temp;
					$wyjscie = true;
				}
				elseif((($do = strpos($temp, '"')) !== false) && ($do == $doMin)) {
					$wartosc = substr($temp, 0, $do);
					$string = $temp;
				}
				//echo "4 string=".$string."<br>";
			}
			elseif((($poz = strpos($string, '":')) !== false) && ($poz == $pozMin)) {
				$od = $poz + 2;
				
				$temp = substr($string, $od);
				if(($do1 = strpos($temp, ',')) !== false) {} else {$do1 = $max;}
				if(($do2 = strpos($temp, '}')) !== false) {} else {$do2 = $max;}
				$doMin = min($do1, $do2);
				if((($do = strpos($temp, '}')) !== false) && ($do == $doMin)) {
					if((substr($temp, 0, $do) == "true") || (substr($temp, 0, $do) == "false")) {
						$text = substr($temp, 0, $do);
						if($text == "true") {
							$wartosc = true;
						}
						else {
							$wartosc = false;
						}
					}
					else if(substr($temp, 0, $do) == "null") {
						$wartosc = null;
					}
					else {
						$wartosc = (double)substr($temp, 0, $do);
					}
					$string = $temp;
					$wyjscie = true;
				}
				elseif((($do = strpos($temp, ',')) !== false) && ($do == $doMin)) {
					if((substr($temp, 0, $do) == "true") || (substr($temp, 0, $do) == "false")) {
						$text = substr($temp, 0, $do);
						if($text == "true") {
							$wartosc = true;
						}
						else {
							$wartosc = false;
						}
					}
					else if(substr($temp, 0, $do) == "null") {
						$wartosc = null;
					}
					else {
						$wartosc = (double)substr($temp, 0, $do);
					}
					$string = $temp;
				}
				//echo "5 string=".$string."<br>";
			}
			
			if(($poz1 = strpos($string, ',')) !== false) {} else {$poz1 = $max;}
			if(($poz2 = strpos($string, ':')) !== false) {} else {$poz2 = $max;}
			if(($poz3 = strpos($string, '}')) !== false) {} else {$poz3 = $max;}
			$pozMin = min($poz1, $poz2, $poz3);
			if((($poz = strpos($string, '}')) !== false) && ($poz == $pozMin)) {
				$od = $poz + 1;
				
				$temp = substr($string, $od);
				$string = $temp;
				$wyjscie = true;
				//echo "--1 string=".$string."<br>";
			}
			elseif((($poz = strpos($string, ':')) !== false) && ($poz == $pozMin)) {
				$od = $poz + 1;
				
				$temp = substr($string, $od);
				$string = $temp;
			}
			elseif((($poz = strpos($string, ',')) !== false) && ($poz == $pozMin)) {
				$od = $poz + 1;
				
				$temp = substr($string, $od);
				$string = $temp;
			}
		}
		else {
			$stan = false;
		}
		
		if(isset($klucz)) {
			$array[$klucz] = $wartosc;
		}
		else {
			$array[] = $wartosc;
		}
		
		/*TEST
		echo $klucz."=>".$wartosc."<br>";
		*/
		
		if($wyjscie == true) {
			return array($array, $string);
		}
	}
	
	if(!isset($array)) {
		$array[] = NULL;
	}
	
	/*TEST
	print_r(array_slice($array, 0));
	echo "<br><br>";
	*/
	
	return array($array, $string);
}

function array_to_string($array)
{
	$i=1;
	$ile = count($array);
	$string = '';
	reset($array);
	while(list($klucz, $wartosc) = each($array)) {
		$string = $string.$klucz.'=>'.$wartosc;
		if($i < $ile) {
			$string = $string.',';
		}
		$i++;
	}
	
	/*TEST
	echo $string."<br>";
	*/
	
	return $string;
}

function view_response($var)
{
	$typ = gettype($var);
	
	if($typ == "array") {
		while(list($klucz, $wartosc) = each($var)) {
			$typ = gettype($wartosc);
			
			switch ($typ) {
				case "string":
					echo $klucz.'=>'.$wartosc.'<br>';
					break;
				case "array":
					echo $klucz.'=>';
					view_response($wartosc);
					break;
				case "boolean":
					echo $klucz.'=>'.$wartosc.'<br>';
					break;
				case "integer":
				case "double":
					echo $klucz.'=>'.$wartosc.'<br>';
					break;
				case "NULL":
					echo $klucz.'=>'.'NULL'.'<br>';
					break;
			}
		}
	}
	else if($typ == "object") {
		var_dump($var);
	}
	else {
		echo $var.'<br />';
		//echo "typ=".$typ."<br />";
	}
}

function getdatafromarray($var, $data)
{
	$typ = gettype($var);
	
	if($typ == "array") {
		while(list($klucz, $wartosc) = each($var)) {
			if($klucz == $data) {
				return $wartosc;
			}
		}
	}
	else if($typ == "object") {
		return var_dump($var);
	}
	else {
		return $var;
	}
	
	return false;
}

function get_address($path)
{
	require $path."config.php";
	
	$address = $db_protocol.$db_server.':'.$db_potr.'/';
	
	return $address;
}

/*function mime_content_type($fileTarget, $fileName) {
	$mimeTypes = array(
		//popularne
		'txt' => 'text/plain',
		'htm' => 'text/html',
		'html' => 'text/html',
		'htmls' => 'text/html',
		'php' => 'text/html',
		'css' => 'application/x-pointplus',
		'css' => 'text/css',
		'js' => 'application/javascript',
		'json' => 'application/json',
		'xml' => 'application/xml',
		'xml' => 'text/xml',
		'swf' => 'application/x-shockwave-flash',
		'flv' => 'video/x-flv',
		
		//images
		'png' => 'image/png',
		'jpe' => 'image/pjpeg',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/pjpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/pjpeg',
		'jpg' => 'image/jpeg',
		'gif' => 'image/gif',
		'bmp' => 'image/x-windows-bmp',
		'bmp' => 'image/bmp',
		'ico' => 'image/vnd.microsoft.icon',
		"ico" => "image/x-icon",
		'tif' => 'image/x-tiff',
		'tif' => 'image/tiff',
		'tiff' => 'image/x-tiff',
		'tiff' => 'image/tiff',
		'svg' => 'image/svg+xml',
		'svgz' => 'image/svg+xml',
		"pbm" => "image/x-portable-bitmap",
		"pgm" => "image/x-portable-graymap",
		"pnm" => "image/x-portable-anymap",
		"ppm" => "image/x-portable-pixmap",
		"xbm" => "image/x-xbitmap",
		'xbm' => 'image/x-xbm',
		'xbm' => 'image/xbm',
		"xpm" => "image/x-xpixmap",
		'pct' => 'image/x-pict',
		'pcx' => 'image/x-pcx',
		'pic' => 'image/pict',
		'pict' => 'image/pict',
		'pm' => 'image/x-xpixmap',
		'qif' => 'image/x-quicktime',
		'qti' => 'image/x-quicktime',
		'qtif' => 'image/x-quicktime',
		'ras' => 'image/cmu-raster',
		'ras' => 'image/x-cmu-raster',
		'rast' => 'image/cmu-raster',
		
		//archives
		'zip' => 'application/zip',
		'rar' => 'application/x-rar-compressed',
		'exe' => 'application/x-msdownload',
		'msi' => 'application/x-msdownload',
		'cab' => 'application/vnd.ms-cab-compressed',
		
		//audio/video
		'mp3' => 'audio/mpeg',
		'qt' => 'video/quicktime',
		'mov' => 'video/quicktime',
		
		//adobe
		'pdf' => 'application/pdf',
		'psd' => 'image/vnd.adobe.photoshop',
		'ai' => 'application/postscript',
		'eps' => 'application/postscript',
		'ps' => 'application/postscript',
		
		//ms office
		'doc' => 'application/msword',
		'rtf' => 'application/rtf',
		'xls' => 'application/vnd.ms-excel',
		'ppt' => 'application/vnd.ms-powerpoint',
		
		//open office
		'odt' => 'application/vnd.oasis.opendocument.text',
		'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		
		//inne
		"323" => "text/h323",
		"3dml" => "text/vnd.in3d.3dml",
		"3g2" => "video/3gpp2",
		"3gp" => "video/3gpp",
		"7z" => "application/x-7z-compressed",
		"aab" => "application/x-authorware-bin",
		"aac" => "audio/x-aac",
		"aam" => "application/x-authorware-map",
		"aas" => "application/x-authorware-seg",
		"abw" => "application/x-abiword",
		"ac" => "application/pkix-attr-cert",
		"acc" => "application/vnd.americandynamics.acc",
		"ace" => "application/x-ace-compressed",
		"acu" => "application/vnd.acucobol",
		"adp" => "audio/adpcm",
		"aep" => "application/vnd.audiograph",
		"afp" => "application/vnd.ibm.modcap",
		"ahead" => "application/vnd.ahead.space",
		"ai" => "application/postscript",
		"aif" => "audio/x-aiff",
		"air" => "application/vnd.adobe.air-application-installer-package+zip",
		"ait" => "application/vnd.dvb.ait",
		"ami" => "application/vnd.amiga.ami",
		"apk" => "application/vnd.android.package-archive",
		"application" => "application/x-ms-application",
		
		"acx" => "application/internet-property-stream",
		"ai" => "application/postscript",
		"aif" => "audio/x-aiff",
		"aifc" => "audio/x-aiff",
		"aiff" => "audio/x-aiff",
		"asf" => "video/x-ms-asf",
		"asr" => "video/x-ms-asf",
		"asx" => "video/x-ms-asf",
		"au" => "audio/basic",
		"avi" => "video/x-msvideo",
		"axs" => "application/olescript",
		"bas" => "text/plain",
		"bcpio" => "application/x-bcpio",
		"bin" => "application/octet-stream",
		"c" => "text/plain",
		"cat" => "application/vnd.ms-pkiseccat",
		"cdf" => "application/x-cdf",
		"cer" => "application/x-x509-ca-cert",
		"class" => "application/octet-stream",
		"clp" => "application/x-msclip",
		"cmx" => "image/x-cmx",
		"cod" => "image/cis-cod",
		"cpio" => "application/x-cpio",
		"crd" => "application/x-mscardfile",
		"crl" => "application/pkix-crl",
		"crt" => "application/x-x509-ca-cert",
		"csh" => "application/x-csh",
		"dcr" => "application/x-director",
		"der" => "application/x-x509-ca-cert",
		"dir" => "application/x-director",
		"dll" => "application/x-msdownload",
		"dms" => "application/octet-stream",
		"doc" => "application/msword",
		"dot" => "application/msword",
		"dvi" => "application/x-dvi",
		"dxr" => "application/x-director",
		"eps" => "application/postscript",
		"etx" => "text/x-setext",
		"evy" => "application/envoy",
		"exe" => "application/octet-stream",
		"fif" => "application/fractals",
		"flr" => "x-world/x-vrml",
		"gtar" => "application/x-gtar",
		"gz" => "application/x-gzip",
		"h" => "text/plain",
		"hdf" => "application/x-hdf",
		"hlp" => "application/winhlp",
		"hqx" => "application/mac-binhex40",
		"hta" => "application/hta",
		"htc" => "text/x-component",
		"htt" => "text/webviewhtml",
		"ief" => "image/ief",
		"iii" => "application/x-iphone",
		"ins" => "application/x-internet-signup",
		"isp" => "application/x-internet-signup",
		"jfif" => "image/pipeg",
		"js" => "application/x-javascript",
		"latex" => "application/x-latex",
		"lha" => "application/octet-stream",
		"lsf" => "video/x-la-asf",
		"lsx" => "video/x-la-asf",
		"lzh" => "application/octet-stream",
		"m13" => "application/x-msmediaview",
		"m14" => "application/x-msmediaview",
		"m3u" => "audio/x-mpegurl",
		"man" => "application/x-troff-man",
		"mdb" => "application/x-msaccess",
		"me" => "application/x-troff-me",
		"mht" => "message/rfc822",
		"mhtml" => "message/rfc822",
		"mid" => "audio/mid",
		"mny" => "application/x-msmoney",
		"mov" => "video/quicktime",
		"movie" => "video/x-sgi-movie",
		"mp2" => "video/mpeg",
		"mp3" => "audio/mpeg",
		"mpa" => "video/mpeg",
		"mpe" => "video/mpeg",
		"mpeg" => "video/mpeg",
		"mpg" => "video/mpeg",
		"mpp" => "application/vnd.ms-project",
		"mpv2" => "video/mpeg",
		"ms" => "application/x-troff-ms",
		"mvb" => "application/x-msmediaview",
		"nws" => "message/rfc822",
		"oda" => "application/oda",
		"p10" => "application/pkcs10",
		"p12" => "application/x-pkcs12",
		"p7b" => "application/x-pkcs7-certificates",
		"p7c" => "application/x-pkcs7-mime",
		"p7m" => "application/x-pkcs7-mime",
		"p7r" => "application/x-pkcs7-certreqresp",
		"p7s" => "application/x-pkcs7-signature",
		"pdf" => "application/pdf",
		"pfx" => "application/x-pkcs12",
		"pko" => "application/ynd.ms-pkipko",
		"pma" => "application/x-perfmon",
		"pmc" => "application/x-perfmon",
		"pml" => "application/x-perfmon",
		"pmr" => "application/x-perfmon",
		"pmw" => "application/x-perfmon",
		"pot" => "application/vnd.ms-powerpoint",
		"pps" => "application/vnd.ms-powerpoint",
		"ppt" => "application/vnd.ms-powerpoint",
		"prf" => "application/pics-rules",
		"ps" => "application/postscript",
		"pub" => "application/x-mspublisher",
		"qt" => "video/quicktime",
		"ra" => "audio/x-pn-realaudio",
		"ram" => "audio/x-pn-realaudio",
		"ras" => "image/x-cmu-raster",
		"rgb" => "image/x-rgb",
		"rmi" => "audio/mid",
		"roff" => "application/x-troff",
		"rtf" => "application/rtf",
		"rtx" => "text/richtext",
		"scd" => "application/x-msschedule",
		"sct" => "text/scriptlet",
		"setpay" => "application/set-payment-initiation",
		"setreg" => "application/set-registration-initiation",
		"sh" => "application/x-sh",
		"shar" => "application/x-shar",
		"sit" => "application/x-stuffit",
		"snd" => "audio/basic",
		"spc" => "application/x-pkcs7-certificates",
		"spl" => "application/futuresplash",
		"src" => "application/x-wais-source",
		"sst" => "application/vnd.ms-pkicertstore",
		"stl" => "application/vnd.ms-pkistl",
		"stm" => "text/html",
		"sv4cpio" => "application/x-sv4cpio",
		"sv4crc" => "application/x-sv4crc",
		"t" => "application/x-troff",
		"tar" => "application/x-tar",
		"tcl" => "application/x-tcl",
		"tex" => "application/x-tex",
		"texi" => "application/x-texinfo",
		"texinfo" => "application/x-texinfo",
		"tgz" => "application/x-compressed",
		"tr" => "application/x-troff",
		"trm" => "application/x-msterminal",
		"tsv" => "text/tab-separated-values",
		"uls" => "text/iuls",
		"ustar" => "application/x-ustar",
		"vcf" => "text/x-vcard",
		"vrml" => "x-world/x-vrml",
		"wav" => "audio/x-wav",
		"wcm" => "application/vnd.ms-works",
		"wdb" => "application/vnd.ms-works",
		"wks" => "application/vnd.ms-works",
		"wmf" => "application/x-msmetafile",
		"wps" => "application/vnd.ms-works",
		"wri" => "application/x-mswrite",
		"wrl" => "x-world/x-vrml",
		"wrz" => "x-world/x-vrml",
		"xaf" => "x-world/x-vrml",
		"xla" => "application/vnd.ms-excel",
		"xlc" => "application/vnd.ms-excel",
		"xlm" => "application/vnd.ms-excel",
		"xls" => "application/vnd.ms-excel",
		"xlt" => "application/vnd.ms-excel",
		"xlw" => "application/vnd.ms-excel",
		"xof" => "x-world/x-vrml",
		"xwd" => "image/x-xwindowdump",
		"z" => "application/x-compress",
		"zip" => "application/zip",
		
		'3dm' => 'x-world/x-3dmf',
		'3dmf' => 'x-world/x-3dmf',
		'a' => 'application/octet-stream',
		'aab' => 'application/x-authorware-bin',
		'aam' => 'application/x-authorware-map',
		'aas' => 'application/x-authorware-seg',
		'abc' => 'text/vnd.abc',
		'acgi' => 'text/html',
		'afl' => 'video/animaflex',
		'ai' => 'application/postscript',
		'aif' => 'audio/aiff',
		'aif' => 'audio/x-aiff',
		'aifc' => 'audio/aiff',
		'aifc' => 'audio/x-aiff',
		'aiff' => 'audio/aiff',
		'aiff' => 'audio/x-aiff',
		'aim' => 'application/x-aim',
		'aip' => 'text/x-audiosoft-intra',
		'ani' => 'application/x-navi-animation',
		'aos' => 'application/x-nokia-9000-communicator-add-on-software',
		'aps' => 'application/mime',
		'arc' => 'application/octet-stream',
		'arj' => 'application/arj',
		'arj' => 'application/octet-stream',
		'art' => 'image/x-jg',
		'asf' => 'video/x-ms-asf',
		'asm' => 'text/x-asm',
		'asp' => 'text/asp',
		'asx' => 'application/x-mplayer2',
		'asx' => 'video/x-ms-asf',
		'asx' => 'video/x-ms-asf-plugin',
		'au' => 'audio/basic',
		'au' => 'audio/x-au',
		'avi' => 'application/x-troff-msvideo',
		'avi' => 'video/avi',
		'avi' => 'video/msvideo',
		'avi' => 'video/x-msvideo',
		'avs' => 'video/avs-video',
		'bcpio' => 'application/x-bcpio',
		'bin' => 'application/mac-binary',
		'bin' => 'application/macbinary',
		'bin' => 'application/octet-stream',
		'bin' => 'application/x-binary',
		'bin' => 'application/x-macbinary',
		'bm' => 'image/bmp',
		'boo' => 'application/book',
		'book' => 'application/book',
		'boz' => 'application/x-bzip2',
		'bsh' => 'application/x-bsh',
		'bz' => 'application/x-bzip',
		'bz2' => 'application/x-bzip2',
		'c' => 'text/plain',
		'c' => 'text/x-c',
		'c++' => 'text/plain',
		'cat' => 'application/vnd.ms-pki.seccat',
		'cc' => 'text/plain',
		'cc' => 'text/x-c',
		'ccad' => 'application/clariscad',
		'cco' => 'application/x-cocoa',
		'cdf' => 'application/cdf',
		'cdf' => 'application/x-cdf',
		'cdf' => 'application/x-netcdf',
		'cer' => 'application/pkix-cert',
		'cer' => 'application/x-x509-ca-cert',
		'cha' => 'application/x-chat',
		'chat' => 'application/x-chat',
		'class' => 'application/java',
		'class' => 'application/java-byte-code',
		'class' => 'application/x-java-class',
		'com' => 'application/octet-stream',
		'com' => 'text/plain',
		'conf' => 'text/plain',
		'cpio' => 'application/x-cpio',
		'cpp' => 'text/x-c',
		'cpt' => 'application/mac-compactpro',
		'cpt' => 'application/x-compactpro',
		'cpt' => 'application/x-cpt',
		'crl' => 'application/pkcs-crl',
		'crl' => 'application/pkix-crl',
		'crt' => 'application/pkix-cert',
		'crt' => 'application/x-x509-ca-cert',
		'crt' => 'application/x-x509-user-cert',
		'csh' => 'application/x-csh',
		'csh' => 'text/x-script.csh',
		'cxx' => 'text/plain',
		'dcr' => 'application/x-director',
		'deepv' => 'application/x-deepv',
		'def' => 'text/plain',
		'der' => 'application/x-x509-ca-cert',
		'dif' => 'video/x-dv',
		'dir' => 'application/x-director',
		'dl' => 'video/dl',
		'dl' => 'video/x-dl',
		'doc' => 'application/msword',
		'dot' => 'application/msword',
		'dp' => 'application/commonground',
		'drw' => 'application/drafting',
		'dump' => 'application/octet-stream',
		'dv' => 'video/x-dv',
		'dvi' => 'application/x-dvi',
		'dwf' => 'drawing/x-dwf',
		'dwf' => 'model/vnd.dwf',
		'dwg' => 'application/acad',
		'dwg' => 'image/vnd.dwg',
		'dwg' => 'image/x-dwg',
		'dxf' => 'application/dxf',
		'dxf' => 'image/vnd.dwg',
		'dxf' => 'image/x-dwg',
		'dxr' => 'application/x-director',
		'el' => 'text/x-script.elisp',
		'elc' => 'application/x-bytecode.elisp',
		'elc' => 'application/x-elc',
		'env' => 'application/x-envoy',
		'eps' => 'application/postscript',
		'es' => 'application/x-esrehber',
		'etx' => 'text/x-setext',
		'evy' => 'application/envoy',
		'evy' => 'application/x-envoy',
		'exe' => 'application/octet-stream',
		'f' => 'text/plain',
		'f' => 'text/x-fortran',
		'f77' => 'text/x-fortran',
		'f90' => 'text/plain',
		'f90' => 'text/x-fortran',
		'fdf' => 'application/vnd.fdf',
		'fif' => 'application/fractals',
		'fif' => 'image/fif',
		'fli' => 'video/fli',
		'fli' => 'video/x-fli',
		'flo' => 'image/florian',
		'flx' => 'text/vnd.fmi.flexstor',
		'fmf' => 'video/x-atomic3d-feature',
		'for' => 'text/plain',
		'for' => 'text/x-fortran',
		'fpx' => 'image/vnd.fpx',
		'fpx' => 'image/vnd.net-fpx',
		'frl' => 'application/freeloader',
		'funk' => 'audio/make',
		'g' => 'text/plain',
		'g3' => 'image/g3fax',
		'gl' => 'video/gl',
		'gl' => 'video/x-gl',
		'gsd' => 'audio/x-gsm',
		'gsm' => 'audio/x-gsm',
		'gsp' => 'application/x-gsp',
		'gss' => 'application/x-gss',
		'gtar' => 'application/x-gtar',
		'gz' => 'application/x-compressed',
		'gz' => 'application/x-gzip',
		'gzip' => 'application/x-gzip',
		'gzip' => 'multipart/x-gzip',
		'h' => 'text/plain',
		'h' => 'text/x-h',
		'hdf' => 'application/x-hdf',
		'help' => 'application/x-helpfile',
		'hgl' => 'application/vnd.hp-hpgl',
		'hh' => 'text/plain',
		'hh' => 'text/x-h',
		'hlb' => 'text/x-script',
		'hlp' => 'application/hlp',
		'hlp' => 'application/x-helpfile',
		'hlp' => 'application/x-winhelp',
		'hpg' => 'application/vnd.hp-hpgl',
		'hpgl' => 'application/vnd.hp-hpgl',
		'hqx' => 'application/binhex',
		'hqx' => 'application/binhex4',
		'hqx' => 'application/mac-binhex',
		'hqx' => 'application/mac-binhex40',
		'hqx' => 'application/x-binhex40',
		'hqx' => 'application/x-mac-binhex40',
		'hta' => 'application/hta',
		'htc' => 'text/x-component',
		'htt' => 'text/webviewhtml',
		'htx' => 'text/html',
		'ice' => 'x-conference/x-cooltalk',
		'idc' => 'text/plain',
		'ief' => 'image/ief',
		'iefs' => 'image/ief',
		'iges' => 'application/iges',
		'iges' => 'model/iges',
		'igs' => 'application/iges',
		'igs' => 'model/iges',
		'ima' => 'application/x-ima',
		'imap' => 'application/x-httpd-imap',
		'inf' => 'application/inf',
		'ins' => 'application/x-internett-signup',
		'ip' => 'application/x-ip2',
		'isu' => 'video/x-isvideo',
		'it' => 'audio/it',
		'iv' => 'application/x-inventor',
		'ivr' => 'i-world/i-vrml',
		'ivy' => 'application/x-livescreen',
		'jam' => 'audio/x-jam',
		'jav' => 'text/plain',
		'jav' => 'text/x-java-source',
		'java' => 'text/plain',
		'java' => 'text/x-java-source',
		'jcm' => 'application/x-java-commerce',
		'jfif' => 'image/jpeg',
		'jfif' => 'image/pjpeg',
		'jfif-tbnl' => 'image/jpeg',
		'jps' => 'image/x-jps',
		'js' => 'application/x-javascript',
		'jut' => 'image/jutvision',
		'kar' => 'audio/midi',
		'kar' => 'music/x-karaoke',
		'ksh' => 'application/x-ksh',
		'ksh' => 'text/x-script.ksh',
		'la' => 'audio/nspaudio',
		'la' => 'audio/x-nspaudio',
		'lam' => 'audio/x-liveaudio',
		'latex' => 'application/x-latex',
		'lha' => 'application/lha',
		'lha' => 'application/octet-stream',
		'lha' => 'application/x-lha',
		'lhx' => 'application/octet-stream',
		'list' => 'text/plain',
		'lma' => 'audio/nspaudio',
		'lma' => 'audio/x-nspaudio',
		'log' => 'text/plain',
		'lsp' => 'application/x-lisp',
		'lsp' => 'text/x-script.lisp',
		'lst' => 'text/plain',
		'lsx' => 'text/x-la-asf',
		'ltx' => 'application/x-latex',
		'lzh' => 'application/octet-stream',
		'lzh' => 'application/x-lzh',
		'lzx' => 'application/lzx',
		'lzx' => 'application/octet-stream',
		'lzx' => 'application/x-lzx',
		'm' => 'text/plain',
		'm' => 'text/x-m',
		'm1v' => 'video/mpeg',
		'm2a' => 'audio/mpeg',
		'm2v' => 'video/mpeg',
		'm3u' => 'audio/x-mpequrl',
		'man' => 'application/x-troff-man',
		'map' => 'application/x-navimap',
		'mar' => 'text/plain',
		'mbd' => 'application/mbedlet',
		'mc$' => 'application/x-magic-cap-package-1.0',
		'mcd' => 'application/mcad',
		'mcd' => 'application/x-mathcad',
		'mcf' => 'image/vasa',
		'mcf' => 'text/mcf',
		'mcp' => 'application/netmc',
		'me' => 'application/x-troff-me',
		'mht' => 'message/rfc822',
		'mhtml' => 'message/rfc822',
		'mid' => 'application/x-midi',
		'mid' => 'audio/midi',
		'mid' => 'audio/x-mid',
		'mid' => 'audio/x-midi',
		'mid' => 'music/crescendo',
		'mid' => 'x-music/x-midi',
		'midi' => 'application/x-midi',
		'midi' => 'audio/midi',
		'midi' => 'audio/x-mid',
		'midi' => 'audio/x-midi',
		'midi' => 'music/crescendo',
		'midi' => 'x-music/x-midi',
		'mif' => 'application/x-frame',
		'mif' => 'application/x-mif',
		'mime' => 'message/rfc822',
		'mime' => 'www/mime',
		'mjf' => 'audio/x-vnd.audioexplosion.mjuicemediafile',
		'mjpg' => 'video/x-motion-jpeg',
		'mm' => 'application/base64',
		'mm' => 'application/x-meme',
		'mme' => 'application/base64',
		'mod' => 'audio/mod',
		'mod' => 'audio/x-mod',
		'moov' => 'video/quicktime',
		'mov' => 'video/quicktime',
		'movie' => 'video/x-sgi-movie',
		'mp2' => 'audio/mpeg',
		'mp2' => 'audio/x-mpeg',
		'mp2' => 'video/mpeg',
		'mp2' => 'video/x-mpeg',
		'mp2' => 'video/x-mpeq2a',
		'mp3' => 'audio/mpeg3',
		'mp3' => 'audio/x-mpeg-3',
		'mp3' => 'video/mpeg',
		'mp3' => 'video/x-mpeg',
		'mpa' => 'audio/mpeg',
		'mpa' => 'video/mpeg',
		'mpc' => 'application/x-project',
		'mpe' => 'video/mpeg',
		'mpeg' => 'video/mpeg',
		'mpg' => 'audio/mpeg',
		'mpg' => 'video/mpeg',
		'mpga' => 'audio/mpeg',
		'mpp' => 'application/vnd.ms-project',
		'mpt' => 'application/x-project',
		'mpv' => 'application/x-project',
		'mpx' => 'application/x-project',
		'mrc' => 'application/marc',
		'ms' => 'application/x-troff-ms',
		'mv' => 'video/x-sgi-movie',
		'my' => 'audio/make',
		'mzz' => 'application/x-vnd.audioexplosion.mzz',
		'nap' => 'image/naplps',
		'naplps' => 'image/naplps',
		'nc' => 'application/x-netcdf',
		'ncm' => 'application/vnd.nokia.configuration-message',
		'nif' => 'image/x-niff',
		'niff' => 'image/x-niff',
		'nix' => 'application/x-mix-transfer',
		'nsc' => 'application/x-conference',
		'nvd' => 'application/x-navidoc',
		'o' => 'application/octet-stream',
		'oda' => 'application/oda',
		'omc' => 'application/x-omc',
		'omcd' => 'application/x-omcdatamaker',
		'omcr' => 'application/x-omcregerator',
		'p' => 'text/x-pascal',
		'p10' => 'application/pkcs10',
		'p10' => 'application/x-pkcs10',
		'p12' => 'application/pkcs-12',
		'p12' => 'application/x-pkcs12',
		'p7a' => 'application/x-pkcs7-signature',
		'p7c' => 'application/pkcs7-mime',
		'p7c' => 'application/x-pkcs7-mime',
		'p7m' => 'application/pkcs7-mime',
		'p7m' => 'application/x-pkcs7-mime',
		'p7r' => 'application/x-pkcs7-certreqresp',
		'p7s' => 'application/pkcs7-signature',
		'part' => 'application/pro_eng',
		'pas' => 'text/pascal',
		'pcl' => 'application/vnd.hp-pcl',
		'pcl' => 'application/x-pcl',
		'pdb' => 'chemical/x-pdb',
		'pdf' => 'application/pdf',
		'pfunk' => 'audio/make',
		'pfunk' => 'audio/make.my.funk',
		'pkg' => 'application/x-newton-compatible-pkg',
		'pko' => 'application/vnd.ms-pki.pko',
		'pl' => 'text/plain',
		'pl' => 'text/x-script.perl',
		'plx' => 'application/x-pixclscript',
		'pm' => 'text/x-script.perl-module',
		'pm4' => 'application/x-pagemaker',
		'pm5' => 'application/x-pagemaker',
		'pnm' => 'application/x-portable-anymap',
		'pot' => 'application/mspowerpoint',
		'pot' => 'application/vnd.ms-powerpoint',
		'pov' => 'model/x-pov',
		'ppa' => 'application/vnd.ms-powerpoint',
		'pps' => 'application/mspowerpoint',
		'pps' => 'application/vnd.ms-powerpoint',
		'ppt' => 'application/mspowerpoint',
		'ppt' => 'application/powerpoint',
		'ppt' => 'application/vnd.ms-powerpoint',
		'ppt' => 'application/x-mspowerpoint',
		'ppz' => 'application/mspowerpoint',
		'pre' => 'application/x-freelance',
		'prt' => 'application/pro_eng',
		'ps' => 'application/postscript',
		'psd' => 'application/octet-stream',
		'pvu' => 'paleovu/x-pv',
		'pwz' => 'application/vnd.ms-powerpoint',
		'py' => 'text/x-script.phyton',
		'pyc' => 'applicaiton/x-bytecode.python',
		'qcp' => 'audio/vnd.qcelp',
		'qd3' => 'x-world/x-3dmf',
		'qd3d' => 'x-world/x-3dmf',
		'qt' => 'video/quicktime',
		'qtc' => 'video/x-qtc',
		'ra' => 'audio/x-pn-realaudio',
		'ra' => 'audio/x-pn-realaudio-plugin',
		'ra' => 'audio/x-realaudio',
		'ram' => 'audio/x-pn-realaudio',
		'ras' => 'application/x-cmu-raster',
		'rexx' => 'text/x-script.rexx',
		'rf' => 'image/vnd.rn-realflash',
		'rgb' => 'image/x-rgb',
		'rm' => 'application/vnd.rn-realmedia',
		'rm' => 'audio/x-pn-realaudio',
		'rmi' => 'audio/mid',
		'rmm' => 'audio/x-pn-realaudio',
		'rmp' => 'audio/x-pn-realaudio',
		'rmp' => 'audio/x-pn-realaudio-plugin',
		'rng' => 'application/ringing-tones',
		'rng' => 'application/vnd.nokia.ringing-tone',
		'rnx' => 'application/vnd.rn-realplayer',
		'roff' => 'application/x-troff',
		'rp' => 'image/vnd.rn-realpix',
		'rpm' => 'audio/x-pn-realaudio-plugin',
		'rt' => 'text/richtext',
		'rt' => 'text/vnd.rn-realtext',
		'rtf' => 'application/rtf',
		'rtf' => 'application/x-rtf',
		'rtf' => 'text/richtext',
		'rtx' => 'application/rtf',
		'rtx' => 'text/richtext',
		'rv' => 'video/vnd.rn-realvideo',
		's' => 'text/x-asm',
		's3m' => 'audio/s3m',
		'saveme' => 'application/octet-stream',
		'sbk' => 'application/x-tbook',
		'scm' => 'application/x-lotusscreencam',
		'scm' => 'text/x-script.guile',
		'scm' => 'text/x-script.scheme',
		'scm' => 'video/x-scm',
		'sdml' => 'text/plain',
		'sdp' => 'application/sdp',
		'sdp' => 'application/x-sdp',
		'sdr' => 'application/sounder',
		'sea' => 'application/sea',
		'sea' => 'application/x-sea',
		'set' => 'application/set',
		'sgm' => 'text/sgml',
		'sgm' => 'text/x-sgml',
		'sgml' => 'text/sgml',
		'sgml' => 'text/x-sgml',
		'sh' => 'application/x-bsh',
		'sh' => 'application/x-sh',
		'sh' => 'application/x-shar',
		'sh' => 'text/x-script.sh',
		'shar' => 'application/x-bsh',
		'shar' => 'application/x-shar',
		'shtml' => 'text/html',
		'shtml' => 'text/x-server-parsed-html',
		'sid' => 'audio/x-psid',
		'sit' => 'application/x-sit',
		'sit' => 'application/x-stuffit',
		'skd' => 'application/x-koan',
		'skm' => 'application/x-koan',
		'skp' => 'application/x-koan',
		'skt' => 'application/x-koan',
		'sl' => 'application/x-seelogo',
		'smi' => 'application/smil',
		'smil' => 'application/smil',
		'snd' => 'audio/basic',
		'snd' => 'audio/x-adpcm',
		'sol' => 'application/solids',
		'spc' => 'application/x-pkcs7-certificates',
		'spc' => 'text/x-speech',
		'spl' => 'application/futuresplash',
		'spr' => 'application/x-sprite',
		'sprite' => 'application/x-sprite',
		'src' => 'application/x-wais-source',
		'ssi' => 'text/x-server-parsed-html',
		'ssm' => 'application/streamingmedia',
		'sst' => 'application/vnd.ms-pki.certstore',
		'step' => 'application/step',
		'stl' => 'application/sla',
		'stl' => 'application/vnd.ms-pki.stl',
		'stl' => 'application/x-navistyle',
		'stp' => 'application/step',
		'sv4cpio' => 'application/x-sv4cpio',
		'sv4crc' => 'application/x-sv4crc',
		'svf' => 'image/vnd.dwg',
		'svf' => 'image/x-dwg',
		'svr' => 'application/x-world',
		'svr' => 'x-world/x-svr',
		't' => 'application/x-troff',
		'talk' => 'text/x-speech',
		'tar' => 'application/x-tar',
		'tbk' => 'application/toolbook',
		'tbk' => 'application/x-tbook',
		'tcl' => 'application/x-tcl',
		'tcl' => 'text/x-script.tcl',
		'tcsh' => 'text/x-script.tcsh',
		'tex' => 'application/x-tex',
		'texi' => 'application/x-texinfo',
		'texinfo' => 'application/x-texinfo',
		'text' => 'application/plain',
		'text' => 'text/plain',
		'tgz' => 'application/gnutar',
		'tgz' => 'application/x-compressed',
		'tr' => 'application/x-troff',
		'tsi' => 'audio/tsp-audio',
		'tsp' => 'application/dsptype',
		'tsp' => 'audio/tsplayer',
		'tsv' => 'text/tab-separated-values',
		'turbot' => 'image/florian',
		'uil' => 'text/x-uil',
		'uni' => 'text/uri-list',
		'unis' => 'text/uri-list',
		'unv' => 'application/i-deas',
		'uri' => 'text/uri-list',
		'uris' => 'text/uri-list',
		'ustar' => 'application/x-ustar',
		'ustar' => 'multipart/x-ustar',
		'uu' => 'application/octet-stream',
		'uu' => 'text/x-uuencode',
		'uue' => 'text/x-uuencode',
		'vcd' => 'application/x-cdlink',
		'vcs' => 'text/x-vcalendar',
		'vda' => 'application/vda',
		'vdo' => 'video/vdo',
		'vew' => 'application/groupwise',
		'viv' => 'video/vivo',
		'viv' => 'video/vnd.vivo',
		'vivo' => 'video/vivo',
		'vivo' => 'video/vnd.vivo',
		'vmd' => 'application/vocaltec-media-desc',
		'vmf' => 'application/vocaltec-media-file',
		'voc' => 'audio/voc',
		'voc' => 'audio/x-voc',
		'vos' => 'video/vosaic',
		'vox' => 'audio/voxware',
		'vqe' => 'audio/x-twinvq-plugin',
		'vqf' => 'audio/x-twinvq',
		'vql' => 'audio/x-twinvq-plugin',
		'vrml' => 'application/x-vrml',
		'vrml' => 'model/vrml',
		'vrml' => 'x-world/x-vrml',
		'vrt' => 'x-world/x-vrt',
		'vsd' => 'application/x-visio',
		'vst' => 'application/x-visio',
		'vsw' => 'application/x-visio',
		'w60' => 'application/wordperfect6.0',
		'w61' => 'application/wordperfect6.1',
		'w6w' => 'application/msword',
		'wav' => 'audio/wav',
		'wav' => 'audio/x-wav',
		'wb1' => 'application/x-qpro',
		'wbmp' => 'image/vnd.wap.wbmp',
		'web' => 'application/vnd.xara',
		'wiz' => 'application/msword',
		'wk1' => 'application/x-123',
		'wmf' => 'windows/metafile',
		'wml' => 'text/vnd.wap.wml',
		'wmlc' => 'application/vnd.wap.wmlc',
		'wmls' => 'text/vnd.wap.wmlscript',
		'wmlsc' => 'application/vnd.wap.wmlscriptc',
		'word' => 'application/msword',
		'wp' => 'application/wordperfect',
		'wp5' => 'application/wordperfect',
		'wp5' => 'application/wordperfect6.0',
		'wp6' => 'application/wordperfect',
		'wpd' => 'application/wordperfect',
		'wpd' => 'application/x-wpwin',
		'wq1' => 'application/x-lotus',
		'wri' => 'application/mswrite',
		'wri' => 'application/x-wri',
		'wrl' => 'application/x-world',
		'wrl' => 'model/vrml',
		'wrl' => 'x-world/x-vrml',
		'wrz' => 'model/vrml',
		'wrz' => 'x-world/x-vrml',
		'wsc' => 'text/scriplet',
		'wsrc' => 'application/x-wais-source',
		'wtk' => 'application/x-wintalk',
		'xdr' => 'video/x-amt-demorun',
		'xgz' => 'xgl/drawing',
		'xif' => 'image/vnd.xiff',
		'xl' => 'application/excel',
		'xla' => 'application/excel',
		'xla' => 'application/x-excel',
		'xla' => 'application/x-msexcel',
		'xlb' => 'application/excel',
		'xlb' => 'application/vnd.ms-excel',
		'xlb' => 'application/x-excel',
		'xlc' => 'application/excel',
		'xlc' => 'application/vnd.ms-excel',
		'xlc' => 'application/x-excel',
		'xld' => 'application/excel',
		'xld' => 'application/x-excel',
		'xlk' => 'application/excel',
		'xlk' => 'application/x-excel',
		'xll' => 'application/excel',
		'xll' => 'application/vnd.ms-excel',
		'xll' => 'application/x-excel',
		'xlm' => 'application/excel',
		'xlm' => 'application/vnd.ms-excel',
		'xlm' => 'application/x-excel',
		'xls' => 'application/excel',
		'xls' => 'application/vnd.ms-excel',
		'xls' => 'application/x-excel',
		'xls' => 'application/x-msexcel',
		'xlt' => 'application/excel',
		'xlt' => 'application/x-excel',
		'xlv' => 'application/excel',
		'xlv' => 'application/x-excel',
		'xlw' => 'application/excel',
		'xlw' => 'application/vnd.ms-excel',
		'xlw' => 'application/x-excel',
		'xlw' => 'application/x-msexcel',
		'xm' => 'audio/xm',
		'xmz' => 'xgl/movie',
		'xpix' => 'application/x-vnd.ls-xpix',
		'xpm' => 'image/x-xpixmap',
		'xpm' => 'image/xpm',
		'x-png' => 'image/png',
		'xsr' => 'video/x-amt-showrun',
		'xwd' => 'image/x-xwd',
		'xwd' => 'image/x-xwindowdump',
		'xyz' => 'chemical/x-pdb',
		'z' => 'application/x-compress',
		'z' => 'application/x-compressed',
		'zip' => 'application/x-compressed',
		'zip' => 'application/x-zip-compressed',
		'zip' => 'application/zip',
		'zip' => 'multipart/x-zip',
		'zoo' => 'application/octet-stream',
		'zsh' => 'text/x-script.zsh',
	);
	
	$file = $fileTarget.$fileName;
	
	/*
	$fp = fopen($file,'rb');
	if($fp) {
		$rozmiarPliku = filesize($repository.$attachment);
		echo "rozmiarPliku = ".$rozmiarPliku." bytes<br>";
		
		$typPliku = filetype($repository.$attachment);
		echo "typPliku = ".$typPliku."<br>";
		
		$statystykiPliku = fstat($fp);
		echo "statystykiPliku = ";
		print_r(array_slice($statystykiPliku, 13));
		echo "<br>";
		
		$czesciPliku = pathinfo($repository.$attachment);
		echo "czesciPliku = ";
		print_r(array_slice($czesciPliku, 0));
		echo "<br>";
	}
	else if(!$fp) {
		echo "Błąd otwierania pliku";
		exit();
	}
	fclose($fp);
	* /
	
	$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
	//echo "extension = ".$extension."<br>";
	
	//PHP 5
	if(function_exists('finfo')) {
		$fileInfo = new finfo(FILEINFO_MIME);
		//$fileInfo = new finfo(FILEINFO_MIME_TYPE);
		$mimeType = $fileInfo->file($file);
		//$mimeType = $fileInfo->filename($file);
		//$mimeType = $file_info->buffer(file_get_contents($file));
		return $mimeType;
	}
	//PHP 4
	elseif(function_exists('finfo_open')) {
		$fileInfo = finfo_open(FILEINFO_MIME);
		//$fileInfo = finfo_open(FILEINFO_MIME_TYPE);
		$mimeType = finfo_file($fileInfo, $file);
		finfo_close($fileInfo);
		return $mimeType;
	}
	elseif(isset($mimeTypes[$extension])) {
		return $mimeTypes[$extension];
	}
	elseif(function_exists('mime_content_type')) {
		echo $mimeType = mime_content_type($file);
		return $mimeType;
	}
	else {
		throw new \Exception("Nieznany typ pliku");
		return 'application/octet-stream';
	}
}*/
?>