//import ApiService from 'src/core/service/api.service'; from old version Shopware 6
const {ApiService} = Shopware.Classes;
/**
 * @private
 */
export default class WbpProductTabsService extends ApiService {
    constructor(httpClient, loginService, apiEndpoint = 'wbp-product-tabs') {
        super(httpClient, loginService, apiEndpoint);
        this.name = 'WbpProductTabsService';
        this.httpClient = httpClient;
    }

    async setDefaultTabs(productId) {
        let setDefaultTabsResponse = await this.httpClient.post('/wbp-product-tabs/set-default-tabs', {productId: productId}, {
            headers: this.getBasicHeaders()
        });
        return setDefaultTabsResponse;
    }

    async changeVisibility(tabsId) {
        let changeVisibilityResponse = await this.httpClient.post('/wbp-product-tabs/change-visibility', {tabsId: tabsId}, {
            headers: this.getBasicHeaders()
        });

        return changeVisibilityResponse;
    }

    async setNewTab(tab) {

        let response = await this.httpClient.post('/wbp-product-tabs/set-new-tab', {newTab: tab}, {
            headers: this.getBasicHeaders()
        });

        return response;
    }

    async removeTab(id) {

        let response = await this.httpClient.post('/wbp-product-tabs/remove-tab', {id: id}, {
            headers: this.getBasicHeaders()
        });

        return response;
    }

    async positionUp(id, productId) {

        let response = await this.httpClient.post('/wbp-product-tabs/position-up', {id: id, productId: productId}, {
            headers: this.getBasicHeaders()
        });

        return response;
    }

    async positionDown(id, productId) {

        let response = await this.httpClient.post('/wbp-product-tabs/position-down', {id: id, productId: productId}, {
            headers: this.getBasicHeaders()
        });

        return response;
    }
}
