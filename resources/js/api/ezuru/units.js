import request from '@/utils/request';
import Resource from '@/api/resource';

class UnitResource extends Resource {
    constructor() {
        super('units');
    }

    feature(unit, status) {
        return request({
            url: '/' + this.uri + '/feature/' + unit,
            method: 'post',
            data: { "status": status },
        });
    }
}

export { UnitResource as default };