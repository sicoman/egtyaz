import request from '@/utils/request';

import Resource from '@/api/resource';

class ReviewsResource extends Resource {
    constructor() {
        super('reviews');
    }

}

export { ReviewsResource as default };