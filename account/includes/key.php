<?php
function mkeyhash($dataA, $dataB, $dataC, $dataD, $dataE, $dataF)
{
	$saltA = "D5@fe%3wAv";
	$saltB = "rT9*dRsd1e";
	$saltC = "kQ4^tfH!we";
	
	$details = getIpDetails();
	$ipDetails = checkIpDetails($details);
	$ip = $ipDetails['ip'];
	$key = ip2long($ip);
	
	$data = $saltA.hash("adler32", $dataA.$dataB.$dataC).$saltB.hash("crc32b", $dataD.$dataE.$dataF).$saltC;
	$mKey = hash_hmac("sha512", $data, $key);
	
	return $mKey;
}

function skeyhash($path, $userId, $userEmail, $userKey)
{
	$saltA = "Aw8aN#dr&i";
	$saltB = "uNi)7Z%E3s";
	$saltC = "o%r1StGm3e";
	
	$sKey = getuserdata($path, "facelike", "skey", "super_key");
	
	$key = rand(100000, 100000);
	
	$data = $saltA.hash("gost-crypto", $userId.$userEmail.$userKey).$saltB.$sKey.$saltC;
	$superKey = hash_hmac("whirlpool", $data, $key);
	
	return $superKey;
}

function rkeyhash($dataA, $dataB, $dataC, $dataD, $dataE, $dataF)
{
	$saltA = "uN4+e=Xe0t";
	$saltB = "Ro{di5b&8r";
	$saltC = "nT:4s<e8>f";
	$saltD = "b5E.jD,r3h";
	
	$key = randomString(10);
	
	$data = $saltA.hash("ripemd320", $dataA.$dataB).$saltB.hash("fnv1a64", $dataC.$dataD).$saltC.hash("snefru256", $dataE.$dataF).$saltD;
	$rKey = hash_hmac("tiger192,4", $data, $key);
	
	return $rKey;
}
?>
