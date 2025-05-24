# XML to JSON Streaming in Laravel

This repository explores memory-efficient techniques for converting large XML files into JSON format using streaming and buffering strategies in Laravel.

## Features

- Memory-optimized JSON writing using string `append()`
- Custom buffer flushing mechanism to reduce memory spikes
- Practical example of Laravel-style file handling
- Insight into performance trade-offs and optimization decisions

## Articles

This repository includes two markdown articles:

1. **[Switching to Append Method](articles/memory-optimized-json-writing-with-buffering.md)**  
   Replacing arrays with string-based appending to reduce memory usage.

2. **[Adding Buffered Output](articles/memory-optimized-json-writing-with-buffering.md)**  
   Using a 1MB buffer to periodically flush content and avoid long delays.

Both sections are combined into one file for easier access.

## Usage

The approach described is especially helpful when dealing with large XML files and trying to avoid PHP memory limit issues. Instead of collecting all data into an array and `json_encode` at once, it streams and appends JSON fragments directly to a file.

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.
