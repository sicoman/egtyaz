import request from '@/utils/request';

import Resource from '@/api/resource';

class FlagsResource extends Resource {
    constructor() {
        super('flags');
    }

}

export { FlagsResource as default };