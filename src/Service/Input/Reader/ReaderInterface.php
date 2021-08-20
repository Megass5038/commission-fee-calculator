<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\Input\Reader;

use Generator;

/**
 * Provides method to read items from source (file, HTTP stream, websocket, etc...)
 */
interface ReaderInterface
{
    /**
     * @return Generator<array>
     */
    public function items(): Generator;
}
