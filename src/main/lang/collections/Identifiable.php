<?php

declare(strict_types=1);

namespace vinyl\std\lang\collections;

/**
 * Interface Identity
 *
 * Interface to mark objects that are identifiable by some id.
 */
interface Identifiable
{
    /**
     * Returns identity of object
     */
    public function identity(): string;
}
