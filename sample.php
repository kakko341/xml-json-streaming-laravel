<?php

$reader = new XMLReader();
$reader->open('example.xml');

$result = [];

while ($reader->read()) {
    if ($reader->nodeType === XMLReader::ELEMENT && $reader->name === 'a') {
        $reader->read(); // move to text node
        $result['a'][] = $reader->value;
    }
}

$reader->close();

$json = json_encode($result, JSON_UNESCAPED_UNICODE);
file_put_contents('output.json', $json);

?>