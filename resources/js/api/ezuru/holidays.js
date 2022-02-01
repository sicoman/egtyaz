import request from '@/utils/request';

import Resource from '@/api/resource';

class HolidaysResource extends Resource {
    constructor() {
        super('holidays');
    }

}

export { HolidaysResource as default };