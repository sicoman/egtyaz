<template>
  <el-card v-if="user.name">
    <div class="user-profile">
      <div class="user-avatar box-center">
        <pan-thumb :image="user.avatar" :height="'100px'" :width="'100px'" :hoverable="false"/>
      </div>
      <div class="box-center">
        <div class="user-name text-center">{{ user.name }}</div>
      </div>
      
      <div class="box-socialx ">
       
        <el-table :data="userVerication" :show-header="true">
          <el-table-column prop="n" label="Type" width="90"></el-table-column>
          <el-table-column label="Status" align="left" width="80">
            <template slot-scope="scope">
                  <div>
                      <span v-if="scope.row.v != null">
                        <el-tooltip :content="scope.row.v" placement="top">
                          <i class="el-icon-success " style="color:#00F000"></i>
                        </el-tooltip>   
                      </span>
                      <span v-else><i class="el-icon-error"></i></span>
                  </div>
            </template>
          </el-table-column>
          <el-table-column label="View" width="80">
              <template slot-scope="scope">
                  <span v-if="user[scope.row.n] !== null ">
                    <el-button type="primary" size="mini" @click="openView( scope.row )"><i class="el-icon-view"></i></el-button>   
                  </span>
            </template>
          </el-table-column>
          <el-table-column label="Confirm" width="80">
              <template slot-scope="scope">
                  <div>
                      <span v-if="scope.row.v == null && scope.row.n == 'email' && user.email != null ">
                        <el-button size="mini" @click="Accept( scope.row , 'now' )"><i class="el-icon-edit"></i></el-button>   
                      </span>
                      <span v-else-if="scope.row.v == null && scope.row.n == 'mobile' && user.mobile != null ">
                        <el-button size="mini" @click="Accept( scope.row , 'now' )"><i class="el-icon-edit"></i></el-button>   
                      </span>
                      <span v-else-if="scope.row.v == null && scope.row.n == 'photoid' && user.photoid != null ">
                        <el-button  size="mini" @click="Accept( scope.row , 'now' )"><i class="el-icon-edit"></i></el-button>   
                      </span>
                      <span v-else-if="scope.row.v != null">
                        <el-tooltip content="Toggle Confirmation" placement="top">
                        <el-button size="mini" @click="Accept( scope.row , null )">
                          <i class="el-icon-edit"></i>
                        </el-button>  
                        </el-tooltip> 
                      </span>
                  </div>
            </template>
          </el-table-column>
        </el-table>
      </div>


      
    </div>
  </el-card>
</template>

<script>
import PanThumb from '@/components/PanThumb' ;
import UserResource from '@/api/user';
import settings from '@/settings' ;

export default {
  components: { PanThumb },
  props: {
    user : {
      type: Object,
      default: () => {
        return {
          name: '',
          email: '',
          avatar: '',
          email_verified_at: '',
          mobile_verified_at: '',
          photoid_verified_at: '',
          roles: [],
        };
      },
    },
  },
  data() {
    return {
      userVerication: []
    };
  },
  methods: {
    getRole() {
      const roles = this.user.roles.map(value => this.$options.filters.uppercaseFirst(value));
      return roles.join(' | ');
    },
    verifications(){
        this.userVerication = [
              {'n' : 'email' , 'v' : this.user.email_verified_at} ,
              {'n' : 'mobile' , 'v' : this.user.mobile_verified_at} ,
        ];
    },
    openView(row) {
        if(row.n == 'email') {
             this.$message( 'Email is : '+ this.user.email );
        }else if( row.n == 'mobile' ) {
             this.$message( 'Mobile is : '+ this.user.mobile );
        }else{
            window.open( this.user.photoid ) ;
        }
  },
    Accept(row , valu ){
        var o = this ;
                 
                this.$confirm('This will Update Verification Status. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {

                        let us = new UserResource();
                   
                        us.DoVerify( this.user.id , row.n , valu ).then( response => {
                            
                            this.$message({
                                type: 'success',
                                message: 'Verification Updated Succefully',
                            });
                            this.user = response ;
                            this.verifications();
                            
                        }).catch(error => {
                          
                            this.$message({ type: 'danger', message: error });     
                        });
                    
                }).catch((e) => {
                    console.log(e);
                    this.$message({ type: 'info', message: 'Verification canceled'});          
                });
    }
  },
  mounted(){
     var self  = this ;
     setTimeout(function(){
        self.verifications() ;
     }, 2000 )
     
  }
  
};
</script>

<style lang="scss" scoped>
.user-profile {
  .user-name {
    font-weight: bold;
  }
  .box-center {
    padding-top: 10px;
  }
  .user-role {
    padding-top: 10px;
    font-weight: 400;
    font-size: 14px;
  }
  .box-social {
    padding-top: 30px;

    .el-table {
      border-top: 1px solid #dfe6ec;
    }

  }
  .user-follow {
    padding-top: 20px;
  }
}
</style>
