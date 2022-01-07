<?php declare(strict_types=1);

namespace Wbp\ProductTabs;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

class WbpProductTabs extends Plugin
{
    /**
     * @param UninstallContext $context
     */
    public function uninstall(UninstallContext $context): void
    {
        parent::uninstall($context);

        if ($context->keepUserData()) {
            return;
        }

        $connection = $this->container->get(Connection::class);

        $connection->executeStatement('DROP TABLE IF EXISTS `wbp_product_tabs_translation`');
        $connection->executeStatement('DROP TABLE IF EXISTS `wbp_product_tabs`');
    }
}