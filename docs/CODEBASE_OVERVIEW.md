# BikeCoders MapBundle Codebase Overview

**Purpose**: This bundle manages the acquisition, processing, and serving of vector map tiles (PMTiles) for the application. It handles large-scale geospatial data downloads and provides the API endpoints for frontend map rendering.

---

## Key Components

### 1. Commands (`src/Command`)
- `map:download` - Downloads vector map data (OSM/Protomaps) for specified regions.
- `map:process` - Processes and extracts specific regions from global datasets (e.g., extracting Benelux from Europe).
- `map:cleanup` - Removes temporary map files to free up space.

### 2. Controllers (`src/Controller`)
- `MapController` - Handles HTTP requests for map tiles.
  - Endpoint: `/api/maps/{region}.pmtiles`
  - Features: Supports HTTP Range requests for efficient streaming of large PMTiles archives.

### 3. Services (`src/Service`)
- `MapSourceService` - Manages different map data sources (BBBike, Protomaps, Geofabrik).
- `PMTilesService` - Utilities for handling PMTiles archives.

---

## Infrastructure

- **Storage**: Maps are stored locally or in S3 buckets (configurable).
- **Format**: Uses [PMTiles](https://github.com/protomaps/PMTiles), a single-file archive format for serverless map hosting.
- **Frontend Integration**: Designed to work with `protomaps-leaflet` on the client side.

---

## Routes

- `/api/maps/{region}.pmtiles` - Serves the vector tiles implementation for the requested region (e.g., `benelux`, `europe`).

---

**Last Updated**: 2026-01-01
