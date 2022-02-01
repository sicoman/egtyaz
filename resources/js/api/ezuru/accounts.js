import request from '@/utils/request';

import Resource from '@/api/resource';

class AccountsResource extends Resource {
    constructor() {
        super('accounts');
    }

}

export { AccountsResource as default };