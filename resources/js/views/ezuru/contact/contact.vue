<template>
    <div id="post" class="app-container">
            <el-container>
                <el-header>
                    <el-button type="warning"  @click="search.visible = true"><i class="el-icon-search"></i> Search </el-button>    
                    <br />
                </el-header>
                <el-main height="" >
                    <el-row >
                        <el-col :xs="24" :sm="24" :lg="24" v-if="addNew == false">

                              <el-dialog title="Search" :visible.sync="search.visible" >
                                <el-form label-position="top" :model="search">
                                    <el-row>
                                        <el-col   :xs="24" :sm="24" :lg="10">
                                            <el-form-item label="text Search">
                                                <el-input v-model="search.s" autocomplete="off"></el-input>
                                            </el-form-item> 
                                        </el-col>
                                        <el-col :span="2">
                                            &nbsp;
                                        </el-col>
                                        <el-col   :xs="24" :sm="24" :lg="10">
                                            <el-form-item label="Status" >
                                                <el-select v-model="search.status" placeholder="Select Status">
                                                    <el-option label="Unreaded" value="0"></el-option>
                                                    <el-option label="Readed" value="1"></el-option>
                                                </el-select>
                                            </el-form-item>
                                       </el-col>
                                    </el-row>


                                </el-form>
                                <span slot="footer" class="dialog-footer">
                                    <el-button @click="PostSearch(false)">Cancel</el-button>
                                    <el-button type="primary" @click="PostSearch(true)">Search</el-button>
                                </span>
                            </el-dialog>
                            
                              <el-table :data="postData" style="width: 100%" v-loading="listLoading"  row-key="id" border fit highlight-current-row>
                                    <!-- <el-table-column prop="id" width="50" label="ID"></el-table-column>-->
                                    <el-table-column prop="name" label="Name"></el-table-column>
                                    <el-table-column prop="email" label="Email Address"></el-table-column>
                                    <el-table-column v-if="type == 'adv'" prop="mobile" label="Mobile"></el-table-column>
                                    <el-table-column v-if="type == 'adv'" prop="subject" label="Message title"></el-table-column>
                                    <el-table-column prop="message" label="Message"></el-table-column>
                                    <el-table-column prop="ip" label=" ip "></el-table-column>
                                    <el-table-column prop="created_at" label="Created at"></el-table-column>
                                    
                                    <el-table-column prop="status" label="Read Status">
                                        <template slot-scope="scope">
                                                <div>
                                                    <p v-if="scope.row.status == 1"> Readed </p>
                                                    <p v-if="scope.row.status == 0"> </p>
                                                </div>
                                        </template>
                                    </el-table-column>
                                    

                                    <el-table-column label="Operations">
                                        <template slot-scope="scope">

                                            <el-tooltip v-if="scope.row.status == 1" content="Make un Readed" placement="top" >
                                                <el-button  size="mini" type="info" @click="ActivePost(scope.row , 0)" ><i class="el-icon-error"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip v-else-if="scope.row.status == 0" content="Make Readed" placement="top">
                                                <el-button  size="mini" type="success" @click="ActivePost(scope.row , 1)" ><i class="el-icon-success"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip content="Delete" placement="top">
                                                <el-button size="mini" type="danger" @click="DeletePost(scope.row)" ><i class="el-icon-delete"></i></el-button>
                                            </el-tooltip>
                                        </template>
                                    </el-table-column>
                              </el-table>

                                <el-pagination
                                        background
                                        layout="pager"
                                        :page-size="pagination.per_page"
                                        :total="pagination.total"
                                        :current-page="pagination.current_page"
                                        :hide-on-single-page="true"
                                        @current-change="gotoPage"
                                        >
                                </el-pagination>

                        </el-col>
                    </el-row>
                </el-main>
            </el-container>


    </div>
</template>

