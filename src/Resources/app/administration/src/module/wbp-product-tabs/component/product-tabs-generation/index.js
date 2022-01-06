import template from './product-tabs-modal-generation.html.twig';

const {Component, Mixin, Context} = Shopware;
const {EntityCollection, Criteria} = Shopware.Data;
const {mapState, mapGetters, mapPropertyErrors} = Component.getComponentHelper();

Component.register('wbp-product-tabs-modal-generation', {
    template,

    data: function () {
        return {
            tabs: {
                name : undefined,
                description: undefined
            }
        }
    },

    props: {
        productId: {
            type: String
        },
        item: {
            type: Object
        }
    },

    inject: [
        'repositoryFactory',
        'WbpProductTabsService'
    ],

    computed: {

        ...mapPropertyErrors('tabs', [
            'name',
            'description'
        ]),

        wbpProductTabsRepository() {
            return this.repositoryFactory.create('wbp_product_tabs');
        }
    },

    created() {
        this.editTab();
    },

    methods: {
        editTab() {
            if (this.item !== null) {
                this.tabs = new EntityCollection(
                    this.wbpProductTabsRepository,
                    Context.api,
                );
                this.wbpProductTabsRepository.get(this.item.id, Context.api).then((tabs) => {
                    this.tabs = tabs;
                }).catch((error) => {
                    console.log(error)
                });
            }
        },


        saveTabs() {
            this.validate();
            if (this.tabs.name !== undefined && this.tabs.description !== undefined) {
                this.wbpProductTabsRepository.save(this.tabs, Context.api).then((response) => {
                    this.$emit('product-tabs-save');
                }).catch((error) => {
                    console.log(error);
                });
            }
        },

        saveNewTab(){
            this.validate();

            if (this.tabs.name !== undefined && this.tabs.description !== undefined) {
                let path = window.location.href;
                let arr = path.split('/');
                this.tabs.productId = arr[7];
                this.WbpProductTabsService.setNewTab(this.tabs)
                    .then((result) => {
                        this.$emit('product-tabs-save');
                    })
                    .catch((error) => {
                        this.handleError(error);
                    });
            }
        },

        validate(){
            if (this.tabs.name === undefined) {
                document.getElementById('sw-field--tabs-name').style.border = "solid 1px red";
            } else {
                document.getElementById('sw-field--tabs-name').style.border = "solid 1px #d1d9e0";
            }

            if (this.tabs.description === undefined) {
                document.getElementsByClassName('sw-text-editor__content-editor')[0].style.border = "solid 1px red";
            } else {
                document.getElementsByClassName('sw-text-editor__content-editor')[0].style.border = "solid 1px #d1d9e0";
            }
        }
    }

});