import request from '@/utils/request';

/**
 * Simple RESTful resource class
 */
class Resource {
    constructor(uri) {
        this.uri = uri;
    }

    list(query) {

        return request({
            url: '/' + this.uri,
            method: 'get',
            params: query,
        });
    }
    get(id) {
        return request({
            url: '/' + this.uri + '/' + id,
            method: 'get',
        });
    }
    store(resource) {
        return request({
            url: '/' + this.uri,
            method: 'post',
            data: resource,
        });
    }
    update(id, resource) {
        return request({
            url: '/' + this.uri + '/' + id,
            method: 'put',
            data: resource,
        });
    }
    destroy(id) {
        return request({
            url: '/' + this.uri + '/' + id,
            method: 'delete',
        });
    }

    active(url, status, id) {
        return request({
            url: '/' + this.uri + '/active/' + id,
            method: 'post',
            data: { "status": status },
        });
    }

    select(query) {
        return request({
            url: '/' + this.uri + '/select',
            method: 'get',
            params: query,
        });
    }

    selectByType(type) {
        return request({
            url: '/' + this.uri + '/select/' + type ,
            method: 'get',
        });
    }


}

export { Resource as default };