<?php

namespace App\Service;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Uid\Uuid;

class SnapshotService
{
    public const FILE_EXTENSION = '.jpg';
    public const MIME_TYPE = 'image/jpeg';

    private const MAX_ATTEMPTS = 3;

    public function __construct(
        #[Target('snapshots.storage')]
        private FilesystemOperator $snapshotsStorage,
    ) {}

    /**
     * @param resource $stream
     */
    public function save($stream): ?string
    {
        for ($attemptIndex = 0; $attemptIndex < self::MAX_ATTEMPTS; ++$attemptIndex) {
            try {
                $uuid = Uuid::v4()->toRfc4122();

                $path = self::createPathFromUuid($uuid);
                $this->snapshotsStorage->writeStream($path, $stream);

                return $uuid;
            } catch (\Exception) {
            }
        }

        return null;
    }

    /**
     * @return ?resource
     */
    public function serve(string $uuid)
    {
        for ($attemptIndex = 0; $attemptIndex < self::MAX_ATTEMPTS; ++$attemptIndex) {
            try {
                $path = self::createPathFromUuid($uuid);
                $stream = $this->snapshotsStorage->readStream($path);

                return $stream;
            } catch (\Exception) {
            }
        }

        return null;
    }

    public function size(string $uuid): ?int
    {
        for ($attemptIndex = 0; $attemptIndex < self::MAX_ATTEMPTS; ++$attemptIndex) {
            try {
                $path = self::createPathFromUuid($uuid);
                $size = $this->snapshotsStorage->fileSize($path);

                return $size;
            } catch (\Exception) {
            }
        }

        return null;
    }

    private static function createPathFromUuid(string $uuid): string
    {
        $directory = substr($uuid, offset: 0, length: 2);
        $fileName = $uuid.self::FILE_EXTENSION;

        return $directory.DIRECTORY_SEPARATOR.$fileName;
    }
}
