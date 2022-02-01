import request from '@/utils/request';
import Resource from '@/api/resource';

class TicketsResource extends Resource {
    constructor() {
        super('admin/tickets');
    }
}

export { TicketsResource as default };