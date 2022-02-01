<template>
    <div id="taxonomy" class="app-container">
        <h1 class="title">  </h1>

            <el-container>
                <el-header>
                    <h3> {{ tit }}    <el-button type="primary" class="el-left"  v-permission="['manage '+ptype+' search']"  @click="search.visible = true"><i class="el-icon-search"></i> Search For {{ type }}</el-button> </h3>
                    <hr/>
                </el-header>
                <el-main height="">
                    <el-row >
                        <el-col :span="12"  v-permission="['manage '+ptype+' add']">
                            <el-form label-position="left"  label-width="150px">
                                    <el-row>
                                        <el-col :span="22">
                                            <el-form-item label="Name">
                                                <el-input v-model="name" type="text"/>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22" v-show="setting.description">
                                            <el-form-item label="Description">
                                                <el-input v-model="description" type="textarea"/>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="22" v-show="setting.parent">
                                            <el-form-item label="Parent">
                                                <el-select v-model="parent" filterable>
                                                      <el-option v-for="(tax, index) in parents" :key="tax.id" :value="tax.id" :label="tax.name" />      
                                                </el-select>   
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="22">
                                            <el-form-item label="Status">
                                                <el-select v-model="status" value-key="status">
                                                      <el-option value="0" label="Disabled" /> 
                                                      <el-option value="1" label="Enabled" />      
                                                </el-select>   
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22" v-show="setting.photo">
                                            <el-form-item label="Image">
                                                <el-upload class="avatar-uploader" :action="apiUrl" :show-file-list="false" :on-success="handleAvatarSuccess" :before-upload="beforeAvatarUpload">
                                                    <img v-if="photo" :src="photo" class="avatar">
                                                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                                </el-upload>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="22">
                                            <el-form-item>
                                                <el-button type="primary" @click="AddTaxonomy"> Save </el-button>
                                            </el-form-item>
                                        </el-col>

                                    </el-row>
                            </el-form> 
                        </el-col>
                        <el-col :span="12">
                            
    
                            <el-dialog title="Search" :visible.sync="search.visible">
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
                                            <el-form-item label="Status">
                                                <el-select v-model="search.status" placeholder="Please select a status">
                                                    <el-option label="Disabled" value="0"></el-option>
                                                    <el-option label="Enabled" value="1"></el-option>
                                                </el-select>
                                            </el-form-item>  
                                       </el-col>
                                    </el-row>    
                                    
                                    
                                </el-form>
                                <span slot="footer" class="dialog-footer">
                                    <el-button @click="TaxonomySearch(false)">Cancel</el-button>
                                    <el-button type="primary" @click="TaxonomySearch(true)">Search</el-button>
                                </span>
                            </el-dialog>



                              <el-table  ref="dragTable" :data="taxData" style="width: 100%" v-loading="listLoading"  row-key="id" border fit highlight-current-row >
                                    <!-- <el-table-column prop="id" width="50" label="ID"></el-table-column>-->
                                    <el-table-column prop="name" label="Name"></el-table-column>
                                    <el-table-column prop="photo" label="Image" v-if="setting.photo" >
                                      <template slot-scope="scope" >
                                            <img :src="scope.row.photo" width="50" height="50" />
                                      </template>  
                                    </el-table-column>
                                    <el-table-column label="Operations">
                                        <template slot-scope="scope">

                                            <el-tooltip  v-permission="['manage '+ptype+' edit']" content="Edit" placement="top">
                                                <el-button size="mini" type="warning" @click="UpdateTaxonomy(scope.row)" ><i class="el-icon-edit"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip  v-permission="['manage '+ptype+' status']" v-if="scope.row.status == 1" content="Disable" placement="top">
                                                <el-button  size="mini" type="info" @click="ActiveTaxonomy(scope.row , 0)" ><i class="el-icon-error"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip  v-permission="['manage '+ptype+' status']"  v-else-if="scope.row.status == 0" content="Active" placement="top">
                                                <el-button  size="mini" type="success" @click="ActiveTaxonomy(scope.row , 1)" ><i class="el-icon-success"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip content="Delete" placement="top"  v-permission="['manage '+ptype+' delete']">
                                                <el-button size="mini" type="danger" @click="DeleteTaxonomy(scope.row)" ><i class="el-icon-delete"></i></el-button>
                                            </el-tooltip>

                                        </template>
                                    </el-table-column>
                              </el-table>

                                <el-pagination
                                        background
                                        layout="pager"
                                        :total="pagination.total"
                                        :page-size="pagination.per_page"
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
    import permission from '@/directive/permission/index.js' ;
    import Cookies from 'js-cookie' ;
    const TokenKey = 'Admin-Token' ;
    import TaxonomyResource from '@/api/ezuru/taxonomy' ;
    const Taxonomy = new TaxonomyResource() ;
    export default {
        directives : { permission } ,
        data() {
           return {
               "tit" : "Add New "+this.$route.meta.title ,
               "type" : this.$route.params.type ,
               "ptype" : this.getPtype() ,
               "id"   : 0 ,
               "name" : '' ,
               "description" : null ,
               "parent" : "" ,
               "photo" : '' ,
               "status" : '1' ,
               parents : [] ,
               taxData : [] ,
               search : {
                   's' : '' ,
                   'status' : '',
                   'visible' : false
               },
               pagination : {

               },
               setting : {

               },
               apiUrl  : settings.apiUrl+'admin/upload' ,
               parent : this.$route.params.type ,
               listLoading : false,
               countries: [],
               governments: []
           }
        },
        methods : {
            UpdateTaxonomy : function(taxonomy){
                 this.id = taxonomy.id ;
                 this.name = taxonomy.name ;
                 this.description = taxonomy.description ;
                 this.photo = taxonomy.photo ;
                 this.status = taxonomy.status.toString() ;
                 this.parent = taxonomy.parent ;
                 this.type  = taxonomy.type ;
                 this.tit    = 'Update '+taxonomy.type+' No: '+taxonomy.id+ '  >>> '+taxonomy.name;
                 if(this.type == "city"){
                  this.description = parseInt(this.description);
                 }
                 if(this.type == "area"){
                  this.description = parseInt(this.description);
                  this.description_en = parseInt(this.description_en);
                 } 
            },
            DeleteTaxonomy : function(taxonomy){
                var o = this ;

                this.$confirm('This will permanently delete the file. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'danger'
                }).then(() => {

                    fetch(settings.apiUrl+'admin/taxonomy/'+taxonomy.id , {
                        "method" : "DELETE" ,
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                            'Content-Type': 'application/json'
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
            ActiveTaxonomy : function(taxonomy , status){
                var o = this ;

                this.$confirm('This will Update Taxonomy Status. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {

                    fetch(settings.apiUrl+'admin/taxonomy/active/'+taxonomy.id , {
                        "method" : "POST" ,
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                            'Content-Type': 'application/json'
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
            AddTaxonomy : function(){
                if( !this.name || this.name.length < 2 ){
                    this.$message.error('Please Set Vaild Name');
                }else {
                    let o = this ;
                    let tax = {
                        "name"      : this.name ,
                        "id"       : this.id ,
                        "description" : this.description,
                        "parent" : this.parent ,
                        "status" : this.status,
                        "photo"  : this.photo ,
                        "type"   : this.type 
                    }
                    fetch(settings.apiUrl+'admin/taxonomy' , {
                        "method" : "POST" ,
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                            'Content-Type': 'application/json'
                        },
                        body : JSON.stringify( tax )
                    })
                    .then( res => res.json() )
                    .then( function(res){
                            if( res.errors ){
                                o.$message.error( res.message );
                            }else{
                                o.$message.success('Taxonomy Saved');
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
            TaxonomySearch : function(search){

                if( !search ){
                    this.search.s = '' ;
                    this.search.status = '' ;
                }else{
                    this.$message.info('Please Wait Until Search');
                    this.getList( 1 , true , true ) ;
                }

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

                this.listLoading = true;

                let u = settings.apiUrl+'admin/taxonomy/?type='+ this.type+'&page='+page ;

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
                     self.taxData = res.data ;

                     self.pagination.total = res.total ;
                     self.pagination.per_page = res.per_page ;
                     self.pagination.current_page = res.current_page ;

                     if( search && message ){
                        self.$message.success('We Found '+ res.total+' Result' );
                    }
                    self.listLoading = false;
               });


            },
            getParents : function(){
                if( !this.type ){
                    this.type = this.$route.params.type ;
                    this.setting.parent = this.$route.params.type ;
                }

                if( this.setting.parent === true ){
                    this.setting.parent = this.type ;
                }

                let self = this ;
                fetch(settings.apiUrl+'admin/taxonomy/'+ this.setting.parent )
               .then( res => res.json() )
               .then( function(res){
                     self.parents = res ;
               });
               
            }
            ,
            getPtype : function(){
        
                    var ppp = {
                        "category" : "category" ,
                        "contact" : "contact category" ,
                        "faq" : "faq category" ,
                        "careers" : "careers departments" ,
                    }
                    
                    var t = this.type ;
                    if( !t && this.$route.meta.type ){
                        t = this.type = this.$route.meta.type ;
                    }else if( !t ){
                        t = this.type = 'category' ;
                        
                    }

                    if( ppp[t] ){
                        return 'user' ; // ppp[t]
                    }

                    return 'user' ; // category

            }
                
        },
        async mounted() {
                
               /*
                * Set Taxonomy Type Settings
                */
                var t = this.type ;

                if( !t && this.$route.meta.type ){
                    t = this.type = this.$route.meta.type ;
                    this.tit = "Add New "+ this.$route.meta.title ; 
                }else if( !t ){
                    t = this.type = 'category' ;
                }

                var osetting = {
                    photo : true ,
                    description :true,
                    parent : true
                };

                var tsetting = settings.taxonomy[t] ;

                if( tsetting ){
                    this.setting = tsetting ;
                    if( tsetting.photo !== false  ){ this.setting.photo = true ; }
                    if( tsetting.description !== false ){ this.setting.description = true ; }
                    if( tsetting.parent !== false && tsetting.parent !== true ){ this.setting.parent = tsetting.parent ; }else if( tsetting.parent !== false ){ this.setting.parent = true ; }
                }else{
                    this.setting = osetting ;
                }

                this.getList(1) ;
                this.getParents() ;
                if(['city','area'].includes(this.type)){
                  this.countries = await Taxonomy.select( {'type' : 'country' } ) ;
                }
                if(['area'].includes(this.type)){
                  this.governments = await Taxonomy.select( {'type' : 'government' } ) ;
                }                
        },

        watch : {
            "description": async function(country) {
                if(['city','area'].includes(this.type)){
                    if(this.type == "city"){
                    this.parents = await Taxonomy.select( {'type' : 'government' , 'parent' :  country} ) ;
                    let isFound = this.parents.filter(gov => this.parent == gov.id) ;
                    if(!isFound.length){
                        this.parent = null;
                }
                    }else {
                    this.governments = await Taxonomy.select( {'type' : 'government' , 'parent' :  country} ) ;
                    let isFound = this.governments.filter(gov => this.description_en == gov.id) ;
                    if(!isFound.length){
                        this.description_en = null;
                }
                    }
                }

            },
            "description_en": async function() { 
                if(this.type == "area"){
                   this.parents = await Taxonomy.select( {'type' : 'city' , 'parent' :  this.description_en} ) ;
                    let isFound = this.parents.filter(city => this.parent == city.id) ;
                    if(!isFound.length){
                        this.parent = null;
                }
                }
 
            },
            "tax_city": function() {
              this.getArea();
            }
        } 

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