<?php

namespace Wbp\ProductTabs\Controller\Api;


use Doctrine\DBAL\Connection;
use mysql_xdevapi\Exception;
use Psr\Container\ContainerInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Routing\ApiRouteScope;
use Shopware\Core\Framework\Routing\RouteScopeRegistry;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Storefront\Framework\Cache\Annotation\HttpCache;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wbp\ProductTabs\Resources\helper\TranslationHelper;
use function React\Promise\resolve;

/**
 * @RouteScope(scopes={"api"})
 */
class WbpProductTabsApiController extends AbstractController
{
    protected $productTabsRepository;
    protected $container;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var TranslationHelper
     */
    protected $translationHelper;

    public function __construct(
        ContainerInterface $container,
        EntityRepositoryInterface $productTabsRepository,
        Connection $connection
    )
    {
        $this->connection = $connection;
        $this->productTabsRepository = $productTabsRepository;
        $this->translationHelper = new TranslationHelper($connection);
        $this->container = $container;
    }

    /**
     * @Route("/api/wbp-product-tabs/set-default-tabs", name="api.action.wbpproducttabs.setdefaulttabs", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
//    public function setDefaultTabs(Request $request, Context $context): JsonResponse
//    {
//
//        $params = $request->request->all();
//
//        if (!isset($params['productId'])) {
//            return new JsonResponse([
//                'state' => 'failed',
//                'error' => 'Configuration invalid'
//            ]);
//        }
//
//        $id = Uuid::randomHex();
//        $this->productTabsRepository->create([
//            [
//                'id' => $id,
//                'productId' => $params['productId'],
//                'position' => 1,
//                'name' => $this->translationHelper->adjustTranslations([
//                    'de-DE' => 'Description DE',
//                    'en-GB' => 'Description EN']),
//                'description' => $this->translationHelper->adjustTranslations([
//                    'de-DE' => '',
//                    'en-GB' => '',
//                ]),
//                'isEnabled' => 1,
//                'createdAt' => date('Y-m-d H:i:s'),
//                'updatedAt' => null,
//            ]
//        ], $context);
//
//        $id = Uuid::randomHex();
//        $this->productTabsRepository->create([
//            [
//                'id' => $id,
//                'productId' => $params['productId'],
//                'position' => 2,
//                'name' => $this->translationHelper->adjustTranslations([
//                    'de-DE' => 'Reviews DE',
//                    'en-GB' => 'Reviews EN'
//                ]),
//                'description' => $this->translationHelper->adjustTranslations([
//                    'de-DE' => '',
//                    'en-GB' => '',
//                ]),
//                'isEnabled' => 1,
//                'createdAt' => date('Y-m-d H:i:s'),
//                'updatedAt' => null,
//            ]
//        ], $context);
//
//
//        return new JsonResponse([
//            'success' => 'ok'
//        ]);
//    }


    /**
     * @Route("/api/wbp-product-tabs/change-visibility", name="api.action.wbpproducttabs.changevisibility", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changeVisibility(Request $request, Context $context): JsonResponse
    {
        $params = $request->request->all();

        if (!isset($params['tabsId'])) {
            return new JsonResponse([
                'state' => 'failed',
                'error' => 'Configuration invalid'
            ]);
        }

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $params['tabsId']));
        $tabsId = $this->productTabsRepository->searchIds($criteria, $context)->firstId();
        $tab = $this->productTabsRepository->search($criteria, $context)->first();

        if ($tab->isEnabled == 0) {
            $this->productTabsRepository->update([
                [
                    'id' => $tabsId,
                    'isEnabled' => 1
                ]
            ], $context);
        } else {
            $this->productTabsRepository->update([
                [
                    'id' => $tabsId,
                    'isEnabled' => 0
                ]
            ], $context);
        }


        return new JsonResponse([
            'success' => 'Visibility changed'
        ]);
    }

    /**
     * @Route("/api/wbp-product-tabs/set-new-tab", name="api.action.wbpproducttabs.setnewtab", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
//    public function setNewTab(Request $request, Context $context): JsonResponse
//    {
//        $params = $request->request->all();
//
//        if (is_null($params['newTab']['name']) || is_null($params['newTab']['productId'])) {
//            return new JsonResponse([
//                'state' => 'failed',
//                'error' => 'Configuration invalid'
//            ]);
//        }
//
//        $criteria = new Criteria();
//        $criteria->addFilter(new EqualsFilter('productId', $params['newTab']['productId']));
//        $criteria->addSorting(new FieldSorting('position', FieldSorting::ASCENDING));
//        $element = $this->productTabsRepository->search($criteria, $context)->last();
//
//        $id = Uuid::randomHex();
//
//        if($params['newTab']['show'] == 'description') {
//            $this->productTabsRepository->create([
//                [
//                    'id' => $id,
//                    'productId' => $params['newTab']['productId'],
//                    'position' => ++$element->position,
//                    'show' => $params['newTab']['show'],
//                    'name' => $this->translationHelper->adjustTranslations([
//                        'de-DE' => $params['newTab']['name'],
//                        'en-GB' => $params['newTab']['name']
//                    ]),
//                    'description' => $this->translationHelper->adjustTranslations([
//                        'de-DE' => $params['newTab']['description'],
//                        'en-GB' => $params['newTab']['description'],
//                    ]),
//                    'isEnabled' => 1,
//                    'createdAt' => date('Y-m-d H:i:s'),
//                    'updatedAt' => null,
//                ]
//            ], $context);
//        }
//
//        if($params['newTab']['show'] == 'products') {
//
//            $productIsd = implode('&&',$params['newTab']['selectProductsIds']);
//            $this->productTabsRepository->create([
//                [
//                    'id' => $id,
//                    'productId' => $params['newTab']['productId'],
//                    'position' => ++$element->position,
//                    'show' => $params['newTab']['show'],
//                    'productString' => $productIsd,
//                    'isEnabled' => 1,
//                    'createdAt' => date('Y-m-d H:i:s'),
//                    'updatedAt' => null,
//                    'name' => $this->translationHelper->adjustTranslations([
//                        'de-DE' => $params['newTab']['name'],
//                        'en-GB' => $params['newTab']['name']
//                    ]),
//                ]
//            ], $context);
//        }
//
//        return new JsonResponse([
//            'success' => $params
//        ]);
//    }

    /**
     * @Route("/api/wbp-product-tabs/remove-tab", name="api.action.wbpproducttabs.removetab", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function removeTab(Request $request, Context $context): JsonResponse
    {
        $params = $request->request->all();

        if (is_null($params['id'])) {
            return new JsonResponse([
                'state' => 'failed',
                'error' => 'Configuration invalid'
            ]);
        }

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $params['id']));
        $productId = $this->productTabsRepository->search($criteria, $context)->first()->productId;

        $this->productTabsRepository->delete([
            [
                'id' => $params['id']
            ]
        ], $context);

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('productId', $productId));
        $criteria->addSorting(new FieldSorting('position', FieldSorting::ASCENDING));
        $elements = $this->productTabsRepository->search($criteria, $context)->getElements();

        $i = 0;
        foreach ($elements as $element) {
            $i++;
            $this->productTabsRepository->update([
                [
                    'id' => $element->id,
                    'position' => $i
                ]
            ], $context);
        }


        return new JsonResponse([
            'success' => 'Ok'
        ]);
    }

    /**
     * @Route("/api/wbp-product-tabs/edit-tab", name="api.action.wbpproducttabs.edittab", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
//    public function editTab(Request $request, Context $context): JsonResponse
//    {
//        $params = $request->request->all();
//
//        if (!isset($params['tab']['id'])) {
//            return new JsonResponse([
//                'state' => 'failed',
//                'error' => 'Configuration invalid'
//            ]);
//        }
//
//        $name = $params['tab']['tabsName'];
//        $description = $params['tab']['data'];
//        $show = $params['tab']['show'];
//        $id = $params['tab']['id'];
//        $langId = strtoupper($params['tab']['langId']);
//
//        $criteria = new Criteria();
//        $criteria->addFilter(new EqualsFilter('id', $id));
//        $id = $this->productTabsRepository->searchIds($criteria, $context)->firstId();
//
//
//        if($show == 'description') {
//            $this->productTabsRepository->update([
//                [
//                    'id' => $id,
//                    'name' => $name,
//                    'show' => $show,
//                    'description' => $description
//                ]
//            ], $context);
//        }
//        if($show == 'products') {
//            $productIds = implode('&&',$params['newTab']['selectProductsIds']);
//            $this->productTabsRepository->update([
//                [
//                    'id' => $id,
//                    'name' => $name,
//                    'show' => $show,
//                    'productString' => $productIds
//                ]
//            ], $context);
//        }
//
//        return new JsonResponse([
//            'success' => ''
//        ]);
//    }

    /**
     * @Route("/api/wbp-product-tabs/position-down", name="api.action.wbpproducttabs.positiondown", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function positionDown(Request $request, Context $context): JsonResponse
    {
        $params = $request->request->all();

        if (is_null($params['id'])) {
            return new JsonResponse([
                'state' => 'failed',
                'error' => 'Configuration invalid'
            ]);
        }


        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $params['id']));
        $tab = $this->productTabsRepository->search($criteria, $context)->first();
        $oldPosition = $tab->position - 1;

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('productId', $params['productId']));
        $criteria->addFilter(new EqualsFilter('position', $oldPosition));
        $changesTab = $this->productTabsRepository->search($criteria, $context)->first();

        $this->productTabsRepository->update([
            [
                'id' => $changesTab->id,
                'position' => $tab->position
            ]
        ], $context);


        $this->productTabsRepository->update([
            [
                'id' => $tab->id,
                'position' => $changesTab->position
            ]
        ], $context);


        return new JsonResponse([
            'success' => 'Visibility changed'
        ]);
    }

    /**
     * @Route("/api/wbp-product-tabs/position-up", name="api.action.wbpproducttabs.positionup", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function positionUp(Request $request, Context $context): JsonResponse
    {
        $params = $request->request->all();

        if (is_null($params['id'])) {
            return new JsonResponse([
                'state' => 'failed',
                'error' => 'Configuration invalid'
            ]);
        }


        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $params['id']));
        $tab = $this->productTabsRepository->search($criteria, $context)->first();
        $oldPosition = $tab->position + 1;

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('productId', $params['productId']));
        $criteria->addFilter(new EqualsFilter('position', $oldPosition));
        $changesTab = $this->productTabsRepository->search($criteria, $context)->first();

        $this->productTabsRepository->update([
            [
                'id' => $changesTab->id,
                'position' => $tab->position
            ]
        ], $context);


        $this->productTabsRepository->update([
            [
                'id' => $tab->id,
                'position' => $changesTab->position
            ]
        ], $context);


        return new JsonResponse([
            'success' => 'Visibility changed'
        ]);
    }
}