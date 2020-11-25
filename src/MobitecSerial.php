<?php
namespace datagutten\mobitec_sis;


use datagutten\mobitec\encoder;
use Exception;
use datagutten\phpSerial;



class MobitecSerial extends encoder
{
    /**
     * @var phpSerial\SerialConnection
     */
    private $serial;
    public $debug = false;

    /**
     * MobitecSerial constructor.
     * @param $serial_port
     * @throws Exception
     */
    public function __construct($serial_port)
    {
        $this->serial = new phpSerial\SerialConnection();
        $this->serial->setDevice($serial_port);
        $this->serial->setBaudRate(4800);
        $this->serial->setParity("none");
        $this->serial->setCharacterLength(8);
        $this->serial->setStopBits(1);
        $this->serial->setFlowControl("none");
        $this->serial->open();
    }

    public function serial_output($data, int $address, int $width, int $height)
    {
        parent::output($data, $address, $width, $height);
    }
}