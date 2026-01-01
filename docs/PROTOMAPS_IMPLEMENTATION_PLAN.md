# Protomaps Implementation Plan

## Goal
Replace Google/Mapbox/Stadia with a **Self-Hosted** vector tile solution using **Protomaps (PMTiles)** to reduce map costs to near-zero (infrastructure only).

## Architecture

### 1. Data Source (The `.pmtiles` Archive)
We need a single file containing vector tiles for our target region (or the planet).
*   **Source**: [Protomaps Daily Builds](https://protomaps.com/downloads/osm).
*   **Scope**: "Benelux" (Initial) -> "Europe" or "Planet" (Future).
*   **Update Frequency**: Monthly (via a scheduled job or manual script).

### 2. Storage (The Backend)
The unique advantage of PMTiles is that it requires **no backend server**. It uses HTTP Range Requests to fetch only the bytes needed for a specific tile from a single massive file.
*   **Service**: Scaleway Object Storage (S3 compatible).
*   **Bucket**: `velogrid-maps-prod` (Public/or via CDN).
*   **CORS**: Must be configured to allow `GET` and `HEAD` from `https://app.velogrid.com`.

### 3. The Frontend (The Viewer)
We need to update our map library to understand the `.pmtiles` protocol.
*   **Library**: `maplibre-gl` (already in use or equivalent Leaflet plugin).
*   **Adapter**: `pmtiles` (npm package).

## Step-by-Step Implementation

### Phase 1: PoC (Proof of Concept)
1.  **Download Map**: Download the `benelux.pmtiles` from Protomaps.
2.  **Upload**: Upload to a Scaleway S3 bucket `velogrid-maps-poc`.
3.  **Frontend Integration**:
    *   `npm install pmtiles maplibre-gl`
    *   Create a simple `MapComponent` that loads the `pmtiles` protocol.
    *   Point it to the S3 URL.
4.  **Styling**: Use a basic OSM style (e.g., from `protomaps-themes-base`) to verify rendering.

### Phase 2: Production Setup
1.  **Automated Updates**:
    *   Create a Scaleway Job (Container) `MapUpdater`.
    *   Script: Downloads latest `.pmtiles`, validates it, uploads to S3 with a version tag (e.g., `maps/benelux-2025-01.pmtiles`).
    *   Updates a `manifest.json` pointing "current" to the new file.
2.  **CDN / Caching**:
    *   Ensure Cloudflare or Scaleway Edge Services caches the tile requests.
    *   Since the file name changes (or cache busting), users always get fresh maps.

### Phase 3: Styling (The "VeloGrid" Look)
1.  **Design**: Use **Maputnik** locally.
2.  **Customize**:
    *   Match administrative boundaries (Provinces) colors to VeloGrid theme.
    *   Tone down non-essential POIs.
3.  **Deploy**: Upload `style.json` to the same S3 bucket.

## Cost Analysis (Revisited)
*   **Storage**: ~1GB (Benelux) to ~100GB (Planet). Cost: €0.02 - €1.30 / month.
*   **Compute**: €0 (Client-side rendering).
*   **Egress**: 20KB per tile. Heavy caching = minimal cost.

## Dependencies
*   `pmtiles` (NPM)
*   `maplibre-gl` (NPM)
*   Scaleway Bucket (Infrastructure)

## Risks
*   **Browser Compatibility**: PMTiles works in all modern browsers.
*   **Initial Load**: The header fetch might add slight latency on first load (milliseconds).
