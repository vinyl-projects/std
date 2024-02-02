<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

/**
 * Interface Set
 *
 * Collection that doesn't support duplicate elements.
 *
 * @template-covariant TValue of string|object|bool|int|null
 * @extends Collection<int, TValue>
 */
interface Set extends Collection
{
}
