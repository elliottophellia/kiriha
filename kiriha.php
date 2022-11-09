<?php
/*

        Kiriha  - Admin Panel Scanner Build in PHP7.4-CLI
        Author  - @elliottophellia
        Version - 1.0.0
        
        DISCLAIMER: 
        THIS SCRIPT IS FOR EDUCATIONAL PURPOSES ONLY
        I AM NOT RESPONSIBLE FOR ANY ILLEGAL ACTIVITIES YOU MAY DO WITH THIS SCRIPT
        USE AT YOUR OWN RISK.

*/
error_reporting(0);
set_time_limit(0);
$brown = "\e[0;33m";
$green = "\e[0;32m";
$red = "\e[0;31m";
$clear = "\e[0m";

function banner()
{

    print $GLOBALS['red'];
    print "██   ██ ██ ██████  ██ ██   ██  █████  \n";
    print $GLOBALS['brown'];
    print "██  ██  ██ ██   ██ ██ ██   ██ ██   ██ \n";
    print $GLOBALS['clear'];
    print "█████   ██ ██████  ██ ███████ ███████ \n";
    print $GLOBALS['brown'];
    print "██  ██  ██ ██   ██ ██ ██   ██ ██   ██ \n";
    print $GLOBALS['red'];
    print "██   ██ ██ ██   ██ ██ ██   ██ ██   ██ \n";
    print $GLOBALS['clear'];
    print "github.com/elliottophellia/kiriha.git\n\n";
}

function usage()
{

    print "Usage : php kiriha.php [domain] [wordlist]\n\n";
    print "[domain] - Domain to scan (with or without http/https)\n";
    print "[wordlist] - Wordlist to use (optional, default is wordlist.txt)\n\n";
    print "Example : php kiriha.php rei.my.id wordlist.txt\n\n";
}

if ($argv[1] == "-h" || $argv[1] == "--help") {
    banner();
    usage();
    exit();
} elseif ($argv[1]) {
    banner();
    print "Scanning...\n\n";

    $domain = preg_replace('/(http|https):\/\//', '', $argv[1]);
    $domain = preg_replace('/\/.*/', '', $domain);

    if (empty($argv[2])) {
        $wordlist = "wordlist.txt";
    } else {
        $wordlist = $argv[2];
    }

    $file = file($wordlist);
    foreach ($file as $line) {
        $line = trim($line);
        $url = "http://" . $domain . "/" . $line;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpcode == 200) {
            print "[" . date("Y/m/d - H:i:s") . "] " . "[" . $GLOBALS['green'] . $httpcode . $GLOBALS['clear'] . "] " . $GLOBALS['green'] . $url . $GLOBALS['clear'] . "\n";
            print "\nAdmin panel found : " . $url . "\n";
            exit();
        } else {
            print "[" . date("Y/m/d - H:i:s") . "] " . "[" . $GLOBALS['red'] . $httpcode . $GLOBALS['clear'] . "] " . $GLOBALS['red'] . $url . $GLOBALS['clear'] . "\n";
        }
    }
} else {
    banner();
    print "\nInvalid option, see --help for usage\n";
    exit();
}
