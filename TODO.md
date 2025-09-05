# Dynamic Pagination for Gallery Assets

## Tasks

-   [x] Keep gallery grid responsive with same card sizes
-   [x] Update pagination perPage to 9 (3 rows x 3 columns)
-   [x] Update both getAssets and getAssetsByFolder methods

## Implementation Details

-   Kept original responsive grid CSS: repeat(auto-fill, minmax(200px, 1fr))
-   Changed perPage to 9 to fill approximately 3 rows when 3 columns are displayed
-   Pagination occurs when 3 columns are "full" (9 items)

## Files Modified

-   app/Http/Controllers/AssetController.php (perPage values)

## Features Implemented

-   Responsive gallery grid with same card sizes
-   Dynamic pagination based on 3 columns being full (9 items per page)
-   Maintains visual consistency with previous layout
