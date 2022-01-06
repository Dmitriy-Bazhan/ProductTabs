<?php

namespace Wbp\ProductTabs\Core\Content\WbpProductTabs;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class WbpProductTabsEntity extends Entity
{
    use EntityIdTrait;

    protected $productId;
    protected $position;
    protected $isEnabled;

    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(int $isEnabled)
    {
        $this->isEnabled = $isEnabled;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }
}