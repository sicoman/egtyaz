<template>
  <el-card v-if="user.name">
    <el-tabs v-model="activeActivity" @tab-click="handleClick"  v-permission="['view menu units']">
        <el-tab-pane label="Account" name="first" v-loading="updating" >
          <div class="block">
              <el-form ref="userForm" :model="user" label-position="left" label-width="150px">
                      <el-form-item label="Name">
                        <el-input v-model="user.name" :disabled="user.role === 'admin'"></el-input>
                      </el-form-item>
                      <el-form-item label="Email">
                        <el-input v-model="user.email" :disabled="user.role === 'admin'"></el-input>
                      </el-form-item>
                      <el-form-item :label="$t('Mobile')" prop="mobile">
                          <el-input v-model="user.mobile" />
                        </el-form-item>
                        <!--
                        <el-form-item :label="$t('City')" prop="city">
                          <vue-google-autocomplete v-model="user.city" types="geocode" ref="city" id="map" classname="form-control el-input__inner" placeholder="Please type your address" v-on:placechanged="getAddressData" >
                          </vue-google-autocomplete>
                        </el-form-item>
                        -->
                        <el-form-item :label="$t('Status')" prop="status">
                            <el-select v-model="user.status" placeholder="Status">
                              <el-option v-for="(item , index) in status" :key="index" :label="item" :value="parseInt(index)">
                              </el-option>
                            </el-select>
                        </el-form-item>
                        
                        <el-form-item label="Image">
                                    <el-upload class="avatar-uploader" :action="apiUrl" :show-file-list="false" :on-success="handleAvatarSuccess" :before-upload="beforeAvatarUpload">
                                        <img v-if="user.avatar != '' || user.avatar != null" :src="user.avatar" class="avatar">
                                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                    </el-upload>
                        </el-form-item>

                        <el-form-item :label="$t('Points')" prop="points">
                          <el-input v-model="user.points" />
                        </el-form-item>

                        <el-form-item :label="$t('Description')" prop="description">
                          <el-input type="textarea" v-model="user.description" />
                        </el-form-item>

                        <el-form-item :label="$t('user.password')" prop="password">
                          <el-input v-model="user.password" show-password />
                        </el-form-item>
                        <el-form-item :label="$t('user.confirmPassword')" prop="confirmPassword">
                          <el-input v-model="user.password2" show-password />
                        </el-form-item>

                      <el-form-item>
                        <el-button type="primary" @click="onSubmit" :disabled="user.role === 'admin'">Update</el-button>
                      </el-form-item>
              </el-form>
          </div>
      </el-tab-pane>
    
      
      <el-tab-pane label="Exams" name="four"  v-permission="['view menu booking']">
        <div class="block">
            <ExamsTable @reloadAgain="getExams('free')" :list="Exams" :loading="loading" ></ExamsTable>

        </div>
      </el-tab-pane>

      <el-tab-pane label="Mock Tests" name="five" v-permission="['view menu payments']">
        <div class="block">
            <ExamsTable @reloadAgain="getExams('mock')" :list="Mock" :loading="loading" ></ExamsTable>
        </div>
      </el-tab-pane>

      <el-tab-pane label="Subscripations" name="six" v-permission="['view menu payments']">
        <div class="block">
              <PaymentsTable @reloadAgain="getPayments"  type="package" :list="Payments" :loading="loading" ></PaymentsTable>
        </div>
      </el-tab-pane>

      <el-tab-pane label="Courses" name="sixx" v-permission="['view menu payments']">
        <div class="block">
              <PaymentsTable @reloadAgain="getPaymentsCourses" type="course" :list="Courses" :loading="loading" ></PaymentsTable>
        </div>
      </el-tab-pane>

      <el-tab-pane label="Badges" name="seven" v-permission="['view menu payments']">
        <div class="block">
              <BadgeTable @reloadAgain="getBadges" :list="badges" :u_u_id="u_u_id" :type="'user'" :loading="loading" ></BadgeTable>
        </div>
      </el-tab-pane>

      <el-tab-pane label="Add Package" name="eight" v-permission="['view menu payments']">
        <div class="block">
              <addPackage :user_id="u_u_id" types="package" :search="{}"></addPackage>
        </div>
      </el-tab-pane>

      <el-tab-pane label="Add Course" name="nine" v-permission="['view menu payments']">
        <div class="block">
              <addPackage :user_id="u_u_id" types="course"  :search="{}"></addPackage>
        </div>
      </el-tab-pane>

      <el-tab-pane label="Points" name="ten" v-permission="['view menu payments']">
        <div class="block">
              <addPoints :user_id="u_u_id" types="course"  :search="{}"></addPoints>
        </div>
      </el-tab-pane>

      


    </el-tabs>
  </el-card>
</template>

<script>

import Resource from '@/api/user';
const userResource = new Resource('users');

import UnitResource from '@/api/ezuru/units';
import ExamsResource from '@/api/ezuru/exams';
import PaymentsResource from '@/api/ezuru/payments';

import BadgesResource from '@/api/ezuru/badges';

const Packages = new UnitResource('packages') ;

