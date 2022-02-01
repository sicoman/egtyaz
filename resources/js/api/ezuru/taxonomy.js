import request from '@/utils/request';
import Resource from '@/api/resource';

class TaxonomyResource extends Resource {
    constructor() {
        super('admin/taxonomy');
    }
}

export { TaxonomyResource as default };