<?php


namespace datagutten\mobitec_sis;


use datagutten\mobitec\encoder;
use DateTime;

class MobitecSanntid
{
    /**
     * @var MobitecSerial
     */
    private $mobitec;
    private $config;
    private static $debug=true;

    public function __construct($config)
    {
        $this->mobitec = new MobitecSerial($config['serial_port']);
        $this->config = $config;
    }
    public static function find_departure($stop, $destination)
    {
        $departures = file_get_contents(sprintf('https://www.sanntidpluss.no/departures/stop/%s.json', $stop));
        $departures = json_decode($departures, true);

        foreach($departures['data']['stopPlace']['estimatedCalls'] as $departure)
        {
            if($departure['destinationDisplay']['frontText']!=$destination)
                continue;
            return $departure;
        }
    }

    public static function departure_time(array $departure)
    {
        $now = new DateTime();
        $departure_time = new DateTime($departure['expectedDepartureTime']);
        $diff = $departure_time->diff($now);
        if($diff->i == 0)
            return self::write_now();
        elseif ($diff->i<=9)
            return self::write_minutes($diff->i);
        else
            return self::write_time($departure_time->format('H:i'));
    }

    public function departure_output($stop, $destination)
    {
        $departure = self::find_departure($stop, $destination);
        $output = self::write_line_number($departure['serviceJourney']['journeyPattern']['line']['publicCode']);
        $output .= self::departure_time($departure);
        $this->mobitec->serial_output($output, 11,28,16);
    }

    public static function write_time(string $time)
    {
        return encoder::write_text($time,1,16,54, self::$debug);
    }

    public static function write_minutes(int $minutes)
    {
        return encoder::write_text(sprintf('%d min', $minutes),0,16,54, self::$debug);
    }

    public static function write_now()
    {
        return encoder::write_text('Nå',8,16,54, self::$debug);
    }

    public static function write_line_number(string $line_number)
    {
        return encoder::write_text($line_number,7,7,54, self::$debug);
    }
}