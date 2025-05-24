# Memory-Optimized JSON Writing with Buffering

## Introduction

This article explains the process of optimizing memory usage when writing large JSON files in a Laravel application. The solution was developed after identifying performance bottlenecks in an API that used XMLReader for processing large XML files.

## Problem

Initially, we built an API using XMLReader to convert XML to JSON. However, during testing, it was discovered that the method of collecting all data into an array and then encoding it using `json_encode` consumed excessive memory—up to 10 times the original file size (e.g., 400MB memory usage for a 40MB file).

## Step 1: Replace with `append`

To mitigate this, we replaced the array accumulation approach with incremental writing using an `append` method.

```php
$jsonStr->append();
$reader->read();
```

Instead of storing data in an array, we maintained a JSON-formatted string and appended each chunk. This solved the memory issue but introduced a new problem: the process that used to take a few seconds now took hours.

## Step 2: Add Buffering

To balance memory usage and speed, we implemented a buffer. Once the buffer reached a specified size (e.g., 1MB), it would flush the contents to disk.

### Constructor

```php
public function __construct(int $buffer_size = 1048576) {
    // 1MB buffer size
    $this->buffer_size = $buffer_size;
}
```

### Append Method

```php
public function append(string $str): void
{
    $this->sumStr .= $str;
    $str_size = strlen($this->sumStr);

    if ($str_size > ($this->buffer_size)) {
        $this->disk->put($this->write_path, "");
        $fp = fopen($this->write_path, "a");
        if ($fp) {
            fwrite($fp, $this->sumStr);
            fclose($fp);
            $this->sumStr = "";
        }
    }
}
```

## Step 3: Usage

The `FileWriter` class is instantiated and the `append` method is called during the XML reading process.

```php
$fileWriter = new FileWriter();

$fileWriter->append($str);
$reader->read();
```

## Notes

- Using Laravel’s native `append` method did not effectively reduce memory usage because it retained data in memory.

## Conclusion

This change significantly improved memory efficiency without severely impacting performance, providing a practical way to handle large-scale JSON generation in Laravel.
