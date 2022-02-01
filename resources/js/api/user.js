import request from '@/utils/request';
import Resource from '@/api/resource';

class UserResource extends Resource {

    constructor() {
        super('users');
    }

    permissions(id) {
        return request({
            url: '/' + this.uri + '/' + id + '/permissions',
            method: 'get',
        });
    }

    updatePermission(id, permissions) {
        return request({
            url: '/' + this.uri + '/' + id + '/permissions',
            method: 'put',
            data: permissions,
        });
    }

    DoVerify(userid, fi, status) {
        return request({
            url: '/' + this.uri + '/verifiy/' + userid,
            method: 'post',
            data: { "field": fi, "status": status },
        });
    }

    updateArea(userid, area) {
        return request({
            url: '/' + this.uri + '/area/' + userid,
            method: 'post',
            data: { "area": area },
        });
    }


}

export { UserResource as default };