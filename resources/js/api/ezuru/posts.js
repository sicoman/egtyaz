import request from '@/utils/request';
import Resource from '@/api/resource';

class PostsResource extends Resource {
    constructor() {
        super('admin/post');
    }
}

export { PostsResource as default };