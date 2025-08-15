<?php

// rb opens the file in read binary mode
$file = fopen('./messages.txt', 'rb');

if (!$file) {
    die("Unable to open file");
}

// while we aren't at the end of the file, read 8 bytes and echo
while (!feof($file)) {
    $chunk = fread($file, 8);

    if ($chunk === false) {
        break;
    }

   echo 'read:'.$chunk . PHP_EOL;
}

// tidy up
fclose($file);