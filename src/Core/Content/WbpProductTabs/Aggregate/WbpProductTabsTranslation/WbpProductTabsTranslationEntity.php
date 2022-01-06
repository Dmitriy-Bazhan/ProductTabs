<?php

namespace WbpProductTabs\Core\Content\WbpProductTabs\Aggregate\WbpProductTabsTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;
use WbpProductTabs\Core\Content\WbpProductTabs\WbpProductTabsEntity;

class WbpProductTabsTranslationEntity extends TranslationEntity
{
    protected $name;
    protected $description;

    /**
     * @var WbpProductTabsEntity
     */
    protected $productTabs;


    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        return $this->description = $description;
    }

    public function getProductTabs(): WbpProductTabsEntity
    {
        return $this->productTabs;
    }
    public function setProductTabs(WbpProductTabsEntity $productTabs): void
    {
        $this->productTabs = $productTabs;
    }
}