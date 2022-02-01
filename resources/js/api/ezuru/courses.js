import request from '@/utils/request';
import Resource from '@/api/resource';

class CoursesResource extends Resource {
    constructor() {
        super('admin/course');
    }
}

export { CoursesResource as default };