# XML to JSON Streaming API in Laravel

This project demonstrates an API built with Laravel that reads large XML files, extracts necessary elements, and converts them into JSON format efficiently.

## Overview

Initially, we developed an API using Laravel to parse XML files and return only the required elements in JSON. Originally, SimpleXML was considered, but due to heavy memory consumption with large files, we switched to using `XMLReader`, which allows streaming XML parsing with minimal memory overhead.

## Features

- **XMLReader Usage**  
  Utilizes PHP's `XMLReader` for memory-efficient XML parsing.  
- **Unit Testing**  
  Includes tests verifying output JSON content correctness and file creation.  
- **Handling Large Files**  
  Implements streaming upload to S3 using Laravel's `readStream` and buffered writing to reduce memory usage during JSON generation.

## Performance Optimization

During testing, we observed excessive memory usage while accumulating parsed data before JSON encoding. To address this, we replaced array accumulation with a streaming approach that appends JSON strings gradually to a file. 

However, naive appending led to very slow processing, so a buffer mechanism was introduced: data is accumulated in memory up to a configurable buffer size (default 1MB), then flushed to disk, greatly improving memory consumption without a significant performance penalty.


## Articles

- [Part 1: Building the XMLReader API](./articles/streaming-xml-to-json-with-xmlreader.md)  
  Covers the use of `XMLReader`, basic API design, unit testing, and streaming to S3.

- [Part 2: Memory Optimization via Streaming and Buffering](./articles/memory-optimized-json-writing-with-buffering.md)  
  Details performance tuning using buffered JSON streaming and custom append methods.

## Additional Notes

- The append method implemented here is customized to avoid Laravel's built-in append method, which retains data in memory and is less efficient for large streams.  
- The streaming upload to S3 requires proper handling of file pointers and stream closing to avoid file locking issues.

## License

This project is licensed under the MIT License.
