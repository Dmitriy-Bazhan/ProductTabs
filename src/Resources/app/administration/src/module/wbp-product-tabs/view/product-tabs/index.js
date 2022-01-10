import template from './product-detail-tabs.html.twig';
import './product-detail-tabs.scss';

const {Component, Mixin, Context} = Shopware;
const {Criteria} = Shopware.Data;
const {mapState, mapGetters} = Shopware.Component.getComponentHelper();

Component.register('product-tabs', {
    template,

    metaInfo() {
        return {
            title: 'Tabs'
        };
    },

    inject: [
        'repositoryFactory',
        'WbpProductTabsService'
    ],

    data: function () {
        return {
            dataSource: [],
            lastPosition : '',
            columns: [
                {
                    property: 'position',
                    label: this.$tc('wbp-product-tabs.general.position'),
                    width: '125px',
                    align: 'left',
                },
                {
                    property: 'name',
                    label: this.$tc('wbp-product-tabs.general.tabsName'),
                    allowResize: true,
                    width: '200px',
                    align: 'right',
                },
                {
                    property: 'show',
                    label: this.$tc('wbp-product-tabs.general.show'),
                    width: '200px',
                    align: 'left',
                },
                {
                    property: 'isEnabled',
                    label: this.$tc('wbp-product-tabs.general.visibility'),
                    width: '125px',
                    align: 'right',
                },
            ],
            activeModal: false,
            showDeleteModal: '',
            editItem: null,
            tab : {},
            languageId : '',
            errors: [],
        };
    },

    mixins: [
        Mixin.getByName('listing')
    ],

    watch: {
        contextLanguageId : {
            handler() {
                this.getList();
            },
            deep: true,
        },
    },

    computed: {
        ...mapState('swProductDetail', [
            'product'
        ]),

        ...mapState('context', {
            contextLanguageId: state => state.api.languageId,
        }),

        wbpProductTabsRepository() {
            return this.repositoryFactory.create('wbp_product_tabs');
        },

        itemsCriteria() {
            if (this.product.id === undefined) {
                let path = window.location.href;
                let arr = path.split('/');
                this.product.id = arr[7];
            }

            const criteria = new Criteria();
            const params = this.getMainListingParams();
            params.sortBy = params.sortBy || 'position';
            params.sortDirection = params.sortDirection || 'ASC';

            criteria.addSorting(Criteria.sort(params.sortBy, params.sortDirection));
            criteria.addFilter(Criteria.equals('productId', this.product.id));

            return criteria;
        }
    },

    methods: {
        getList() {
            this.languageId = this.contextLanguageId;
            this.wbpProductTabsRepository.search(this.itemsCriteria, Context.api).then(items => {
                if (items.length < 1) {
                    this.setDefaultTabs();
                }
                this.dataSource = items;
            });
        },

        onChangeLanguage() {
            this.getList();
        },

        addNewTab() {
            this.lastPosition = this.dataSource[this.dataSource.length - 1].position;
            this.activeModal = true;
        },

        productTabsSave() {
            this.editItem = null;
            this.activeModal = false;
            this.getList();
        },

        onEdit(item) {
            if (item.position <= 2) {
                return;
            }
            this.editItem = item;
            this.activeModal = true;
        },

        onConfirmDelete(item) {
            this.WbpProductTabsService.removeTab(item.id)
                .then((result) => {
                    this.showDeleteModal = null;
                    this.getList();
                })
                .catch((error) => {
                    this.handleError(error);
                });
        },

        onCloseDeleteModal() {
            this.showDeleteModal = null;
        },

        CloseActiveModal(){
            this.editItem = null;
            this.activeModal = false;
        },

        changeVisibility(item) {
            this.WbpProductTabsService.changeVisibility(item.id)
                .then((result) => {
                    this.getList();
                })
                .catch((error) => {
                    this.handleError(error);
                });
        },

        setDefaultTabs() {
            this.errors = [];
            let path = window.location.href;
            let arr = path.split('/');

            this.tabs = this.wbpProductTabsRepository.create(Shopware.Context.api);
            this.tabs.productId = arr[7];
            this.tabs.name = 'Description';
            this.tabs.position = 1;
            this.tabs.isEnabled = 1;

            this.wbpProductTabsRepository.save(this.tabs, Context.api).then((response) => {
               //
            }).catch((error) => {
                this.errors.push('Please, first use your default language');
                // console.log(error);
            });

            this.tabs = this.wbpProductTabsRepository.create(Shopware.Context.api);
            this.tabs.productId = arr[7];
            this.tabs.name = 'Reviews';
            this.tabs.position = 2;
            this.tabs.isEnabled = 1;

            this.wbpProductTabsRepository.save(this.tabs, Context.api).then((response) => {
                this.getList();
            }).catch((error) => {
                console.log(error);
            });
        },

        positionUp(item){
            this.WbpProductTabsService.positionUp(item.id, item.productId)
                .then((result) => {
                    this.getList();
                })
                .catch((error) => {
                    this.handleError(error);
                });
        },

        positionDown(item){
            this.WbpProductTabsService.positionDown(item.id, item.productId)
                .then((result) => {
                    this.getList();
                })
                .catch((error) => {
                    this.handleError(error);
                });
        }
    }

});