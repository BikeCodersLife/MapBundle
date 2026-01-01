# Self-Hosted Maps Guide (Cost Saving Strategy)

To achieve **€0.00 per 1k tiles** (paying only for storage & bandwidth), you can switch from commercial providers (Google/Mapbox/Stadia) to a **Self-Hosted Vector Tile** solution.

## 1. The Technology: PMTiles
The industry standard for efficient serverless map hosting is **PMTiles**.
- **What is it?**: A single file archive (like a zip) containing millions of vector tiles.
- **Why?**: It allows you to host the entire planet (or a region) on **S3 (Minio/Scaleway Object Storage)** and serve it via HTTP Range Requests. No running server required.

## 2. Where to get the Data?
You do not need to generate it yourself. You can download ready-made builds.

### A. Protomaps (Recommended)
[Protomaps](https://protomaps.com/) offers daily builds of OpenStreetMap data.
- **Small Areas**: Extract just your region (e.g., "Benelux") for free using their isolate tool.
- **Whole Planet**: ~100GB single file.
- **Cost**: Free to download/generate for small-medium scale.

### B. Planetiler
If you want to customize the map (add custom layers, hillshading), use [Planetiler](https://github.com/onthegomap/planetiler) to generate your own `.pmtiles` file from an OSM PBF dump (e.g., form Geofabrik).

## 3. Implementation Steps

### Step 1: Host the File
Upload `planet.pmtiles` (or `benelux.pmtiles`) to your S3 bucket (e.g., `velogrid-maps`).
- Storage Cost: ~50GB = €0.65/month (Scaleway).

### Step 2: Serve the Tiles
You need a lightweight viewer/renderer on the frontend.
- Use **MapLibre GL JS** (Open Source fork of Mapbox GL).
- Use the `pmtiles` protocol adapter.

```javascript
import { Protocol } from 'pmtiles';
import maplibregl from 'maplibre-gl';

let protocol = new Protocol();
maplibregl.addProtocol("pmtiles", protocol.tile);

const map = new maplibregl.Map({
    container: 'map',
    style: 'https://demotiles.maplibre.org/style.json', // Your Visual Style JSON
    center: [4.4699, 50.5039], // Belgium
    zoom: 7
});
```

### Step 3: Bandwidth (Egress)
This is the only variable cost.
- **Commercial**: You pay per "MapView" or "Tile Load" (~€5/1k).
- **Self-Hosted**: You pay for **Egress Bandwidth**.
    - Vector tiles are tiny (~20-40KB).
    - Caching (Cloudflare/FrontDoor) reduces this by 80-90%.
    - **Result**: You pay pennies for terabytes of traffic.

## 4. OpenFreeMap (Zero Config Option)
If you don't want to manage S3, check [OpenFreeMap.org](https://openfreemap.org/).
- They host the tiles for free.
- You just point your MapLibre to their endpoint.
- **Cost**: Truly Free (donation based).
- **Pros**: Zero infrastructure.
- **Cons**: Less control over uptime/SLA.

## 5. Custom Look & Feel (Theming)
**YES**, you can give the map a completely custom look.
Vector tiles separate the **Data** (Roads, Rivers) from the **Design** (Colors, Fonts).

### How it works
You load a `style.json` into MapLibre. This file defines:
- "Water layers should be dark blue `#000033`"
- "Highways should be neon pink `2px` wide"
- "Hide all POI labels"

### Tools to Create Styles
You do not edit the JSON manually. Use a visual editor:
1.  **Maputnik** (Open Source Editor):
    - Graphic interface to design your map.
    - Export the `style.json`.
    - Host it on your server.
2.  **Mapbox Studio** (Alternative):
    - Export style -> Adjust for MapLibre compatibility.

