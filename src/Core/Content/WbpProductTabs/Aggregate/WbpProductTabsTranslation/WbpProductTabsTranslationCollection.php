<?php
namespace Wbp\ProductTabs\Core\Content\WbpProductTabs\Aggregate\WbpProductTabsTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(WbpProductTabsTranslationEntity $entity)
 * @method void              set(string $key, WbpProductTabsTranslationEntity $entity)
 * @method WbpProductTabsTranslationEntity[]    getIterator()
 * @method WbpProductTabsTranslationEntity[]    getElements()
 * @method WbpProductTabsTranslationEntity|null get(string $key)
 * @method WbpProductTabsTranslationEntity|null first()
 * @method WbpProductTabsTranslationEntity|null last()
 */
class WbpProductTabsTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return WbpProductTabsTranslationEntity::class;
    }
}