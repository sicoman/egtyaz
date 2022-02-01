<template>
  <div class="app-container">
    <div class="filter-container">

      <el-input  v-permission="['manage user search']" v-model="query.keyword" :placeholder="$t('table.search')+' Name , Email , Mobile , City' " style="width: 300px;" class="filter-item" @keyup.enter.native="handleFilter" />
    
      <el-select v-permission="['manage user search']" style="width: 300px;" v-model="query.verification" multiple filterable placeholder="Verify Type">
        <el-option v-for="item in verification" :key="item.value" :label="item.label" :value="item.value">
        </el-option>
      </el-select>


      <el-button v-waves  v-permission="['manage user search']" class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        {{ $t('table.search') }} 
      </el-button>
      <el-button  v-permission="['manage user add']" class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-plus" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
      <el-button  v-permission="['manage user export']" v-waves :loading="downloading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
        {{ $t('table.export') }}
      </el-button>
    </div>

    <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.index }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Name">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Email">
        <template slot-scope="scope">
          <span>{{ scope.row.email }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Mobile">
        <template slot-scope="scope">
          <span>{{ scope.row.mobile }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="City">
        <template slot-scope="scope">
          <span v-if="scope.row.city && scope.row.city.hasOwnProperty('name')">{{ scope.row.city.name }}</span>
          <span v-else ></span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Joined At">
        <template slot-scope="scope">
                <span>{{ scope.row.created_at }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Verfications" width="120">
        <template slot-scope="scope">
          <el-button-group >
              <el-tooltip class="item"  effect="dark" :content="'Verfied At: '+scope.row.mobile_verified_at" placement="top-start">
                <el-button  size="mini" v-if="scope.row.mobile_verified_at" type="success" icon="el-icon-mobile"></el-button>
              </el-tooltip> 
          </el-button-group>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Actions" width="350">
        <template slot-scope="scope">
          <router-link :to="'/users/user/edit/'+scope.row.id" >
            <el-button type="primary" size="small" icon="el-icon-edit" v-permission="['manage user edit']">
              
            </el-button>
          </router-link>
        
          <el-button type="danger" size="small" icon="el-icon-delete" v-permission="['manage user delete']" @click="handleDelete(scope.row.id, scope.row.name);">
            
          </el-button>


          <el-tooltip class="item" effect="dark" :content="'Status: '+status[scope.row.status]" placement="top-start">
                <el-button  v-permission="['manage user status']" size="small"  :type="scope.row.status ? 'success' : '' " :icon="scope.row.status ? 'el-icon-success' : 'el-icon-error'"
                @click="handleActive(scope.row , (scope.row.status ==1 ? 0 : 1) )" ></el-button>
          </el-tooltip>

        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />

    <el-dialog :title="'Create new user'" :visible.sync="dialogFormVisible">
      <div class="form-container" v-loading="userCreating">
        <el-form ref="userForm" :rules="rules" :model="newUser" label-position="left" label-width="150px" style="max-width: 500px;">
          
          <el-form-item :label="$t('user.name')" prop="name">
            <el-input v-model="newUser.name"  />
          </el-form-item>
          <el-form-item :label="$t('user.email')" prop="email">
            <el-input v-model="newUser.email" />
          </el-form-item>
          <el-form-item :label="$t('Mobile')" prop="mobile">
            <el-input v-model="newUser.mobile" />
          </el-form-item>


          <el-form-item :label="$t('Status')" prop="status">
              <el-select v-model="newUser.status" placeholder="Status">
                <el-option v-for="(item , index) in status" :key="index" :label="item" :value="index">
                </el-option>
              </el-select>
          </el-form-item>

          <el-form-item :label="$t('Roles')" prop="roles">
              <el-select v-model="newUser.roles" placeholder="Role">
                <el-option v-for="(item , index) in settings.nonAdminRoles" :key="item" :label="item" :value="item">
                </el-option>
              </el-select>
          </el-form-item>

        
          <el-form-item label="Image">
                      <el-upload class="avatar-uploader" :action="apiUrl" :show-file-list="false" :on-success="handleAvatarSuccess" :before-upload="beforeAvatarUpload">
                          <img v-if="newUser.avatar != ''" :src="newUser.avatar" class="avatar">
                          <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                      </el-upload>
          </el-form-item>

        
          <el-form-item :label="$t('user.password')" prop="password">
            <el-input v-model="newUser.password" show-password />
          </el-form-item>
          <el-form-item :label="$t('user.confirmPassword')" prop="confirmPassword">
            <el-input v-model="newUser.confirmPassword" show-password />
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false">
            {{ $t('table.cancel') }}
          </el-button>
          <el-button type="primary" @click="createUser()">
            {{ $t('table.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import UserResource from '@/api/user';
import Resource from '@/api/resource';
import waves from '@/directive/waves'; // Waves directive
import permission from '@/directive/permission'; // Waves directive
import checkPermission from '@/utils/permission'; // Permission checking

import settings from '@/settings' ;

const userResource = new UserResource();
const permissionResource = new Resource('permissions');

export default {
  name: 'UserList',
  components: { Pagination , permission },
  directives: { waves },
  data() {
    var validateConfirmPassword = (rule, value, callback) => {
      if (value !== this.newUser.password) {
        callback(new Error('Password is mismatched!'));
      } else {
        callback();
      }
    };
    return {
      apiUrl  : settings.apiUrl+'admin/upload' ,
      list: null,
      total: 0,
      loading: true,
      downloading: false,
      userCreating: false,
      query: {
        page: 1,
        limit: 15,
        keyword: '',
        role: 'user',
        verification : []
      },
      roles: ['admin', 'manager', 'editor'],
      nonAdminRoles: ['manager','editor'],
      newUser: {
        avatar : ''  ,
        roles  : ''
      },
      dialogFormVisible: false,
      dialogPermissionVisible: false,
      dialogPermissionLoading: false,
      currentUserId: 0,
      currentUser: {
        name: '',
        permissions: [],
        rolePermissions: [],
      },
      rules: {
        role: [{ required: true, message: 'Role is required', trigger: 'change' }],
        name: [{ required: true, message: 'Name is required', trigger: 'blur' }],
        email: [
          { required: true, message: 'Email is required', trigger: 'blur' },
          { type: 'email', message: 'Please input correct email address', trigger: ['blur', 'change'] },
        ],
        password: [{ required: true, message: 'Password is required', trigger: 'blur' }],
        confirmPassword: [{ validator: validateConfirmPassword, trigger: 'blur' }],
      },
      permissionProps: {
        children: 'children',
        label: 'name',
        disabled: 'disabled',
      },
      permissions: [],
      menuPermissions: [],
      otherPermissions: [],
      status : settings.userStatus ,
      verification : [
         {
           label : "Email" , value : "email_verified_at"
         },
         {
           label : "Mobile" , value : "mobile_verified_at"
         },
         {
           label : "Photo Id" , value : "photoid_verified_at"
         }
      ],
      settings : settings
    };
  },
  computed: {
    normalizedMenuPermissions() {
      let tmp = [];
      this.currentUser.permissions.role.forEach(permission => {
        tmp.push({
          id: permission.id,
          name: permission.name,
          disabled: true,
        });
      });
      const rolePermissions = {
        id: -1, // Faked ID
        name: 'Inherited from role',
        disabled: true,
        children: this.classifyPermissions(tmp).menu,
      };

      tmp = this.menuPermissions.filter(permission => !this.currentUser.permissions.role.find(p => p.id === permission.id));
      const userPermissions = {
        id: 0, // Faked ID
        name: 'Extra menus',
        children: tmp,
        disabled: tmp.length === 0,
      };

      return [rolePermissions, userPermissions];
    },
    normalizedOtherPermissions() {
      let tmp = [];
      this.currentUser.permissions.role.forEach(permission => {
        tmp.push({
          id: permission.id,
          name: permission.name,
          disabled: true,
        });
      });
      const rolePermissions = {
        id: -1,
        name: 'Inherited from role',
        disabled: true,
        children: this.classifyPermissions(tmp).other,
      };

      tmp = this.otherPermissions.filter(permission => !this.currentUser.permissions.role.find(p => p.id === permission.id));
      const userPermissions = {
        id: 0,
        name: 'Extra permissions',
        children: tmp,
        disabled: tmp.length === 0,
      };

      return [rolePermissions, userPermissions];
    },
    userMenuPermissions() {
      return this.classifyPermissions(this.userPermissions).menu;
    },
    userOtherPermissions() {
      return this.classifyPermissions(this.userPermissions).other;
    },
    userPermissions() {
      return this.currentUser.permissions.role.concat(this.currentUser.permissions.user);
    },
  },
  created() {
    this.resetNewUser();
    this.getList();
    if (checkPermission(['manage permission'])) {
      this.getPermissions();
    }
  },
  methods: {
    checkPermission,
    async getPermissions() {
      const { data } = await permissionResource.list({});
      const { all, menu, other } = this.classifyPermissions(data);
      this.permissions = all;
      this.menuPermissions = menu;
      this.otherPermissions = other;
    },

    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, total } = await userResource.list(this.query);
      this.list = data;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = total;
      this.loading = false;
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    handleCreate() {
      this.resetNewUser();
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['userForm'].clearValidate();
      });
    },
    handleDelete(id, name) {
      this.$confirm('This will permanently delete user ' + name + '. Continue?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning',
      }).then(() => {
        userResource.destroy(id).then(response => {
          this.$message({
            type: 'success',
            message: 'Delete completed',
          });
          this.handleFilter();
        }).catch(error => {
          console.log(error);
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Delete canceled',
        });
      });
    },
    handleActive : function(user , status){
                var o = this ;
                 
                this.$confirm('This will Update User Status. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {

                    userResource.active(settings.apiUrl+'users/active/'+user.id , status).then(response => {
                            this.$message({
                                type: 'success',
                                message: 'Status Changed Succefully',
                            });
                            this.handleFilter();
                        }).catch(error => {
                            console.log(error);
                            this.$message({ type: 'danger', message: error });     
                        });
                    
                }).catch(() => {
                    this.$message({ type: 'info', message: 'Update canceled'});          
                });

    } ,
    async handleEditPermissions(id) {
      this.currentUserId = id;
      this.dialogPermissionLoading = true;
      this.dialogPermissionVisible = true;
      const found = this.list.find(user => user.id === id);
      const { data } = await userResource.permissions(id);
      this.currentUser = {
        id: found.id,
        name: found.name,
        permissions: data,
      };
      this.dialogPermissionLoading = false;
      this.$nextTick(() => {
        this.$refs.menuPermissions.setCheckedKeys(this.permissionKeys(this.userMenuPermissions));
        this.$refs.otherPermissions.setCheckedKeys(this.permissionKeys(this.userOtherPermissions));
      });
    },
    createUser() {
      this.$refs['userForm'].validate((valid) => {
        if (valid) {
           // this.newUser.roles = [this.newUser.role];
          this.userCreating = true;
          userResource
            .store(this.newUser)
            .then(response => {
              this.$message({
                message: 'New user ' + this.newUser.name + '(' + this.newUser.email + ') has been created successfully.',
                type: 'success',
                duration: 5 * 1000,
              });
              this.resetNewUser();
              this.dialogFormVisible = false;
              this.handleFilter();
            })
            .catch(error => {
              console.log(error);
            })
            .finally(() => {
              this.userCreating = false;
            });
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    resetNewUser() {
      this.newUser = {
        name: '',
        email: '',
        password: '',
        confirmPassword: '',
        avatar: '',
        mobile: '',
        city: '',
        role:'',
        status: 0
      };
    },
    handleDownload() {
      this.downloading = true;
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['id', 'name', 'email', 'mobile','city','Units','Requests','Joined at'];
        
        const data = [] ; var col = 7 ;
        let rows = document.querySelectorAll(".el-table__body tr");
        rows.forEach(function(v , k){
            let r = Array.prototype.slice.call(v.children) ;
            var rtr = [] ;
            r.forEach(function(vv ,kk){
                if( kk <= col ){
                    rtr.push(vv.innerText) ;
                }
            });
            data.push(rtr) ;
        });


        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'user-list',
        });
        this.downloading = false;
      });
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },
    permissionKeys(permissions) {
      return permissions.map(permssion => permssion.id);
    },
    classifyPermissions(permissions) {
      const all = []; const menu = []; const other = [];
      permissions.forEach(permission => {
        const permissionName = permission.name;
        all.push(permission);
        if (permissionName.startsWith('view menu')) {
          menu.push(this.normalizeMenuPermission(permission));
        } else {
          other.push(this.normalizePermission(permission));
        }
      });
      return { all, menu, other };
    },
    handleAvatarSuccess(res, file) {
                this.newUser.avatar = file.response ;
    },
    beforeAvatarUpload(file) {
                const isJPG = ( file.type === 'image/jpeg' || file.type === 'image/png' || file.type === 'image.gif' );
                const isLt2M = file.size / 1024 / 1024 < 10;

                if (!isJPG) {
                    this.$message.error('Avatar picture must be JPG format!');
                }
                if (!isLt2M) {
                    this.$message.error('Avatar picture size can not exceed 2MB!');
                }

                return isJPG && isLt2M;
  },

    normalizeMenuPermission(permission) {
      return { id: permission.id, name: this.$options.filters.uppercaseFirst(permission.name.substring(10)), disabled: permission.disabled || false };
    },

    normalizePermission(permission) {
      const disabled = permission.disabled || permission.name === 'manage permission';
      return { id: permission.id, name: this.$options.filters.uppercaseFirst(permission.name), disabled: disabled };
    },

    confirmPermission() {
      const checkedMenu = this.$refs.menuPermissions.getCheckedKeys();
      const checkedOther = this.$refs.otherPermissions.getCheckedKeys();
      const checkedPermissions = checkedMenu.concat(checkedOther);
      this.dialogPermissionLoading = true;

      userResource.updatePermission(this.currentUserId, { permissions: checkedPermissions }).then(response => {
        this.$message({
          message: 'Permissions has been updated successfully',
          type: 'success',
          duration: 5 * 1000,
        });
        this.dialogPermissionLoading = false;
        this.dialogPermissionVisible = false;
      });
    },
  },
};
</script>

<style>
  .el-left{
      float:right
  }
  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }
  .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }

  table .el-button + .el-button {
    margin-left: 0px !important ;
 }
</style>

<style lang="scss" scoped>
.edit-input {
  padding-right: 100px;
}
.cancel-btn {
  position: absolute;
  right: 15px;
  top: 10px;
}
.dialog-footer {
  text-align: left;
  padding-top: 0;
  margin-left: 150px;
}
.app-container {
  flex: 1;
  justify-content: space-between;
  font-size: 14px;
  padding-right: 8px;
  .block {
    float: left;
    min-width: 250px;
  }
  .clear-left {
    clear: left;
  }
}
</style>
