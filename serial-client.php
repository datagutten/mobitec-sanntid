#! /usr/bin/php
<?Php
require 'config.php';

//https://github.com/Xowap/PHP-Serial
require 'PHP-Serial/src/PhpSerial.php';
// Let's start the class
$serial = new PhpSerial;

// First we must specify the device. This works on both linux and windows (if
// your linux serial device is /dev/ttyS0 for COM1, etc)
if($serial->deviceSet($config['serial_port']))
{
	//Set correct baud rate, parity, length, stop bits, flow control for mobitec sign
	$serial->confBaudRate(4800);
	$serial->confParity("none");
	$serial->confCharacterLength(8);
	$serial->confStopBits(1);
	$serial->confFlowControl("none");

	// Then we need to open it
	$serial->deviceOpen();

	$data='';
	while($data!==false)
	{
		if($config['host']!==false)
			$data=file_get_contents($config['host']);
		else
			$data=shell_exec('php mobitec-sanntid.php');
		//file_put_contents('sanntid.debug.mobitec',$data);
		$serial->sendMessage($data);
		sleep(30);
	}
}