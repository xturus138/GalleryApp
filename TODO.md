# Dynamic Upload Loading Indicator Implementation

## Tasks

-   [x] Modify loading overlay HTML to support dynamic content (text and spinner variations)
-   [x] Update JavaScript upload handler to detect file types (image/video)
-   [x] Implement dynamic loading indicator based on file types
-   [x] Add CSS styles for different loading states (photo/video)
-   [x] Test upload functionality with photos and videos
-   [x] Fix upload validation error for folder field

## Implementation Details

-   Detect file types using MIME types before upload
-   Show different loading messages and spinners for photos vs videos
-   Maintain existing upload functionality
-   Update loading overlay to be more informative about upload progress
-   Fixed folder validation to accept 'none' value properly

## Files Modified

-   resources/views/dashboard/gallery.blade.php
-   app/Http/Controllers/AssetController.php

## Features Implemented

-   Dynamic loading indicator based on file types (photos, videos, or mixed)
-   Color-coded spinners (green for photos, orange for videos)
-   Informative loading text showing file counts
-   Support for mixed file uploads
-   Fixed upload validation for folder selection
