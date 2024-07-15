<?php

class Schedule {
    protected static $currentTimeStamp;
    protected static array $cronJob;
    public function __construct() {
        date_default_timezone_set('Asia/Baghdad');
        static::$currentTimeStamp = getdate();

    }

    public function run(){
        foreach(static::$cronJob as $key => $cronJob){
            $minute = $cronJob[0];
            $hour = $cronJob[1];
            $dayOfMonth = $cronJob[2];
            $month = $cronJob[3];
            $dayOfWeek = $cronJob[4];
            $executeFile = $cronJob[5];
            $command = $cronJob[6];
            if($minute == "*" || $minute == static::$currentTimeStamp['minutes'] || static::checkForMode($minute,"minutes")){
                if($hour == "*" || $hour == static::$currentTimeStamp['hours'] || static::checkForMode($hour,"hours")){
                    if($dayOfMonth == "*" || $dayOfMonth == static::$currentTimeStamp['mday'] || static::checkForMode($dayOfMonth,"mday")){
                        if($month == "*" || $month == static::$currentTimeStamp['mon'] || static::checkForMode($month,"mon")){
                            if($dayOfWeek == "*" || $dayOfWeek == static::$currentTimeStamp['wday'] || static::checkForMode($dayOfWeek,"wday")){
                                echo "Running the command: ".$executeFile." ".$command."<br>";
                                exec($executeFile." ".$command, $output);
                                print_r($output);
                            }
                        }
                    }
                }
            }

        }

    }

    public function setCronJob($name,$cronJob) {
        $cronJobDetail = explode(" ", $cronJob);
        foreach($cronJobDetail as $key => $value){
            static::$currentTimeStamp[$key] = $value;
        }
        static::$cronJob[$name] = $cronJobDetail;
    }

    protected function checkForMode($string,$name){
        $condition = strpos($string, "/");
        if($condition){
            $numberWithoutSlash = explode("/",$string);
            if((static::$currentTimeStamp[$name] % (int)$numberWithoutSlash[1]) ==  0){
                return true;
            }
        }
        return false;
    }
}