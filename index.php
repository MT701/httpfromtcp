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

$host = "127.0.0.1";
$port = 9001; // 9001 because 9000 was being used by php-fpm

// create a tcp server
$server = stream_socket_server("tcp://{$host}:{$port}", $errno, $errstr);

if (!$server) {
    die("Unable to start server: $errstr ($errno)");
}

$client = stream_socket_accept($server);

if ($client) {
    foreach (getLinesChannel($client) as $line) {
        echo 'read: ' . $line . PHP_EOL;
    }

    fclose($client);
}

fclose($server);