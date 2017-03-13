<?Php
function showtime($seconds) //Vis tid i henhold til http://labs.trafikanten.no/ofte-stilte-spoersmaal.aspx#98
{
	if ($seconds<45)
		$time='Nå';
	elseif ($seconds>=45 && $seconds<=104)
		$time=1;
	elseif ($seconds>=105 && $seconds<=164)
		$time=2;
	elseif ($seconds>=165 && $seconds<=224)
		$time=3;
	elseif ($seconds>=225 && $seconds<=284)
		$time=4;
	elseif ($seconds>=285 && $seconds<=344)
		$time=5;
	elseif ($seconds>=345 && $seconds<=404)
		$time=6;
	elseif ($seconds>=405 && $seconds<=464)
		$time=7;
	elseif ($seconds>=465 && $seconds<=524)
		$time=8;
	elseif ($seconds>=525 && $seconds<=584)
		$time=9;
	elseif ($seconds>=585)
		$time=false;

return $time;
}

require 'mobitec-php/class_mobitec.php';
$mobitec=new mobitec;
require 'sanntidpluss/sanntidpluss_class.php';
$sanntid=new sanntidpluss;
require 'config.php';

$line=$config['line_number'];
$dest=$config['destination'];

$departures=$sanntid->getdepartures($config['stop_id']);
$departure=$departures[$line][$dest][0];
$arrival_time=strtotime($departure['MonitoredVehicleJourney']['MonitoredCall']['ExpectedArrivalTime']);
$seconds=$arrival_time-time();
$time=showtime($seconds);
//$time='Nå';
if($time=='Nå')
	$lines=$mobitec->write_text('Nå',8,16,54);
elseif($time==false)
{
	$lines=$mobitec->write_text(date('H:i',$arrival_time),3,16,101);
	$lines=$mobitec->write_text(date('H:i',$arrival_time),1,16,54);
	//$lines=$mobitec->write_text('88:88',1,16,54);
}
else
{
	//$time=8;
	$lines=$mobitec->write_text($time.' min',2,15,102);
	$lines=$mobitec->write_text($time.' min',0,16,54);
}
//$lines.=$mobitec->write_text($line,9,7,102); //Line number
$lines.=$mobitec->write_text($line,7,7,54); //Line number
//$lines.=$mobitec->write_text('å',0,7,54);

//$lines.=$mobitec->write_text('He',22,7,102);

$output=$mobitec->output($lines,11,28,16);
echo $output;
//file_put_contents('sanntid php.mobitec',$output);