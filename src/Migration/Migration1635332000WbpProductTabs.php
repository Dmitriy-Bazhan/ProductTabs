<?php declare(strict_types=1);

namespace Wbp\ProductTabs\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1635332000WbpProductTabs extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1635332000;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement("
            CREATE TABLE IF NOT EXISTS `wbp_product_tabs` (
                `id` BINARY (16) NOT NULL,
                `product_id` BINARY (16) NOT NULL,
                `position` tinyint(1),
                `is_enabled` tinyint(1) DEFAULT 1,
                `show` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'description',
                `product_string` TEXT DEFAULT NULL,
                `created_at` datetime(3) NOT NULL,
                `updated_at` datetime(3) DEFAULT NULL,
                PRIMARY KEY ( `id` )
            ) ENGINE = INNODB DEFAULT CHARSET = utf8;
        ");
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
