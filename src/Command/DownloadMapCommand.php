<?php

namespace BikeCodersLife\MapBundle\Command;

use BikeCodersLife\MapBundle\Entity\MapSource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use League\Flysystem\FilesystemOperator;

#[AsCommand(
    name: 'map:download',
    description: 'Downloads a PMTiles map file from Protomaps and stores it in S3',
)]
class DownloadMapCommand extends Command
{
    // Protomaps Builds URL (Example)
    // https://maps.protomaps.com/builds/
    // Direct link to Benelux/latest needs to be found or configured.
    // For now, using a placeholder or a direct known link if available.
    private const DEFAULT_URL = 'https://build.protomaps.com/20241028.pmtiles'; // Example

    public function __construct(
        private HttpClientInterface $httpClient,
        private FilesystemOperator $defaultStorage, // Using default storage for now, mapped in services.yaml
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('url', InputArgument::OPTIONAL, 'URL to the .pmtiles file', self::DEFAULT_URL)
            ->addArgument('region', InputArgument::OPTIONAL, 'Region Name', 'planet-demo')
            ->addOption('min-zoom', null, InputOption::VALUE_OPTIONAL, 'Minimum Zoom Level', 0)
            ->addOption('max-zoom', null, InputOption::VALUE_OPTIONAL, 'Maximum Zoom Level', 15)
            ->addOption('bounds', null, InputOption::VALUE_OPTIONAL, 'Bounds as JSON [minLon, minLat, maxLon, maxLat]', null)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $url = $input->getArgument('url');
        $region = $input->getArgument('region');
        
        $io->title("Downloading Map Data: $region");
        $io->text("Source: $url");

        // 1. Download Stream
        // In a real scenario, we might want to stream directly to S3 to avoid disk usage,
        // but Flysystem stream writes depend on the adapter.
        // Let's try streaming download to temp file first.
        
        $tempFile = tempnam(sys_get_temp_dir(), 'pmtiles');
        $io->section("Downloading to temp file: $tempFile");

        $response = $this->httpClient->request('GET', $url, [
            'on_progress' => function (int $dlNow, int $dlSize, array $info) use ($io) {
                // Progress bar logic could go here
            },
        ]);

        if (200 !== $response->getStatusCode()) {
            $io->error('Failed to download file: ' . $response->getStatusCode());
            return Command::FAILURE;
        }

        $fileHandler = fopen($tempFile, 'w');
        foreach ($this->httpClient->stream($response) as $chunk) {
            fwrite($fileHandler, $chunk->getContent());
        }
        fclose($fileHandler);

        $io->success("Download complete. Size: " . (filesize($tempFile) / 1024 / 1024) . " MB");

        // 2. Upload to S3
        $s3Key = sprintf('maps/%s/%s.pmtiles', $region, date('Y-m-d'));
        $io->section("Uploading to S3: $s3Key");

        $stream = fopen($tempFile, 'r');
        try {
            $this->defaultStorage->writeStream($s3Key, $stream);
            fclose($stream);
            unlink($tempFile); // Cleanup
        } catch (\Exception $e) {
            $io->error("Upload failed: " . $e->getMessage());
            return Command::FAILURE;
        }

        $io->success("Upload complete.");

        // 3. Register in DB
        $mapSource = new MapSource();
        $mapSource->setRegion($region);
        $mapSource->setVersion(date('Y-m-d'));
        $mapSource->setS3Key($s3Key);
        $mapSource->setSourceUrl($url);
        $mapSource->setMinZoom((int) $input->getOption('min-zoom'));
        $mapSource->setMaxZoom((int) $input->getOption('max-zoom'));
        
        if ($bounds = $input->getOption('bounds')) {
            $mapSource->setBounds(json_decode($bounds, true));
        }

        $mapSource->setIsActive(true);

        $this->entityManager->persist($mapSource);
        $this->entityManager->flush();

        $io->success("Map Source registered in Database.");

        return Command::SUCCESS;
    }
}
