# BikeCoders Map Bundle

A Symfony bundle for managing self-hosted map data using Protomaps (PMTiles) and MapLibre.

## Features

- **MapSource Entity**: Tracks downloaded map regions, versions, and S3 keys.
- **CLI Command**: `map:download` to fetch PMTiles from a URL and upload them to your S3 storage.
- **S3 Integration**: Stores map tiles efficiently in object storage.
- **Frontend Ready**: Designed to work with MapLibre GL JS (frontend implementation required).

## Installation

1.  **Require the bundle**:
    (Assuming specific local path configuration)
    ```bash
    composer require bikecoderslife/map-bundle
    ```

2.  **Enable the bundle** (if not auto-configured):
    ```php
    // config/bundles.php
    return [
        // ...
        BikeCoders\MapBundle\BikeCodersMapBundle::class => ['all' => true],
    ];
    ```

3.  **Run Migrations**:
    ```bash
    php bin/console doctrine:migrations:migrate
    ```

## Configuration

This bundle uses your application's default Flysystem S3 storage. Ensure your `flysystem.yaml` or services are configured with a `default.storage` (or mapped service).

## Usage

### Downloading Maps

Use the console command to download a `.pmtiles` file and register it:

```bash
php bin/console map:download "https://build.protomaps.com/20241028.pmtiles" "benelux" \
  --min-zoom=0 \
  --max-zoom=14 \
  --bounds='[2.5,49.5,7.0,53.5]'
```

- **Arguments**:
    - `url`: Direct URL to the `.pmtiles` file.
    - `region`: A unique identifier/slug for this map region (e.g., "benelux", "world").
- **Options**:
    - `--min-zoom`: Minimum zoom level supported by the file (default: 0).
    - `--max-zoom`: Maximum zoom level (default: 15).
    - `--bounds`: JSON array of bounding box `[minLon, minLat, maxLon, maxLat]`.

### Entity: MapSource

The `MapSource` entity contains the metadata needed for your frontend to initialize the map source (URL, attribution, bounds).

```php
use BikeCoders\MapBundle\Entity\MapSource;

// In your controller:
$maps = $entityManager->getRepository(MapSource::class)->findBy(['isActive' => true]);
```