import settings from '@/settings' ;

import VueGoogleAutocomplete from 'vue-google-autocomplete' ;

import PaymentsTable from '@/views/ezuru/payments/components/Table' ;

import BadgeTable from '@/views/ezuru/badges/components/Table' ;

import ExamsTable from '@/views/ezuru/exams/components/Table' ;

import addPackage from '@/views/ezuru/payments/components/add' ;

import addPoints from '@/views/ezuru/payments/components/points' ;

export default {
  props: {
    user: {},
    UserUnits:{},
    badges : [],
    u_u_id : ''
  },
  components: { VueGoogleAutocomplete , PaymentsTable , BadgeTable , ExamsTable , addPackage , addPoints },
  data() {
    return {
      activeActivity: 'first',
      apiUrl  : settings.apiUrl+'admin/upload' ,
      status : settings.userStatus,
      updating: false,
      loading:false,
      OwnerRequests : [] ,
      UserRequests : [] ,
      badges : [] ,
      Mock : []
    };
  },
  methods: {
    handleClick(tab, event) {
      
    },
    onSubmit() {
      this.updating = true;
      userResource
        .update(this.user.id, this.user)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'User information has been updated successfully',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          this.updating = false;
        });
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
  getAddressData: function (addressData, placeResultData, id) {
      
      
      this.user.city = document.querySelector("#map").value ;
  }
  ,
  async getUnits(){
      return false;
      const un = new UnitResource();
      var query = { user_id : this.$route.params.id } ;
      var UserUnits = await un.list(query) ;
      this.UserUnits = UserUnits.data ;
  },
  async getRequests(){
      return false;
      const req = new BookingResource();
      var UserReq = await req.list({user_id: this.$route.params.id}) ;
      this.UserRequests = UserReq.data ;
  },
  async getExams(type = 'free'){
      
      const req = new ExamsResource();
      if( type == 'free' ) {
        var UserExams = await req.list({ user_id: this.$route.params.id  , 'type' : type }) ;
        this.Exams = UserExams.data ;
      }else{
        var UserExams = await req.list({ 'type' : type }) ; // user_id: this.$route.params.id  , 
        this.Mock = UserExams.data ;
      }
  },
  async getPayments(){
      const req = new PaymentsResource();
      var UserReq = await req.list({user_id: this.$route.params.id}) ;
      this.Payments = UserReq.data ;
  },
  async getPaymentsCourses(){
      const req = new PaymentsResource();
      var UserReq = await req.list({user_id: this.$route.params.id , 'type' : 'course' }) ;
      this.Courses = UserReq.data ;
  },
    async getIncome(){
        return false;
        const req = new PaymentsResource();
        var UserReq = await req.list({owner_id: this.$route.params.id}) ;
        this.Income = UserReq.data ;
    },
    async getBadges(){
        const req = new BadgesResource();
        var Badges = await req.list({user_id: this.$route.params.id , 'type' : 'user' }) ;
        this.badges = Badges.data ;
    }
  },
  mounted(){

        if( this.u_u_id == '' ){
           this.u_u_id = this.$route.params.id ;
        }

      /*
        this.getUnits();
        this.getRequests();
        this.getBookings();
        this.getIncome();
      */
        this.getExams() ;
        this.getExams('mock') ;
        this.getPayments();
        this.getPaymentsCourses();
        this.getBadges();
  },
  computed : {
        
  }


      


  
};
</script>

<style lang="scss" scoped>
.user-activity {
  .user-block {
    .username, .description {
      display: block;
      margin-left: 50px;
      padding: 2px 0;
    }
    img {
      width: 40px;
      height: 40px;
      float: left;
    }
    :after {
      clear: both;
    }
    .img-circle {
      border-radius: 50%;
      border: 2px solid #d2d6de;
      padding: 2px;
    }
    span {
      font-weight: 500;
      font-size: 12px;
    }
  }
  .post {
    font-size: 14px;
    border-bottom: 1px solid #d2d6de;
    margin-bottom: 15px;
    padding-bottom: 15px;
    color: #666;
    .image {
      width: 100%;
    }
    .user-images {
      padding-top: 20px;
    }
  }
  .list-inline {
    padding-left: 0;
    margin-left: -5px;
    list-style: none;
    li {
      display: inline-block;
      padding-right: 5px;
      padding-left: 5px;
      font-size: 13px;
    }
    .link-black {
      &:hover, &:focus {
        color: #999;
      }
    }
  }
  .el-carousel__item h3 {
    color: #475669;
    font-size: 14px;
    opacity: 0.75;
    line-height: 200px;
    margin: 0;
  }

  .el-carousel__item:nth-child(2n) {
    background-color: #99a9bf;
  }

  .el-carousel__item:nth-child(2n+1) {
    background-color: #d3dce6;
  }
}
</style>
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
 .search-location {
  display: block;
  width: 60vw;
  margin: 0 auto;
  margin-top: 5vw;
  font-size: 20px;
  font-weight: 400;
  outline: none;
  height: 30px;
  line-height: 30px;
  text-align: center;
  border-radius: 10px;
}

</style>