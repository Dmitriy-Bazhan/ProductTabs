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
    protected $show;
    protected $productString;

    public function getProductString()
    {
        return $this->productString;
    }

    public function setProductString(string $productString)
    {
        $this->productString = $productString;
    }

    public function getShow()
    {
        return $this->show;
    }

    public function setShow(string $show)
    {
        $this->show = $show;
    }

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