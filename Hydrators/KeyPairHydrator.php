<?php

namespace Trinity\Component\Utils\Hydrators;

use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
use PDO;

/**
 * Hydrate into array in format key(first parameter) => value(second parameter)
 */
class KeyPairHydrator extends AbstractHydrator
{
    /**
     * {@inheritDoc}
     */
    protected function hydrateAllData(): array
    {
        return $this->_stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}
