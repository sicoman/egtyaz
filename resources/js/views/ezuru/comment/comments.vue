<template>
    <div id="post" class="app-container">
            <el-container>
                <el-header>
                    <h3> {{ tit }} </h3>
                    <hr/>
                </el-header>
                <el-main height="" >
                    <el-row >
                        <el-col :xs="24" :sm="24" :lg="24" v-show="addNew">
                            <el-form label-position="right"  label-width="150px">
                                    <el-row>
                                        
                                        <div>
                                            <el-col :span="22">
                                                <el-form-item label="Comment">
                                                    <el-input type="textarea" :height="300" v-model="comment"  ref="description" />
                                                </el-form-item>
                                            </el-col>
                                        </div>


                                        <el-col :span="22">
                                            <el-form-item label="Status">
                                                <el-select v-model="status">
                                                      <el-option value="0" label="Disabled" />
                                                      <el-option value="1" label="Enabled" />
                                                </el-select>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="22">
                                            <el-form-item>
                                                <el-button type="primary" @click="AddPost"> Save </el-button>
                                            </el-form-item>
                                        </el-col>

                                    </el-row>
                            </el-form>
                        </el-col>
                        <el-col :span="24" v-if="addNew == false">
                            <el-button type="warning" v-permission="['manage comments']" @click="search.visible = true"><i class="el-icon-search"></i> البحث عن التعليقات</el-button>    
                            <el-button v-if="multipleSelection.length > 0" type="danger" @click="deleteSelection()"> Delete Selected </el-button>
                            <br /> <br />
                            <el-dialog title="Search Comments" :visible.sync="search.visible" >
                                <el-form label-position="top" :model="search">
                                    <el-row>
                                        <el-col :span="12">
                                            <el-form-item label="Text">
                                                <el-input v-model="search.s" autocomplete="off"></el-input>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="Status" >
                                                <el-select v-model="search.status" placeholder="Please Select Status">
                                                    <el-option label="Enabled" value="0"></el-option>
                                                    <el-option label="Disabled" value="1"></el-option>
                                                </el-select>
                                            </el-form-item>
                                       </el-col>
                                       <el-col :span="12">
                                            <el-form-item label="Sort" >
                                                <el-select v-model="search.order" placeholder="Select Sort Type">
                                                    <el-option label="Older to New" value="ASC"></el-option>
                                                    <el-option label="New to older" value="DESC"></el-option>
                                                </el-select>
                                            </el-form-item>
                                       </el-col>
                                       <el-col :span="12">
                                            <el-form-item label="Types" >
                                                <el-select v-model="search.type" placeholder="Please Select">
                                                    <el-option label="All" value=""></el-option>
                                                    <el-option label="Forum" value="forum"></el-option>
                                                    <el-option label="News" value="news"></el-option>
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

                            

                              <el-table :data="postData" style="width: 100%" v-loading="listLoading"  row-key="id" border fit highlight-current-row @selection-change="handleSelectionChange">
                                    <el-table-column type="selection" width="55"> </el-table-column>
                                    <el-table-column v-show="false" label="Type">
                                            <template slot-scope="scope">
                                                <div>
                                                    <span v-if="scope.row.post && scope.row.post.type == 'news'">News</span>
                                                    <span v-else>Forum</span>
                                                </div> 
                                            </template>
                                    </el-table-column>
                                    <el-table-column  label="Forum Post">
                                            <template slot-scope="scope">
                                                <div v-if="scope.row.hasOwnProperty('post') && scope.row.post">
                                                    <a v-if="scope.row.post && scope.row.post.type == 'news'"  v-html="scope.row.post.title" :href="news+'/'+scope.row.post.key_id+'.html'"></a>
                                                    <a v-else  :href="Forum+'/'+scope.row.post.key_id+'.html'" v-html="scope.row.post.title"></a>
                                                </div>
                                            </template>
                                    </el-table-column>
                                    <el-table-column label="Name">
                                            <template slot-scope="scope">
                                                <div v-if="scope.row.hasOwnProperty('author') && scope.row.author">
                                                    <p>{{ scope.row.author.name }}</p>
                                                </div>
                                            </template>
                                    </el-table-column>

                                    <el-table-column label="Email">
                                            <template slot-scope="scope">
                                                <div v-if="scope.row.hasOwnProperty('author') && scope.row.author">
                                                    <p>{{ scope.row.author.email }}</p>
                                                </div>
                                            </template>
                                    </el-table-column>

                                    <el-table-column prop="ip" label="IP"></el-table-column>
                                    <el-table-column prop="comment" label="Comment"></el-table-column>
                                    
                                    <el-table-column prop="created_at" label="Created at"></el-table-column>
                                    <el-table-column label="Options">
                                        <template slot-scope="scope">

                                            <el-tooltip content="Edit" placement="top" v-permissionx="['manage comments']">
                                                <el-button size="mini" type="warning" @click="UpdatePost(scope.row)" ><i class="el-icon-edit"></i></el-button>
                                            </el-tooltip>
                                    
                                            <el-tooltip v-if="scope.row.status == 1" content="Disable" placement="top" v-permissionx="['manage comments']">
                                                <el-button  size="mini" type="info" @click="ActivePost(scope.row , 0)" ><i class="el-icon-error"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip v-else-if="scope.row.status == 0" content="Active" placement="top" v-permissionx="['manage comments']">
                                                <el-button  size="mini" type="success" @click="ActivePost(scope.row , 1)" ><i class="el-icon-success"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip content="Delete" placement="top" v-permissionx="['manage comments']">
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
               "tit" : "Show Comments" ,
               "id"   : 0 ,
               "comment" : '' ,
               "post_id" : "" ,
               "ip" : '' ,
               "status" : '' ,
               postData : [] ,
               search : {
                   's' : '' ,
                   'status' : '',
                   'visible' : false,
                   'order' : 'DESC',
                   'type' : '' ,
                   'post_id' : ""
               },
               pagination : {

               },
               addNew : false ,
               apiUrl  : settings.apiUrl+'admin/upload',
               Front  : settings.frontend ,
               listLoading : false,
               setting : {

               },
               multipleSelection: [] ,
           }
        },
        components : { Tinymce } ,
        methods : {
            deleteSelection() {
                var o = this ;

                var ids = '' ;

                this.multipleSelection.forEach( (post) => {
                    ids += post.id+',' ;
                }) ;                

                this.$confirm('Do you realy want to delete selected items', 'Warning', {
                confirmButtonText: 'Accept',
                cancelButtonText: 'Cancel',
                type: 'danger'
                }).then(() => {

                    fetch(settings.apiUrl+'admin/comment/'+ids , {
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
                                o.$message.success('Deleted Succefully');
                                o.getList() ;
                            }
                    });

                }).catch(() => {
                    this.$message({ type: 'info', message: 'Delete canceled'});
                });
            },
            handleSelectionChange(val) {
                this.multipleSelection = val;
            } ,
            UpdatePost : function(Post){
                 this.id = Post.id ;
                 this.comment = Post.comment ;
                 this.ip = Post.ip ;
                 this.status = Post.status.toString() ;
                 this.post_id = Post.post_id ;
                 this.tit    = 'Edit /  No: '+Post.id+ '  >>> '+Post.name;
                 this.addNew = true;
                var o = this ;
            },
            DeletePost : function(Post){
                var o = this ;

                this.$confirm('Do you want to delete ? ؟؟', 'Warning', {
                confirmButtonText: 'Accept',
                cancelButtonText: 'Cancel',
                type: 'danger'
                }).then(() => {

                    fetch(settings.apiUrl+'admin/comment/'+Post.id , {
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
                                o.$message.error( 'Unable to delete' );
                            }else{
                                o.$message.success('Deleted Succefully');
                                o.getList() ;
                            }
                    });

                }).catch(() => {
                    this.$message({ type: 'info', message: 'Delete Canceled'});
                });
            } ,
            ActivePost : function(Post , status){
                var o = this ;

                this.$confirm('Do you want to update Comment ؟', 'Warning', {
                confirmButtonText: 'Accept',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {

                    fetch(settings.apiUrl+'admin/comment/active/'+Post.id , {
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
                                o.$message.error( 'Unable to update status' );
                            }else{
                                o.$message.success('Updated Succefully');
                                o.getList() ;
                            }
                    });

                }).catch(() => {
                    this.$message({ type: 'info', message: 'Update Canceled'});
                });

            } ,
            AddPost : function(){
                if( !this.comment || this.comment.length < 3 ){
                    this.$message.error('Please set comment');
                }else {
                    let o = this ;
                    let post = {
                        "id"       : this.id ,
                        "comment" : this.comment ,
                        "status"  : this.status ,
                        "post_id" : this.post_id
                    }
                    fetch(settings.apiUrl+'admin/comment' , {
                        "method" : "POST" ,
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                        },
                        body : JSON.stringify( post )
                    })
                    .then( res => res.json() )
                    .then( function(res){
                            if( res.errors ){
                                o.$message.error( res.message );
                            }else{
                                o.$message.success('Updated');
                                o.getList() ;
                                o.id = 0 ;
                                o.ip = "" ;
                                o.status = '' ;
                                o.tit    = '' ;
                                o.toggleAdd() ;
                            }
                    });


                }
            },
            PostSearch : function(search){

                if( !search ){
                    this.search.s = '' ;
                    this.search.status = '' ;
                }else{
                    this.$message.info('Please wait');
                }
                this.getList( 1 , true , true ) ;

                this.search.visible = false ;
            },
            gotoPage : function(a){
                this.getList(a , true , false) ;
            },
            getList : function( page = 1 , search = false , message = false ){
                
                let self = this ;

                self.listLoading = true ;
                
                let ty = this.type ;
                
                if( this.search.type != '' ) {
                    ty = this.search.type ;
                }

                let u = settings.apiUrl+'admin/comment/?type='+ ty +'&page='+page+'&order='+self.search.order ;

                if( search && message ){
                    u = u+'&s='+self.search.s+'&status='+self.search.status ;
                }

                if( this.search.post_id ){
                    u = u+'&post_id='+this.search.post_id ;
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
                        self.$message.success('We Founded '+ res.total+' Result' );
                    }
                    self.listLoading = false ;
               });


            },
            toggleAdd : function(){
                var o = this ;
                 o.id = 0 ;
                 o.name = '' ;
                 o.comment = '' ;
                 o.email = '' ;
                 o.ip  = '' ;
                 o.status = '0' ;
                 o.post_id = 0 ;

                if( this.addNew === false ){
                    return this.addNew = true ;
                }
                this.addNew = false;
            },
            getPtype : function(){
                return this.$route.meta.type ;
            }

        },
        mounted() {
            if( this.$route.query.hasOwnProperty('post') ) {
                this.search.post_id = this.$route.query.post ;
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