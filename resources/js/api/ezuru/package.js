import request from '@/utils/request';
import Resource from '@/api/resource';

class PackageResource extends Resource {
    constructor() {
        super('admin/packages');
    }
}

export { PackageResource as default };