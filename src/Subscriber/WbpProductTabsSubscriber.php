<?php

namespace Wbp\ProductTabs\Subscriber;

use Psr\Container\ContainerInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Struct\ArrayEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\Struct\ArrayStruct;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Storefront\Page\Product\ProductPageCriteriaEvent;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class WbpProductTabsSubscriber implements EventSubscriberInterface
{
    private $productTabsRepository;


    public function __construct(
        EntityRepositoryInterface $productTabsRepository
    )
    {
        $this->productTabsRepository = $productTabsRepository;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            ProductPageLoadedEvent::class => 'onProductPage',
        ];
    }

    /**
     * @param ProductPageLoadedEvent $event
     */
    public function onProductPage(ProductPageLoadedEvent $event)
    {
        $context = $event->getContext();
        $productId = $event->getPage()->getProduct()->id;


        //Todo: это для демо даты. Что бы на продукты с разными размерами тоже распростронялись настройки. Пока не ясно, как будет работать с продукцией клиента
        $parentProductId = $event->getPage()->getProduct()->getParentId();
        if (!is_null($parentProductId)) {
            $productId = $parentProductId;
        }

        $criteria = new Criteria();
        $criteria->addSorting(new FieldSorting('position', FieldSorting::ASCENDING));
        $criteria->addFilter(new EqualsFilter('productId', $productId));

        $tabs = $this->productTabsRepository->search($criteria, $context)->getElements();

        if (count($tabs) > 0) {
            $newTab = [];
            foreach ($tabs as $tab) {
                if ($tab->position == 2) {
                    $event->getPage()->addExtension('reviews', new ArrayStruct([
                        'visibility' => $tab->isEnabled]));
                } elseif ($tab->position == 1) {
                    $event->getPage()->addExtension('description', new ArrayStruct([
                        'visibility' => $tab->isEnabled]));
                } else {
                    if ($tab->isEnabled == 1) {
                        $arr['name'] = $tab->name;
                        $arr['alias'] = 'id' . str_replace(' ', '', $tab->name);
                        $arr['description'] = $tab->description;
                        $arr['id'] = $tab->id;
                        $newTab[] = $arr;
                    }
                }


            }
            if (count($newTab) > 0) {
                $event->getPage()->addExtension('new_tabs', new ArrayStruct([
                    'data' => $newTab
                ]));
            }
        } else {
            //default visibility
            $event->getPage()->addExtensions([
                'reviews' => new ArrayStruct(['visibility' => 1]),
                'description' => new ArrayStruct(['visibility' => 1]),
            ]);
        }
    }
}