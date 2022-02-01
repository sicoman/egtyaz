import request from '@/utils/request';
import Resource from '@/api/resource';

class DashboardResource extends Resource {
    constructor() {
        super('dashboard');
    }

    stat() {
        return request({
            url: '/stat',
            method: 'get',
        });
    }
}

export { DashboardResource as default };