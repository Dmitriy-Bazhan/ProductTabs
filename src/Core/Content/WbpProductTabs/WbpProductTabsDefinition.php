<?php
namespace WbpProductTabs\Core\Content\WbpProductTabs;

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use WbpProductTabs\Core\Content\WbpProductTabs\Aggregate\WbpProductTabsTranslation\WbpProductTabsTranslationDefinition;

class WbpProductTabsDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'wbp_product_tabs';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return WbpProductTabsCollection::class;
    }

    public function getEntityClass(): string
    {
        return WbpProductTabsEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new ApiAware(), new PrimaryKey(), new Required()),
            (new IdField('product_id', 'productId'))->addFlags(new ApiAware(), new Required()),
            (new IntField('position', 'position'))->addFlags(new ApiAware(), new Required()),
            (new IntField('is_enabled', 'isEnabled'))->addFlags(new ApiAware(), new Required()),
            (new TranslatedField('name'))->addFlags(new ApiAware(), new Required()),
            (new TranslatedField('description'))->addFlags(new ApiAware(), new Required()),
            (new TranslationsAssociationField(
                WbpProductTabsTranslationDefinition::class,
                'wbp_product_tabs_id'
            ))->addFlags(new ApiAware(), new Required()),
            (new CreatedAtField()),
            (new UpdatedAtField()),
        ]);


    }
}