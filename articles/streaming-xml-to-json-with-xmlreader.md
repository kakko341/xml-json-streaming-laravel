# Streaming XML to JSON with XMLReader

## ① XMLReader

Using Laravel, I developed an API that reads XML files, extracts necessary elements, and converts them into JSON. Initially, I considered using SimpleXML, but it consumed too much memory for large files. So, I opted for XMLReader instead.

```php
$xmlFile = new XMLReader();
$xmlFile->XML('filename');
```

Create an instance and load the file.

```php
$jsonStr = [];
```

Prepare an array to store the extracted data.

```php
while ($reader->read()) {
    if ($reader->name == "a" && $reader->nodeType == XMLReader::ELEMENT) {
        $aVal = $reader->value;
        $jsonStr['a'] = $aVal;
        $reader->read();
    }
}
```

If an element named "a" is found, its value is stored in `$aVal` and added to `$jsonStr`. After each entry, `$reader->read()` advances to the next node.

```php
$reader->close();
```

Close the reader after completion.

```php
$json = json_encode($jsonStr, JSON_UNESCAPED_UNICODE);
```

Convert to JSON using `json_encode`. The official documentation was helpful in understanding node types:  
[PHP XMLReader Manual](https://www.php.net/manual/en/class.xmlreader.php)

## ② Unit Testing

Writing the unit tests was more challenging. I couldn’t find many good examples, so I focused on verifying the output JSON content.

```php
$obj = file_get_contents('path_to_file');
$json = json_decode($obj);
$aVal = $json->a;
$this->assertEquals('expected_value', $aVal);
```

Also included tests like `assertCount` for array size and `assertFileExists` to check if output files were created correctly.

## ③ Large Files

During testing, memory issues reappeared when uploading large files to S3. Even with XMLReader, the upload step failed due to memory limits. The solution was to use `readStream` before upload.

```php
$readStream = Storage::disk('local')->readStream('path_to_file');
Storage::disk('s3')->put('path_on_s3', $readStream);
```

And don’t forget to close the stream:

```php
fclose($readStream);
```
