import request from '@/utils/request';
import Resource from '@/api/resource';

class QuestionResource extends Resource {
    constructor() {
        super('admin/questions');
    }
}

export { QuestionResource as default };