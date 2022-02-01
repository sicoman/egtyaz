import request from '@/utils/request';

import Resource from '@/api/resource';

class LogResource extends Resource {
    constructor() {
        super('log');
    }

}

export { LogResource as default };