<script>
    import settings from '@/settings' ;
    import Tinymce from '@/components/Tinymce';
    import { setTimeout } from 'timers';

    import permission from '@/directive/permission/index.js' ;

    import Cookies from 'js-cookie' ;

    const TokenKey = 'Admin-Token' ;

    export default {
        directives : { permission } ,
        data() {
           return {
               "tit" : "Show All" ,
               "type" : this.$route.params.type ,
               "ptype" : this.getPtype() ,
               "id"   : 0 ,
               "name" : '' ,
               "email" : '' ,
               "mobile" : "" ,
               "subject" : "" ,
               "message" : '' ,
               "status" : '' ,
               postData : [] ,
               search : {
                   's' : '' ,
                   'status' : '',
                   'visible' : false
               },
               pagination : {

               },
               addNew : false ,
               apiUrl  : settings.apiUrl+'admin/upload',
               listLoading : false,
               setting : {

               }
           }
        },
        components : { Tinymce } ,
        methods : {
            
            DeletePost : function(Post){
                var o = this ;

                this.$confirm('This will permanently delete the Post. Continue?', 'Warning', {
                confirmButtonText: 'Agree',
                cancelButtonText: 'Cancel',
                type: 'danger'
                }).then(() => {

                    fetch(settings.apiUrl+'admin/contact/'+Post.id , {
                        "method" : "DELETE" ,
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                        }
                    })
                    .then( res => res.json() )
                    .then( function(res){
                            if( res.res == 0 ){
                                o.$message.error( 'Unable to delete ' );
                            }else{
                                o.$message.success('Succefully Deleted');
                                o.getList() ;
                            }
                    });

                }).catch(() => {
                    this.$message({ type: 'info', message: 'Operation Canceled'});
                });
            } ,
            ActivePost : function(Post , status){
                var o = this ;

                this.$confirm('Do yo want to cancel ؟؟', 'Warning', {
                confirmButtonText: 'Agree',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {

                    fetch(settings.apiUrl+'admin/contact/active/'+Post.id , {
                        "method" : "POST" ,
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                        },
                        body : JSON.stringify( { "status" : status  } )
                    })
                    .then( res => res.json() )
                    .then( function(res){
                            if( res.res == 0 ){
                                o.$message.error( 'Unable to update ' );
                            }else{
                                o.$message.success('Updated Succefully');
                                o.getList() ;
                            }
                    });

                }).catch(() => {
                    this.$message({ type: 'info', message: 'Canceled'});
                });

            } ,
            
            handleAvatarSuccess(res, file) {
                this.photo = file.response ;
            },
            handleFileSuccess(res, file) {
                this.file = file.response ;
            },
            beforeAvatarUpload(file) {
                
                const isJPG = ( file.type === 'image/jpeg' || file.type === 'image/png' || file.type === 'image/gif' || file.type == 'image/svg+xml' );
                const isLt2M = file.size / 1024 / 1024 < 10 ;

                if (!isJPG) { 
                    this.$message.error('Image not supported !');
                }
                if (!isLt2M) {
                    this.$message.error('Max size is 2 mega!');
                }

                return isJPG && isLt2M;
            },
            PostSearch : function(search){

                if( !search ){
                    this.search.s = '' ;
                    this.search.status = '' ;
                }else{
                    this.$message.info('Plese wait');
                }
                this.getList( 1 , true , true ) ;

                this.search.visible = false ;
            },
            gotoPage : function(a){
                this.getList(a , true , false) ;
            },
            getList : function( page = 1 , search = false , message = false ){
                if( !this.type ){
                    this.type = this.$route.params.type ;
                }
                let self = this ;

                self.listLoading = true ;

                let u = settings.apiUrl+'admin/contact/?type='+ this.type+'&page='+page ;

                if( search && message ){
                    u = u+'&s='+self.search.s+'&status='+self.search.status ;
                }

               fetch( u , { 
                method: 'get', 
                headers: new Headers({
                    'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                }) 
                } )
               .then( res => res.json() )
               .then( function(res){
                     self.postData = res.data ;

                     self.pagination.total = res.total ;
                     self.pagination.per_page = res.per_page ;
                     self.pagination.current_page = res.current_page ;

                     if( search && message ){
                        self.$message.success('Found '+ res.total+' Result' );
                    }
                    self.listLoading = false ;
               });


            },
            getPtype : function(){
                return this.$route.meta.type ;
            }

        },
        mounted() {

                var t = this.type ;
                if( !t && this.$route.meta.type ){
                    t = this.type = this.$route.meta.type ;
                    this.tit = "Add new " ; 
                }else if( !t ){
                    t = this.type = 'news' ;
                    this.tit = "Show All " ; 
                }

                var osetting = {
                    photo : true ,
                    file  : false ,
                    description :true,
                    tinycme:false,
                    taxonomy :true
                };

                var tsetting = settings.posts[t] ;


                if( tsetting ){
                    this.setting = tsetting ;
                    if( tsetting.photo !== false  ){ this.setting.photo = true ; }
                    if( tsetting.description !== false ){ this.setting.description = true ; }
                    if( tsetting.taxonomy !== false ){ this.setting.taxonomy = t ; }
                }else{
                    this.setting = osetting ;
                }

                this.getList(1) ;

        },

    }
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