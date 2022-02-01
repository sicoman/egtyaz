import request from '@/utils/request';
import Resource from '@/api/resource';

class CancelResource extends Resource {
    constructor() {
        super('cancel');
    }
}

export { CancelResource as default };