import request from '@/utils/request';

import Resource from '@/api/resource';

class BadgesResource extends Resource {
    constructor() {
        super('badges');
    }

}

export { BadgesResource as default };