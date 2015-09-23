<?php
/**
 * Created by PhpStorm.
 * User: Travis
 * Date: 9/17/2015
 * Time: 7:41 PM
 */
if(!isset($argv[1]) || !file_exists($argv[1]))
{
    echo "Please pass a valid file path.\n";
    exit;
}

if(!isset($argv[2]) || !file_exists($argv[2]))
{
    echo "Please pass an output file.\n";
    exit;
}

$file = fopen($argv[1], 'r');

//ob_start();

$out = fopen($argv[2], 'w');
$inGroup = false;
while($line = fgets($file))
{
//    echo $line;
    if(stristr($line, 'delivery has failed') || $inGroup)
    {
        $inGroup = true;

        preg_match('/<mailto:([0-9a-zA-Z_\.]+@[0-9a-zA-Z_\.]+\.[0-9a-zA-Z_\.]+)>/', $line, $matches);
        if (isset($matches[1])) {
            $email = $matches[1];
            echo $email."\n";
            fputcsv($out, [$email]);
        }
    }
    if(stristr($line, 'a problem occurred'))
        $inGroup = false;
}

//return ob_get_clean();

fclose($file);
fclose($out);