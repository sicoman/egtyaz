<template>
    <div id="Package" class="app-container">
        <h1 class="title">  </h1>

            <el-container>
                <el-header>
                    <h3> {{ tit }}    <el-button type="primary" class="el-left"  v-permission="['manage '+ptype+' search']"  @click="search.visible = true"><i class="el-icon-search"></i> Search For {{ type }}</el-button> </h3>
                    <hr/>
                </el-header>
                <el-main height="">
                    <el-row >
                        <el-col :span="12" > <!--  v-permission="['manage packages add']" -->
                            <el-form label-position="left"  label-width="150px">
                                    <el-row>
                                        <el-col :span="22">
                                            <el-form-item label="Name">
                                                <el-input v-model="name" type="text"/>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="22">
                                            <el-form-item label="Price">
                                                <el-input-number v-model="price" />
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22">
                                            <el-form-item label="Discount Type">
                                                <el-select v-model="sale_type">
                                                      <el-option value="" label="" />      
                                                      <el-option value="percent" label="Percent" />      
                                                      <el-option value="fixed" label="Fixed" />      
                                                </el-select> 
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22">
                                            <el-form-item label="Discount Amount">
                                                <el-input-number v-model="sale_amount" />
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22">
                                            <el-form-item label="Description">
                                                <el-input v-model="description" type="textarea"/>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="22" >
                                            <el-form-item label="Period">
                                                <el-select v-model="period" filterable>
                                                      <el-option v-for="(v , k) in periods" :key="k" :value="k" :label="v" />      
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

                                        <el-col :span="22">
                                            <el-form-item label="Points">
                                                <el-input v-model="points"/>
                                                <br />
                                                <small> This package = ??? points  . Leave 0 from no payment with points</small>
                                            </el-form-item>
                                        </el-col>


                                        <el-col :span="22">
                                            <el-form-item label="Roles">
                                                <div>
                                                     <div v-for="( role , key ) in packagesRoles" >  
                                                         <el-switch v-model="roles[key]"></el-switch> {{ role }}
                                                    </div> 
                                                </div>    
                                            </el-form-item>
                                        </el-col>

                             
                                        <el-col :span="22">
                                            <el-form-item>
                                                <el-button type="primary" @click="AddPackage"> Save </el-button>
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
                                    <el-button @click="PackageSearch(false)">Cancel</el-button>
                                    <el-button type="primary" @click="PackageSearch(true)">Search</el-button>
                                </span>
                            </el-dialog>



                              <el-table  ref="dragTable" :data="taxData" style="width: 100%" v-loading="listLoading"  row-key="id" border fit highlight-current-row >
                                    <el-table-column prop="id" width="50" label="ID"></el-table-column>
                                    <el-table-column prop="name" label="Name"></el-table-column>
                                    <el-table-column prop="price" label="Price"></el-table-column>
                                    <el-table-column prop="new_price" label="Sale Price"></el-table-column>
                                    <el-table-column prop="period" label="period" >
                                            <template slot-scope="scope">
                                                    <p>{{ periods[scope.row.period] }}</p>
                                            </template>
                                    </el-table-column>

                                    <el-table-column label="Operations">
                                        <template slot-scope="scope">
                                            <!-- v-permission="['manage '+ptype+' edit']" -->
                                            <el-tooltip   content="Edit" placement="top">
                                                <el-button size="mini" type="warning" @click="UpdatePackage(scope.row)" ><i class="el-icon-edit"></i></el-button>
                                            </el-tooltip>

                                            <!-- v-permission="['manage '+ptype+' status']" -->
                                            <el-tooltip   v-if="scope.row.status == 1" content="Disable" placement="top">
                                                <el-button  size="mini" type="info" @click="ActivePackage(scope.row , 0)" ><i class="el-icon-error"></i></el-button>
                                            </el-tooltip>

                                            <!-- v-permission="['manage '+ptype+' status']" -->
                                            <el-tooltip    v-else-if="scope.row.status == 0" content="Active" placement="top">
                                                <el-button  size="mini" type="success" @click="ActivePackage(scope.row , 1)" ><i class="el-icon-success"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip content="Delete" placement="top"  > <!-- v-permission="['manage '+ptype+' delete']" -->
                                                <el-button size="mini" type="danger" @click="DeletePackage(scope.row)" ><i class="el-icon-delete"></i></el-button>
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
    import PackageResource from '@/api/ezuru/package' ;
    const Package = new PackageResource() ;
    export default {
        directives : { permission } ,
        data() {
           return {
               "tit" : "Add New Package" ,
               "type" : 'packages' ,
               "ptype" : this.getPtype() ,
               "id"   : 0 ,
               "name" : '' ,
               "description" : null ,
               "price" : "" ,
               "sale_type" : '' ,
               "sale_amount" : '' ,
               "status" : '0' ,
               "points" : 0 ,
               "period" : 1 ,
               "roles" : {} ,
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
               packagesRoles : settings.packages ,
               apiUrl  : settings.apiUrl+'admin/upload' ,
               parent : this.$route.params.type ,
               listLoading : false,
               periods : settings.packagePeriods 
           }
        },
        methods : {
            UpdatePackage : function(Package){
                 this.id = Package.id ;
                 this.name = Package.name ;
                 this.description = Package.description ;
                 this.price = Package.price ;
                 this.points = Package.points ;
                 this.status = Package.status.toString() ;
                 this.sale_type = Package.sale_type ;
                 this.sale_amount = Package.sale_amount ;
                this.period  = Package.period ;
                this.roles = Package.roles ; 
                 this.tit    = 'Update Package No: '+Package.id+ '  >>> '+Package.name;
            },
            DeletePackage : function(Package){
                var o = this ;

                this.$confirm('This will permanently delete the file. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'danger'
                }).then(() => {

                    fetch(settings.apiUrl+'admin/packages/'+Package.id , {
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
            ActivePackage : function(Package , status){
                var o = this ;

                this.$confirm('This will Update Package Status. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {

                    fetch(settings.apiUrl+'admin/packages/active/'+Package.id , {
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
            AddPackage : function(){
                if( !this.name || this.name.length < 2 ){
                    this.$message.error('Please Set Vaild Name');
                }else {
                    let o = this ;
                    let tax = {
                        "name"      : this.name ,
                        "id"       : this.id ,
                        "description" : this.description,
                        "price" : this.price ,
                        "status" : this.status,
                        "points" : this.points ,
                        "sale_amount"  : this.sale_amount ,
                        "sale_type"  : this.sale_type ,
                        "period"   : this.period ,
                        "roles" : this.roles
                    }
                    fetch(settings.apiUrl+'admin/packages' , {
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
                                o.$message.success('Package Saved');
                                o.getList() ;
                                o.id = 0 ;
                                o.name = "" ;
                                o.description = "" ;
                                o.price = "" ;
                                o.status = '' ;
                                o.points = 0 ;
                                o.price_old = '' ;
                                o.period = '' ;
                                o.roles = {} ;
                                o.tit    = 'Add New : Package' ;
                            }
                    });


                }
            },
            PackageSearch : function(search){

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

                let u = settings.apiUrl+'admin/packages/?type='+ this.type+'&page='+page ;

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
            getPtype : function(){
                    return 'packages' ;
            }
                
        },
        async mounted() {
                
               /*
                * Set Package Type Settings
                */
                var t = this.type ;

                if( !t && this.$route.meta.type ){
                    t = this.type = this.$route.meta.type ;
                    this.tit = "Add New "+ this.$route.meta.title ; 
                }else if( !t ){
                    t = this.type = 'packages' ;
                }

                var osetting = {
                    photo : true ,
                    description :true,
                    parent : true
                };

                this.setting = osetting ;
                this.getList(1) ;
                             
        },

        watch : {
            "description": async function(country) {
            
            },
            "description_en": async function() { 
                
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