import template from './product-tabs-modal-generation.html.twig';

const {Component, Mixin, Context} = Shopware;
const {EntityCollection, Criteria, Entity} = Shopware.Data;
const {mapState, mapGetters, mapPropertyErrors} = Component.getComponentHelper();

Component.register('wbp-product-tabs-modal-generation', {
    template,

    data: function () {
        return {
            tabs: {},
            isLoading: false,
            checkboxName: 'Description',
            checkbox: false,
            errors: [],
            selectProducts: [
                {'id': 1, name: 'product1'},
                {'id': 2, name: 'product2'},
            ]
        }
    },

    props: {
        productId: {
            type: String
        },
        item: {
            type: Object
        },
        lastPosition : {
            type: Number
        }
    },

    inject: [
        'repositoryFactory',
        'WbpProductTabsService'
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

        ...mapPropertyErrors('tabs', [
            'name',
            'description'
        ]),

        ...mapState('context', {
            contextLanguageId: state => state.api.languageId,
        }),

        wbpProductTabsRepository() {
            return this.repositoryFactory.create('wbp_product_tabs');
        },

        productRepository() {
            return this.repositoryFactory.create('product');
        },

        productCriteria() {
            const productCriteria = new Criteria(this.page, this.limit);

            productCriteria.setTerm(this.term);
            productCriteria.addFilter(Criteria.equals('product.parentId', null));
            // productCriteria.addSorting(Criteria.sort(this.sortBy, this.sortDirection, this.naturalSorting));
            // productCriteria.addAssociation('cover');
            // productCriteria.addAssociation('manufacturer');
            // productCriteria.addAssociation('media');

            // this.filterCriteria.forEach(filter => {
            //     productCriteria.addFilter(filter);
            // });

            return productCriteria;
        },


    },

    created() {
        this.tabs = this.wbpProductTabsRepository.create(Shopware.Context.api);
        this.tabs.position = ++this.lastPosition;
        this.tabs.isEnabled = 1;
        this.editTab();
        this.getProducts();
    },

    methods: {
        async getProducts() {
            try {
                const result = await Promise.all([
                    this.productRepository.search(this.productCriteria),
                ]);
                this.selectProducts = result[0];
                this.isLoading = false;

            } catch {
                this.isLoading = true;
            }
        },

        editTab() {
            if (this.item !== null) {
                this.wbpProductTabsRepository.get(this.item.id, Context.api).then((tabs) => {
                    this.tabs = tabs;
                    if (this.tabs.productString !== null) {
                        this.tabs.selectProductsIds = this.tabs.productString.split('&&');
                    }
                    if (this.tabs.show === 'products') {
                        this.checkbox = true;
                    }
                }).catch((error) => {
                    console.log(error)
                });
            }
        },

        saveTabs() {
            this.validate();
            this.changeShowInTabs(this.checkbox);
            if (this.tabs.name !== undefined) {
                if (this.tabs.show === 'products') {
                    if (this.tabs.selectProductsIds.length > 0) {
                        this.tabs.productString = this.tabs.selectProductsIds.join('&&');
                    } else {
                        this.tabs.productString = '';
                    }

                }

                this.wbpProductTabsRepository.save(this.tabs, Context.api).then((response) => {
                    this.$emit('product-tabs-save');
                }).catch((error) => {
                    console.log(error);
                });
            }
        },

        saveNewTab() {
            this.validate();
            this.changeShowInTabs(this.checkbox);
            if (this.tabs.name !== undefined) {
                let path = window.location.href;
                let arr = path.split('/');
                this.tabs.productId = arr[7];

                if (this.tabs.show === 'products') {
                    if (this.tabs.selectProductsIds.length > 0) {
                        this.tabs.productString = this.tabs.selectProductsIds.join('&&');
                    } else {
                        this.tabs.productString = '';
                    }
                }

                this.wbpProductTabsRepository.save(this.tabs, Context.api).then((response) => {
                    this.$emit('product-tabs-save');
                }).catch((error) => {
                    this.errors.push('Please, first use your default language');
                    console.log(error);
                });

                // this.WbpProductTabsService.setNewTab(this.tabs)
                //     .then((result) => {
                //         this.$emit('product-tabs-save');
                //     })
                //     .catch((error) => {
                //         this.handleError(error);
                //     });
            }
        },

        changeShowInTabs(checkbox) {
            if (checkbox) {
                this.tabs.show = 'products';
            } else {
                this.tabs.show = 'description';
            }
        },

        validate() {
            if (this.tabs.name === undefined) {
                document.getElementById('sw-field--tabs-name').style.border = "solid 1px red";
            } else {
                document.getElementById('sw-field--tabs-name').style.border = "solid 1px #d1d9e0";
            }
        }
    }

});