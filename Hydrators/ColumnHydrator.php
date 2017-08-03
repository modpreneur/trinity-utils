<?php

namespace Trinity\Component\Utils\Hydrators;

use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
use PDO;

/**
 * Hydrate into simple array without column name
 */
class ColumnHydrator extends AbstractHydrator
{
    /**
     * {@inheritDoc}
     */
    protected function hydrateAllData(): array
    {
        return $this->_stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
