import request from '@/utils/request';

import Resource from '@/api/resource';

class PaymentsResource extends Resource {
    constructor() {
        super('payments');
    }

}

export { PaymentsResource as default };