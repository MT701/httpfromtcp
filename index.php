<?php

function getLinesChannel($file)
{
    $string = "";

    // read the file in 8 byte chunks and continue until no more
    while (($chunk = fread($file, 8)) !== false && $chunk !== '') {
        // check if the chunk contains a newline character
        $position = strpos($chunk, "\n");

        if($position !== false) {
            // get everything up until the new line and append it to the running log 
            $string .= substr($chunk, 0, $position);

            yield $string;

            // reset to start keeping track until next newline
            $string = "";

            // Keep only the part after the newline for next iteration
            $chunk = substr($chunk, $position + 1);
        }

        $string .= $chunk;
    }
}

// rb opens the file in read binary mode
$file = fopen('./messages.txt', 'rb');

if (!$file) {
    die("Unable to open file");
}

foreach(getLinesChannel($file) as $line) {
    echo 'read: '. $line . PHP_EOL;
}

// tidy up
fclose($file);