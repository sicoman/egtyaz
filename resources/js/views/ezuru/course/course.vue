<template>
    <div id="post" class="app-container">
            <el-container>
                <el-header>
                    <h3> {{ tit }} <el-button type="primary" v-permissionx="['manage '+ptype+' add']" class="el-left" @click="toggleAdd()"><i class="el-icon-edit-outline"></i> Add New </el-button> </h3>
                    <hr/>
                </el-header>
                <el-main height="" >

                    

                    <el-row >
                        <el-col :span="24" v-show="addNew">
                            <el-form label-position="left"  label-width="150px">

<el-row>
                        <el-col :span="13">
                            <el-col :span="24">
                                <el-form-item label="Title">
                                    <el-input v-model="name" style="width:80%" type="text"/>
                                </el-form-item>
                            </el-col>
                            <el-col :span="24" v-show="setting.category!== false">
                                <el-form-item label="Category" v-if="setting.taxonomy">
                                    <el-select v-model="taxonomy_id"   style="width:80%"  filterable>
                                            <el-option v-for="(tax, index) in taxonomies" :key="tax.id" :value="tax.id" :label="tax.name" />
                                    </el-select>
                                </el-form-item>
                            </el-col>
                            <el-col :span="24">
                                <el-form-item label="Status">
                                    <el-select v-model="status"  style="width:80%" >
                                            <el-option value="0" label="Disabled" />
                                            <el-option value="1" label="Enabled" />
                                    </el-select>
                                </el-form-item>
                            </el-col>
                            <el-col :span="24" v-if="setting.author">
                                <el-form-item label="Author" >
                                    <el-select v-model="author_id"  style="width:80%"  filterable remote reserve-keyword placeholder="Please enter a keyword" :remote-method="getUsers">
                                            <el-option v-for="(user, index) in users" :key="user.id" :value="user.id" :label="user.name" />
                                    </el-select>
                                </el-form-item>
                            </el-col>
                            <el-col :span="24">
                                    <el-form-item label="Price">
                                        <el-input v-model="price"  style="width:80%"  />
                                    </el-form-item>
                            </el-col>

                            
                        </el-col>
                        <el-col :span="1">&nbsp;</el-col>
                        <el-col :span="10">
                                <el-col :span="24"  v-if="setting.photo">
                                    <el-form-item label="Image">
                                        <el-upload class="avatar-uploader" :action="apiUrl" :show-file-list="false" :on-success="handleAvatarSuccess" :before-upload="beforeAvatarUpload">
                                            <img v-if="photo" :src="photo" class="avatar">
                                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                        </el-upload>
                                    </el-form-item>
                                </el-col>

                                <el-col :span="24"  v-if="setting.file">
                                    <el-form-item label="File">
                                        <el-upload class="avatar-uploader" :action="apiUrl" :show-file-list="false" :on-success="handleFileSuccess" :before-upload="beforeFileUpload">
                                                <i class="el-icon-plus avatar-uploader-icon"></i>
                                        </el-upload>
                                        <el-input type="text" v-model="file" />
                                    </el-form-item>
                                </el-col>
                        </el-col>
                    </el-row>
                    <el-row>
                        <div :span="22" v-if="setting.tinymce || 1 == 0">
                                <el-col :span="22">
                                    <el-form-item label="Description">
                                        <tinymce :height="300"  style="width:90%"  v-model="description"  ref="description" />
                                    </el-form-item>
                                </el-col>
                            </div>
                            <div v-else >
                                <el-col :span="22">
                                    <el-form-item label="Description">
                                        <el-input type="textarea"  style="width:90%"  :height="300" v-model="description"  ref="description" />
                                    </el-form-item>
                                </el-col>
                                
                            </div>
                    </el-row>
                    <el-row>
                        <el-col :span="24">
                            <hr />
                        </el-col>

                        <el-col :span="24">
                                <el-button type="warning" icon="el-icon-plus" @click="items.push({'title' : '' , 'is_free' : '0'});">Add Item</el-button>

                                <el-table :data="items" style="width: 100%">

                                    <el-table-column prop="text" label="Item Title" >
                                            <template slot-scope="scope">
                                                <el-input  v-model="scope.row.title" ></el-input>
                                            </template>
                                    </el-table-column>

                                    <el-table-column prop="text" label="Is Free" width="70">
                                            <template slot-scope="scope">
                                                <el-switch v-model="scope.row.is_free" active-color="#0000ff" inactive-color="#cccccc" :active-value="1" :inactive-value="0"></el-switch>
                                            </template>
                                    </el-table-column>

                                    <el-table-column prop="text" label="Delete" width="70">
                                            <template slot-scope="scope">
                                                        <el-button type="danger" icon="el-icon-delete" @click="items.splice(scope.$index, 1);" circle></el-button>
                                            </template>
                                    </el-table-column>
                                    
                                </el-table>
                        </el-col>

                            <el-col :span="24">
                            <hr />
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
                            <el-button type="warning" v-permissionx="['manage '+ptype+' search']" @click="search.visible = true"><i class="el-icon-search"></i> Search For {{ type }}</el-button>    
                            <el-dialog title="Search" :visible.sync="search.visible" >
                                <el-form label-position="top" :model="search">
                                    <el-row>
                                        <el-col :span="10">
                                            <el-form-item label="Search Text">
                                                <el-input v-model="search.s" autocomplete="off"></el-input>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="2">
                                            &nbsp;
                                        </el-col>
                                        <el-col :span="10">
                                            <el-form-item label="Status" >
                                                <el-select v-model="search.status" placeholder="Please select a status">
                                                    <el-option label="Disabled" value="0"></el-option>
                                                    <el-option label="Enabled" value="1"></el-option>
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
                                    <el-table-column prop="id" width="50" label="ID"></el-table-column>
                                    <el-table-column prop="title" label="Title"></el-table-column>
                                    <el-table-column  v-if="setting.author" prop="" label="Author">
                                        <template slot-scope="scope">
                                            <p v-if="scope.row.author.hasOwnProperty('name')">{{ scope.row.author.id +' - '+ scope.row.author.name }}</p>
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="photo" label="Image"  v-if="setting.photo">
                                      <template slot-scope="scope">
                                            <img :src="scope.row.photo" width="50" height="50" />
                                      </template>
                                    </el-table-column>
                                    <el-table-column  label="Category" v-if="setting.taxonomy">
                                            <template slot-scope="scope">
                                                    <span v-if="scope.row.category" v-html="scope.row.category.name"></span>
                                            </template>
                                    </el-table-column>
                                    <el-table-column prop="created_at" label="Date"></el-table-column>
                                    <el-table-column label="Operations">
                                        <template slot-scope="scope">
                                            <el-tooltip content="Edit" placement="top" v-permissionx="['manage '+ptype+' edit']">
                                                <el-button size="mini" type="warning" @click="UpdatePost(scope.row)" ><i class="el-icon-edit"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip v-if="scope.row.status == 1" content="Disable" placement="top" v-permissionx="['manage '+ptype+' status']">
                                                <el-button  size="mini" type="info" @click="ActivePost(scope.row , 0)" ><i class="el-icon-error"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip v-else-if="scope.row.status == 0" content="Active" placement="top" v-permissionx="['manage '+ptype+' status']">
                                                <el-button  size="mini" type="success" @click="ActivePost(scope.row , 1)" ><i class="el-icon-success"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip content="Delete" placement="top" v-permissionx="['manage '+ptype+' delete']">
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

    const TokenKey = 'Admin-Token'  ;

    import PostsResource from '@/api/ezuru/courses' ;
    const Post = new PostsResource() ;

    export default {
        directives : { permission } ,
        data() {
           return {
               "tit" : "Show Courses" ,
               "type" : "course" ,
               "ptype" : this.getPtype() ,
               "id"   : 0 ,
               "name" : '' ,
               "description" : '' ,
               "taxonomy_id" : "" ,
               "author_id" : "" ,
               "photo" : '' ,
               "file"  : '' ,
               "price" : '' ,
               "status" : '' ,
               taxonomies : [] ,
               users : [] ,
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
                   'tinymce' : true ,
               } ,
               items : [
                   {
                       'title' : '' ,
                       'is_free' : 1 ,
                   },
                   {
                       'title' : '' ,
                       'is_free' : 1 ,
                   },
                   {
                       'title' : '' ,
                       'is_free' : 1 ,
                   },
                   {
                       'title' : '' ,
                       'is_free' : 1 ,
                   },
                ],
           }
        },
        components : { Tinymce } ,
        methods : {
            UpdatePost : function(Post){
                 this.id = Post.id ;
                 this.name = Post.title ;
                 this.description = Post.description ;
                 this.photo = Post.photo ;
                 this.file  = Post.file ;
                 this.status = Post.status.toString() ;
                 this.taxonomy_id = Post.taxonomy_id ;
                 this.type  = Post.type ;
                 this.parent  = Post.parent ;
                 this.author_id = Post.author_id ;
                 this.price = Post.price ;
                 this.tit    = 'Update '+Post.type+' No: '+Post.id+ '  >>> '+Post.title;
                 this.addNew = true;
                 this.items  = Post.items ;

                var o = this ;
                this.getUsers();
            }, 
            DeletePost : function(Post){
                var o = this ;

                this.$confirm('This will permanently delete the Course. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'danger'
                }).then(() => {

                    fetch(settings.apiUrl+'admin/course/'+Post.id , {
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
                                o.$message.error( 'Unable to Delete '+o.type );
                            }else{
                                o.$message.success('Deleted Succefully');
                                o.getList() ;
                            }
                    });

                }).catch(() => {
                    this.$message({ type: 'info', message: 'Delete canceled'});
                });
            } ,
            ActivePost : function(Post , status){
                var o = this ;

                this.$confirm('This will Update Course Status. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {

                    fetch(settings.apiUrl+'admin/course/active/'+Post.id , {
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
                                o.$message.error( 'Unable to Update '+o.type );
                            }else{
                                o.$message.success('Updated Succefully');
                                o.getList() ;
                            }
                    });

                }).catch(() => {
                    this.$message({ type: 'info', message: 'Update canceled'});
                });

            } ,
            AddPost : function(){
                if( !this.name || this.name.length < 3 ){
                    this.$message.error('Please Set Vaild Title');
                }else {
                    let o = this ;
                    let post = {
                        "title"      : this.name ,
                        "id"       : this.id ,
                        "description" : this.description,
                        "taxonomy_id" : this.taxonomy_id ,
                        "status" : this.status,
                        "photo"  : this.photo ,
                        "file"  : this.file ,
                        "author_id" : this.author_id ,
                        "price" : this.price ,
                        "items" : this.items ,
                        "list" : this.list 
                    }
                    fetch(settings.apiUrl+'admin/course' , {
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
                                o.$message.success('Course Updated');
                                o.getList() ;
                                
                                o.id = 0 ;
                                o.name = "" ;
                                o.description = "" ;
                                o.photo = "" ;
                                o.list = [{'title' : '' , 'status' : 1 },{'title' : '', 'status' : 1}, {'title' : '','status' : 1},{ 'title' : '' , 'status' : 1 } ] ;
                                o.status = '' ;
                                o.price = '' ;
                                o.tit    = 'Add New : Course' ;
                                 o.toggleAdd() ;
                                
                            }
                    });


                }
            },
            handleAvatarSuccess(res, file) {
                this.photo = file.response ;
            },
            handleFileSuccess(res, file) {
                this.file = file.response ;
            },
            beforeFileUpload(file) {
            
                const isLt2M = file.size / 1024 / 1024 < 50;
                
                if (!isLt2M) {
                    this.$message.error('Photo picture size can not exceed 50 MB!');
                }

                return isLt2M ;
            },
            beforeAvatarUpload(file) {
                
                const isJPG = ( file.type === 'image/jpeg' || file.type === 'image/png' || file.type === 'image.gif' || file.type == 'image/svg+xml' );
                const isLt2M = file.size / 1024 / 1024 < 10;

                if (!isJPG) {
                    this.$message.error('Photo picture must be Image format!');
                }
                if (!isLt2M) {
                    this.$message.error('Photo picture size can not exceed 2MB!');
                }

                return isJPG && isLt2M;
            },
            PostSearch : function(search){

                if( !search ){
                    this.search.s = '' ;
                    this.search.status = '' ;
                }else{
                    this.$message.info('Please Wait Until Search');
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

                let u = settings.apiUrl+'admin/course/?type='+ this.type+'&page='+page ;

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
                        self.$message.success('We Found '+ res.total+' Result' );
                    }
                    self.listLoading = false ;
               });


            },
            getTaxonomy : function(){
                
                let self = this ;
                fetch(settings.apiUrl+'admin/taxonomy/courses' , { 
                method: 'get', 
                headers: new Headers({
                    'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                }) 
                })
               .then( res => res.json() )
               .then( function(res){
                     self.taxonomies = res ;
               });
            },
            getUsers : function(s=''){
                if( !this.type ){
                    this.type = this.$route.params.type ;
                }
                let self = this ;
                fetch(settings.apiUrl+'users/select?s='+s , { 
                method: 'get', 
                headers: new Headers({
                    'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                }) 
                })
               .then( res => res.json() )
               .then( function(res){
                     self.users = res ;
               });

            },
            toggleAdd : function(){
                var o = this ;

                 o.id = 0 ;
                 o.name = '' ;
                 o.description = '' ;
                 o.photo = '' ;
                 o.file = '' ;
                 o.status = '0' ;
                 o.price = '' ;
                 o.taxonomy_id = '' ;

                if( this.addNew === false ){
                    return this.addNew = true ;
                }
                this.addNew = false;
            },
            
            getPtype : function(){
        // Set Premission Type
                    var ppp = {
                        "Course" : "Course" ,
                    }
                    
                    var t = "course" ;
                    if( !t && this.$route.meta.type ){
                        t = this.type = this.$route.meta.type ;
                        this.tit = "Add New "+ t ; 
                    }else if( !t ){
                        t = this.type = 'news' ;
                        this.tit = "Show "+ t ; 
                    }

                    return ppp[t] ;

            }

        },
        mounted() {

                var t = this.type ;
                if( !t && this.$route.meta.type ){
                    t = this.type = this.$route.meta.type ;
                    this.tit = "Add New "+ t ; 
                }else if( !t ){
                    t = this.type = 'news' ;
                    this.tit = "Show "+ t ; 
                }


                var osetting = {
                    photo : true ,
                    file : true ,
                    description :true,
                    tinycme: true,
                    taxonomy :"courses",
                    author : true ,
                    parent : false
                };

                var tsetting = settings.posts[t] ;


                if( tsetting ){
                    this.setting = tsetting ;
                    if( tsetting.photo !== false  ){ this.setting.photo = true ; }
                    if( tsetting.description !== false ){ this.setting.description = true ; }
                    if( tsetting.taxonomy !== false ){ this.setting.taxonomy = t ; }
                    if( tsetting.author !== false ){ this.setting.author = true ; }
                    if( tsetting.parent !== false ){ this.setting.parent = tsetting.parent ; }
                }else{
                    this.setting = osetting ;
                }
                this.setting.tinycme = true ;

                this.getList(1) ;
                this.getTaxonomy() ;
               
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