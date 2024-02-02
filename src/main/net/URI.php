<?php

declare(strict_types=1);

namespace vinyl\std\net;

use Stringable;
use function count;
use function implode;
use function parse_url;

/**
 * Represents a Uniform Resource Identifier (URI).
 *
 * This class provides methods to parse a URI string and retrieve its different components,
 * such as scheme, user, host, port, path, query, and fragment. It also allows you to create
 * a URI instance from a string and convert the URI object back to a string.
 */
final readonly class URI implements Stringable
{
    private function __construct(
        private ?string $scheme,
        private ?string $user,
        private ?string $host,
        private ?int $port,
        private ?string $path,
        private ?string $query,
        private ?string $fragment,
    ) {

    }

    public static function create(string $uriInput): self
    {
        $parsedUri = parse_url($uriInput);

        if ($parsedUri === false) {
            //TODO extract to separate exception
            throw new \RuntimeException('Malformed URI provided: ' . $uriInput);
        }

        return new self(
            $parsedUri['scheme'] ?? null,
            $parsedUri['user'] ?? null,
            $parsedUri['host'] ?? null,
            $parsedUri['port'] ?? null,
            $parsedUri['path'] ?? null,
            $parsedUri['query'] ?? null,
            $parsedUri['fragment'] ?? null
        );
    }

    /**
     * Retrieves the scheme of the URL.
     *
     * @return ?string The scheme of the URL, if available; otherwise null.
     */
    public function scheme(): ?string
    {
        return $this->scheme;
    }

    /**
     * Retrieves the host.
     *
     * @return ?string The host, if available; otherwise null.
     */
    public function host(): ?string
    {
        return $this->host;
    }

    /**
     * Retrieves the port number.
     *
     * @return ?int The port number, if available; otherwise null.
     */
    public function port(): ?int
    {
        return $this->port;
    }

    /**
     * Retrieves the path string.
     *
     * @return ?string The path string, if available; otherwise null.
     */
    public function path(): ?string
    {
        return $this->path;
    }

    /**
     * Retrieves the query string.
     *
     * @return ?string The query string, if available; otherwise null.
     */
    public function query(): ?string
    {
        return $this->query;
    }

    /**
     * Retrieves the fragment value.
     *
     * @return string|null The fragment value, or null if it is not set.
     */
    public function fragment(): ?string
    {
        return $this->fragment;
    }

    /**
     * Retrieves the user value.
     *
     * @return string|null The user value, or null if it is not set.
     */
    public function user(): ?string
    {
        return $this->user;
    }

    /**
     * Retrieves the authority part of the URI.
     *
     * This method constructs the authority part of the URL based on the user,
     * host, and port properties. If user is set, it appends the user and "@" to
     * the authority. Then, it appends the host. If port is set, it appends ":" and
     * the port to the authority. If no host is set, the method returns null.
     *
     * @return string|null The authority part of the URI, or null if no host is set.
     */
    public function authority(): ?string
    {
        $parts = [];

        if ($this->host !== null) {
            if ($this->user !== null) {
                $parts[] = $this->user;
                $parts[] = '@';
            }
            $parts[] = $this->host;
            if ($this->port !== null) {
                $parts[] = ':';
                $parts[] = $this->port;
            }
        }

        if (count($parts) === 0) {
            return null;
        }

        return implode('', $parts);
    }


    public function __toString(): string
    {
        // Reconstruct the URI string from its components
        $uriString = '';
        if ($this->scheme !== null) {
            $uriString .= $this->scheme . ':';
        }

        if ($this->host !== null) {
            $user = '';
            if ($this->user !== null) {
                $user = $this->user . '@';
            }
            $uriString .= '//' . $user . $this->host;
            if ($this->port !== null) {
                $uriString .= ':' . $this->port;
            }
        }
        if ($this->path !== null) {
            $uriString .= $this->path;
        }
        if ($this->query !== null) {
            $uriString .= '?' . $this->query;
        }
        if ($this->fragment !== null) {
            $uriString .= '#' . $this->fragment;
        }

        return $uriString;
    }
}
