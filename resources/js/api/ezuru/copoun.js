import request from '@/utils/request';
import Resource from '@/api/resource';

class CopounResource extends Resource {
    constructor() {
        super('admin/copouns');
    }
}

export { CopounResource as default };