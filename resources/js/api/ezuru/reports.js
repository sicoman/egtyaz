import request from '@/utils/request';
import Resource from '@/api/resource';

class ReportsResource extends Resource {
    constructor() {
        super('reports');
    }

    selectUnit(s) {
        return request({
            url: '/' + this.uri + '/list/unit?s=' + s,
            method: 'get',
        });
    }
}

export { ReportsResource as default };