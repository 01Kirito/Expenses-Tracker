<?php
require_once __DIR__.'/Schedule.php';
date_default_timezone_set('Asia/Baghdad');
$Schedule = new Schedule();

// for the localhost without dockerization
//$executionFile = "C:/xampp/php/php.exe";
//$commandDir = "C:/xampp/htdocs/ExpensesTracker/Command/";

$executionFile = "/usr/local/bin/php";
$commandDir = "/var/www/html/ExpensesTracker/Command/";
$firstCronJob = "*/5 * * * * $executionFile {$commandDir}redisCache.php";
$secondCronJob = "*/2 * * * * $executionFile {$commandDir}daily_notification.php";
$thirdCronJob = "0 0 1 * * $executionFile {$commandDir}monthly_report.php";

$Schedule->setCronJob("Mailing",$firstCronJob);
$Schedule->setCronJob("Event",$secondCronJob);
$Schedule->setCronJob("Update",$thirdCronJob);

$Schedule->run();