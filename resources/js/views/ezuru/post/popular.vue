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
                            <el-form label-position="left"  label-width="150px" v-loading="loading">
                                    <el-row>
                                        <el-col :span="22">
                                            <el-form-item label="Title">
                                                <el-input v-model="name" type="text"/>
                                            </el-form-item>
                                        </el-col>
                                
                                        <div>
                                            <el-col :span="22">
                                                <el-form-item label="Category">
                                                    <el-select v-model="description.category" remote placeholder="Category ... type to search" @change="getSubjects" >
                                                            <el-option v-for="v in category_list" :key="v.id" :label="v.name" :value="parseInt(v.id)" />
                                                    </el-select>
                                                </el-form-item>
                                            </el-col>
                                            <el-col :span="22">
                                                <el-form-item label="Subject">
                                                    <el-select v-model="description.subject" placeholder="Select Subject ..." @change="getSkill"  >
                                                            <el-option v-for="v in subject_list" :key="v.id" :label="v.name" :value="parseInt(v.id)" />
                                                    </el-select>
                                                </el-form-item>
                                            </el-col>
                                            <el-col :span="22">
                                                <el-form-item label="Skill">
                                                    <el-select v-model="description.skill" placeholder="Select Skill"  >
                                                            <el-option v-for="v in skill_list" :key="v.id" :label="v.name" :value="parseInt(v.id)" />
                                                    </el-select>
                                                </el-form-item>
                                            </el-col>
                                            <el-col :span="22">
                                                <el-form-item label="Description">
                                                    <el-input v-model="description.title" ></el-input>
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
                                        <el-col :span="22"  v-if="setting.photo">
                                            <el-form-item label="Image">
                                                <el-upload class="avatar-uploader" :action="apiUrl" :show-file-list="false" :on-success="handleAvatarSuccess" :before-upload="beforeAvatarUpload">
                                                    <img v-if="photo" :src="photo" class="avatar">
                                                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                                </el-upload>
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
                                    <!-- <el-table-column prop="id" width="50" label="ID"></el-table-column>-->
                                    <el-table-column prop="title" label="Title"></el-table-column>
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

    const TokenKey = 'Admin-Token' ;

    import TaxonomyResource from '@/api/ezuru/taxonomy' ;
    const Taxonomy = new TaxonomyResource() ;


    export default {
        directives : { permission } ,
        data() {
           return {
               "tit" : "Show "+this.$route.params.type ,
               "type" : this.$route.params.type ,
               "ptype" : this.getPtype() ,
               "id"   : 0 ,
               "name" : '' ,
               description : {
                   skill : '' ,
                   category : '' ,
                   subject : '' ,
                   title : "" ,
               } ,
               "taxonomy_id" : "" ,
               "user_id" : "" ,
               "photo" : '' ,
               "status" : '' ,
               taxonomies : [] ,
               postData : [
                   
               ] ,
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

               },
               skill_list : [],
               category_list:[],
               subject_list : [] ,
               load : false
           }
        },
        components : { Tinymce } ,
        methods : {
            async getCategory() {
                    this.loading = true;
                    let self = this ;
                    this.category_list = await Taxonomy.select( { 'parent' : 0 , 'type' : 'category' } ) ; 
                    this.subject_list = [] ;
                    this.skill_list = [] ;
                    this.description.subject = '' ;
                    this.description.skill = '' ;
                    this.loading = false;  
            },
            async getSubject(){
                    this.loading = true;
                    let self = this ;
                    this.subject_list = await Taxonomy.select( { 'parent' : this.description.category , 'type' : 'subject' } ) ;
                    this.skill_list = [] ;
                    this.description.skill = '' ;
                    //this.description.subject = '' ;
                    this.loading = false; 
            },
            async getSkill() {
                    this.loading = true;
                    let self = this ;
                    this.skill_list = await Taxonomy.select( { 'parent' : this.description.subject , 'type' : 'skill' } ) ; 
                    this.description.skill = '' ;
                    this.loading = false;  
            },
            UpdatePost : function(Post){
                 
                 this.id = Post.id ;
                 this.name = Post.title ;
                 var ocit = JSON.parse(Post.description) ;
                 this.description = ocit ;
                 this.photo = Post.photo ;
                 this.status = Post.status.toString() ;
                 this.taxonomy_id = Post.taxonomy_id ;
                 this.type  = Post.type ;
                 this.tit    = 'Update '+Post.type+' No: '+Post.id+ '  >>> '+Post.title;
                 this.addNew = true;

                var o = this ;
                //o.$refs.description.setContent( o.description ) ;
                //o.$refs.description_en.setContent( o.description_en ) ;
                var skill = this.description.skill ;
                var subject = this.description.subject ;
                o.getSubject();
                o.getSkill();
                o.load =  true ;
                setTimeout(function(){
                    o.description.subject = subject ;
                    o.description.skill = skill ;
                    o.load =  false ;
                }, 1000 );
                
            },
            DeletePost : function(Post){
                var o = this ;

                this.$confirm('This will permanently delete the Post. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'danger'
                }).then(() => {

                    fetch(settings.apiUrl+'admin/post/'+Post.id , {
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

                this.$confirm('This will Update Post Status. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {

                    fetch(settings.apiUrl+'admin/post/active/'+Post.id , {
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
                        "type"   : this.type
                    }
                    fetch(settings.apiUrl+'admin/post' , {
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
                                o.$message.success('Post Updated');
                                o.getList() ;
                                o.id = 0 ;
                                o.name = "" ;
                                o.description = "" ;
                                o.photo = "" ;
                                o.status = '' ;
                                o.parent = '' ;
                                o.tit    = 'Add New : '+o.type ;
                            }
                    });


                }
            },
            handleAvatarSuccess(res, file) {
                this.photo = file.response ;
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

                let u = settings.apiUrl+'admin/post/?type='+ this.type+'&page='+page ;

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
                if( !this.type ){
                    this.type = this.$route.params.type ;
                }
                let self = this ;
                fetch(settings.apiUrl+'admin/taxonomy/'+ this.type , { 
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
            toggleAdd : function(){
                var o = this ;
                if( this.setting.tinymce ){
                   // o.$refs.description.setContent( '' ) ;
                   //  o.$refs.description_en.setContent( '' ) ;
                }
                
                
                 o.id = 0 ;
                 o.name = '' ;
                 o.description = {
                    city : '' ,
                    title : "" ,
                } ;
                 o.photo = '' ;
                 o.status = '0' ;
                 o.taxonomy_id = '' ;
                 o.subject_list = [] ;
                 o.skill_list = [] ;

                 this.getSubject();
                 console.log('Subjects Load') ;

                if( this.addNew === false ){
                    return this.addNew = true ;
                }
                this.addNew = false;
            },
            getPtype : function(){
        // Set Premission Type
                    var ppp = {
                        "news" : "news" ,
                        "pages" : "pages" ,
                        "forum" : "Forum Post" ,
                        "elearn" : "E-learn Post" ,
                        "e-learn" : "E-learn Post" ,
                    }
                    
                    var t = this.type ;
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
                this.getTaxonomy() ;

                this.getCategory() ;

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