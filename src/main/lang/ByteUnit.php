<?php

declare(strict_types=1);

namespace vinyl\std\lang;

use InvalidArgumentException;

final readonly class ByteUnit
{
    /**
     * Constructor for the class.
     *
     * @param int $bytes The number of bytes.
     *
     * @throws \InvalidArgumentException If the number of bytes is negative.
     */
    private function __construct(private int $bytes)
    {
        if ($this->bytes < 0) {
            throw new InvalidArgumentException('Bytes could not be negative value. Given value ' . "[$this->bytes]");
        }
    }

    /**
     * Create a new instance of ByteUnit with the specified number of bytes.
     *
     * @param int $bytes The number of bytes for the ByteUnit.
     *
     * @return ByteUnit A new instance of ByteUnit with the specified number of bytes.
     */
    public static function create(int $bytes): ByteUnit
    {
        return new self($bytes);
    }

    /**
     * Get the number of bytes represented by the ByteUnit object.
     *
     * @return int The number of bytes represented by the ByteUnit object.
     */
    public function toBytes(): int
    {
        return $this->bytes;
    }

    /**
     * Convert the number of bytes to kilobytes.
     *
     * @return float The number of kilobytes.
     */
    public function toKilobytes(): float
    {
        return $this->bytes / 1024;
    }

    /**
     * Convert the current ByteUnit instance to megabytes.
     *
     * @return float The value of the current ByteUnit instance converted to megabytes.
     */
    public function toMegabytes(): float
    {
        return $this->toKilobytes() / 1024;
    }
}
