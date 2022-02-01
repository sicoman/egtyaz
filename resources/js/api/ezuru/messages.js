import request from '@/utils/request';

import Resource from '@/api/resource';

class MessagesResource extends Resource {
    constructor() {
        super('messages');
    }

    chat(id, query) {
        return request({
            url: '/' + this.uri + '/chat/' + id,
            method: 'post',
            data: query,
        });
    }

    chat_delete(id) {
        return request({
            url: '/' + this.uri + '/chat/' + id,
            method: 'delete',
        });
    }

    chat_answer(id, query) {
        return request({
            url: '/' + this.uri + '/chat/' + id + '/answer',
            method: 'post',
            data: query
        });
    }

    chat_item_delete(id, admin) {
        return request({
            url: '/' + this.uri + '/chat/' + id + '/' + admin,
            method: 'delete',
        });
    }

}

export { MessagesResource as default };