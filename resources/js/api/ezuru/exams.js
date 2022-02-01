import request from '@/utils/request';

import Resource from '@/api/resource';

class ExamsResource extends Resource {
    constructor() {
        super('exams');
    }

    active(url, status, id, message, charge, date) {
        return request({
            url: '/' + this.uri + '/active/' + id,
            method: 'post',
            data: { "status": status, "message": message, "charge": charge, "date": date },
        });
    }

    get(id , student = '') {
        return request({
            url: '/' + this.uri + '/' + id +'?student='+student,
            method: 'get',
        });
    }

    addEdit(id) {
        return request({
            url: '/' + this.uri + '/' + id +'?is_add_edit=true',
            method: 'get',
        });
    }

}

export { ExamsResource as default };