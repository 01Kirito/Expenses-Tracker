<?php
require_once 'Schedule.php';
date_default_timezone_set('Asia/Baghdad');
$Schedule = new Schedule();

$executionFile = "C:/xampp/php/php.exe";
$commandDir = "C:/xampp/htdocs/ExpensesTracker/Command/";
$firstCronJob = "0 0 * * * $executionFile {$commandDir}redisCache.php";
$secondCronJob = "*/10 * * * * $executionFile {$commandDir}daily_notification.php";
$thirdCronJob = "0 0 1 * * $executionFile {$commandDir}monthly_report.php";

$Schedule->setCronJob("Mailing",$firstCronJob);
$Schedule->setCronJob("Event",$secondCronJob);
$Schedule->setCronJob("Update",$thirdCronJob);

$Schedule->run();