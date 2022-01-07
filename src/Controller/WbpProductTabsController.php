<?php

namespace Wbp\ProductTabs\Controller;


use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Response;
use Shopware\Storefront\Framework\Cache\Annotation\HttpCache;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"storefront"})
 */
class WbpProductTabsController extends StorefrontController
{
    private $productTabsRepository;

    public function __construct(
        EntityRepositoryInterface $productTabsRepository
    )
    {
        $this->productTabsRepository = $productTabsRepository;
    }

    /**
     * @HttpCache()
     * @Route("/test", name="wbp.product.tabs.test", methods={"GET"})
     */
    public function test(Context $context): Response
    {
        $criteria = new Criteria();
        $productTabs = $this->productTabsRepository->search($criteria, $context)->getElements();
        dump(reset($productTabs));
        die();
    }


}