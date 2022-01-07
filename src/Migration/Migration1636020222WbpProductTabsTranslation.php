<?php declare(strict_types=1);

namespace Wbp\ProductTabs\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1636020222WbpProductTabsTranslation extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1636020222;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement("
            CREATE TABLE IF NOT EXISTS `wbp_product_tabs_translation` (
                `wbp_product_tabs_id` BINARY (16) NOT NULL,
                `language_id` BINARY (16) NOT NULL,
                `name` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `description` TEXT DEFAULT NULL,
                `created_at` datetime(3) NOT NULL,
                `updated_at` datetime(3) DEFAULT NULL,
                PRIMARY KEY (`wbp_product_tabs_id`, `language_id`),
                CONSTRAINT `fk.wbp_product_tabs_translation.wbp_product_tabs_id` FOREIGN KEY (`wbp_product_tabs_id`)
                    REFERENCES `wbp_product_tabs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.wbp_product_tabs_id.language_id` FOREIGN KEY (`language_id`)
                    REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                
            ) ENGINE = INNODB DEFAULT CHARSET = utf8;
        ");
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
