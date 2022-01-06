<?php
namespace WbpProductTabs\Core\Content\WbpProductTabs;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(WbpProductTabsEntity $entity)
 * @method void              set(string $key, WbpProductTabsEntity $entity)
 * @method WbpProductTabsEntity[]    getIterator()
 * @method WbpProductTabsEntity[]    getElements()
 * @method WbpProductTabsEntity|null get(string $key)
 * @method WbpProductTabsEntity|null first()
 * @method WbpProductTabsEntity|null last()
 */
class WbpProductTabsCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return WbpProductTabsEntity::class;
    }
}