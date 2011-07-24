<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>EVE economy: tool for traders </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<?php
require_once './leechs/Leechs.php';

$leech = new Leechs();

$status = $leech->getServerStatus();
echo ('Vesrion : '.$status['@attributes']['version'].'<br/>');
echo ('Current time : '.$status['currentTime'].'<br/>');
echo ('Cache time : '.$status['cachedUntil'].'<br/>');
echo ('Server status : '.((strtolower($status['result']['serverOpen'])=='true')?'active':'downtime').'<br/>');
echo ('Online players : '.$status['result']['onlinePlayers'].'<br/>');
?>
</body>
</html>