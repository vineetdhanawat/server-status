<!DOCTYPE html>
<html>
<head>
<title>Web Server Stats</title>
</head>
<body>
<?php
error_reporting(0);

if (ini_get('disable_functions')) {
	$disabled_funcs=array_map('trim',explode(',',ini_get('disable_functions')));
}

$action=$_GET["action"];

    $users[0]="Unavailable";
	$users[1]="--";
	$loadnow="Unavailable";
	$load15="--";
	$load30="--";

    if (in_array('exec',$disabled_funcs)) {
		$load=file_get_contents("/proc/loadavg");
		$load=explode(' ',$load);
		$loadnow=$load[0];
		$load15=$load[1];
		$load30=$load[2];
	} 
	else {
		$reguptime=trim(exec("uptime"));
		if ($reguptime) {
			if (preg_match("/, *(\d) (users?), .*: (.*), (.*), (.*)/",$reguptime,$uptime)) {
				$users[0]=$uptime[1];
				$users[1]=$uptime[2];
				$loadnow=$uptime[3];
				$load15=$uptime[4];
				$load30=$uptime[5];
			}
		}
	}

	if (in_array('shell_exec',$disabled_funcs)) {
		$uptime_text=file_get_contents("/proc/uptime");
		$uptime=substr($uptime_text,0,strpos($uptime_text," "));
	} 
	else {
		$uptime=shell_exec("cut -d. -f1 /proc/uptime");
	}

	$days=floor($uptime/60/60/24);
	$hours=str_pad($uptime/60/60%24,2,"0",STR_PAD_LEFT);
	$mins=str_pad($uptime/60%60,2,"0",STR_PAD_LEFT);
	$secs=str_pad($uptime%60,2,"0",STR_PAD_LEFT);

	echo "Avg Load (01 min) : $loadnow <br>";
	echo "Avg Load (15 min) : $load15 <br>\n";
	echo "Avg Load (30 min) : $load30 <br>\n";
	echo "Uptime : $days Days $hours Hours $mins Minutes $secs Seconds <br>";


?>
</body>
</html>