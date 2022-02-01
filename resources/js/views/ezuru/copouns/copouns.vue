<template>
    <div id="Copoun" class="app-container">
        <h1 class="title">  </h1>

            <el-container>
                <el-header>
                    <h3> {{ tit }}    <el-button type="primary" class="el-left"  v-permission="['manage '+ptype+' search']"  @click="search.visible = true"><i class="el-icon-search"></i> Search For {{ type }}</el-button> </h3>
                    <hr/>
                </el-header>
                <el-main height="">
                    <el-row >
                        <el-col :span="10" > <!--  v-permission="['manage copouns add']" -->
                            <el-form label-position="left"  label-width="150px">
                                    <el-row>
                                        <el-col :span="22">
                                            <el-form-item label="Code">
                                                <el-input v-model="code" type="text"/>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22">
                                            <el-form-item label="Type">
                                                <el-select v-model="type" >
                                                      <el-option value="percent" label="Percent" /> 
                                                      <el-option value="fixed" label="Fixed" />      
                                                </el-select>   
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22">
                                            <el-form-item label="Amount">
                                                <el-input-number v-model="amount" />
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22">
                                            <el-form-item label="Maximum Times">
                                                <el-input-number v-model="times" />
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22">
                                            <el-form-item label="Description">
                                                <el-input v-model="description" type="textarea"/>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="22" >
                                            <el-form-item label="Expire date">
                                                 <el-date-picker
                                                    v-model="expire_date"
                                                    type="date"
                                                    placeholder="Pick a date">
                                                </el-date-picker> 
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
                                            <el-form-item>
                                                <el-button type="primary" @click="AddCopoun"> Save </el-button>
                                            </el-form-item>
                                        </el-col>

                                    </el-row>
                            </el-form> 
                        </el-col>
                        <el-col :span="14">
                            
    
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
                                    <el-button @click="copounsearch(false)">Cancel</el-button>
                                    <el-button type="primary" @click="copounsearch(true)">Search</el-button>
                                </span>
                            </el-dialog>



                              <el-table  ref="dragTable" :data="taxData" style="width: 100%" v-loading="listLoading"  row-key="id" border fit highlight-current-row >
                                    <el-table-column prop="id" width="50" label="ID"></el-table-column>
                                    <el-table-column prop="code" label="Code"></el-table-column>
                                    <el-table-column prop="type" label="Type"></el-table-column>
                                    <el-table-column prop="amount" label="Amount"></el-table-column>
                                    <el-table-column prop="expire_date" label="Date" ></el-table-column>
                                    <el-table-column prop="times" label="Max times" ></el-table-column>

                                    <el-table-column label="Operations">
                                        <template slot-scope="scope">
                                            <!-- v-permission="['manage '+ptype+' edit']" -->
                                            <el-tooltip   content="Edit" placement="top">
                                                <el-button size="mini" type="warning" @click="UpdateCopoun(scope.row)" ><i class="el-icon-edit"></i></el-button>
                                            </el-tooltip>

                                            <!-- v-permission="['manage '+ptype+' status']" -->
                                            <el-tooltip   v-if="scope.row.status == 1" content="Disable" placement="top">
                                                <el-button  size="mini" type="info" @click="ActiveCopoun(scope.row , 0)" ><i class="el-icon-error"></i></el-button>
                                            </el-tooltip>

                                            <!-- v-permission="['manage '+ptype+' status']" -->
                                            <el-tooltip    v-else-if="scope.row.status == 0" content="Active" placement="top">
                                                <el-button  size="mini" type="success" @click="ActiveCopoun(scope.row , 1)" ><i class="el-icon-success"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip content="Delete" placement="top"  > <!-- v-permission="['manage '+ptype+' delete']" -->
                                                <el-button size="mini" type="danger" @click="DeleteCopoun(scope.row)" ><i class="el-icon-delete"></i></el-button>
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
    import CopounResource from '@/api/ezuru/copoun' ;
    const Copoun = new CopounResource() ;
    export default {
        directives : { permission } ,
        data() {
           return {
               "tit" : "Add New Copoun" ,
               "type" : 'percent' ,
               "ptype" : this.getPtype() ,
               "id"   : 0 ,
               "code" : '' ,
               "description" : null ,
               "amount" : "" ,
               "times" : '' ,
               "status" : '0' ,
               "expire_date" : "" ,
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
               listLoading : false,
           }
        },
        methods : {
            UpdateCopoun : function(Copoun){
                 this.id = Copoun.id ;
                 this.code = Copoun.code ;
                 this.description = Copoun.description ;
                 this.amount = Copoun.amount ;
                 this.status = Copoun.status.toString() ;
                 this.type   = Copoun.type ;
                 this.times = Copoun.times ;
                 this.expire_date  = Copoun.expire_date ;
                 this.tit    = 'Update Copoun No: '+Copoun.id+ '  >>> '+Copoun.name;
            },
            DeleteCopoun : function(Copoun){
                var o = this ;
                this.$confirm('This will permanently delete the Copoun. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'danger'
                }).then(() => {

                    fetch(settings.apiUrl+'admin/copouns/'+Copoun.id , {
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
            ActiveCopoun : function(Copoun , status){
                var o = this ;

                this.$confirm('This will Update Copoun Status. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {

                    fetch(settings.apiUrl+'admin/copouns/active/'+Copoun.id , {
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
                                o.$message.error( 'Unable to Update Copoun');
                            }else{
                                o.$message.success('Updated Succefully');
                                o.getList() ;
                            }
                    });

                }).catch(() => {
                    this.$message({ type: 'info', message: 'Update canceled'});          
                });

            } ,
            AddCopoun : function(){
                if( !this.code || this.code.length < 2 ){
                    this.$message.error('Please Set Vaild Code');
                }else {
                    let o = this ;
                    let tax = {
                        "code"      : this.code ,
                        "id"       : this.id ,
                        "description" : this.description,
                        "amount" : this.amount ,
                        "type"   : this.type ,
                        "status" : this.status,
                        "times"  : this.times ,
                        "expire_date"   : this.expire_date 
                    }
                    fetch(settings.apiUrl+'admin/copouns' , {
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
                                o.$message.success('Copoun Saved');

                                o.getList() ;
                                
                                o.id = 0 ;
                                o.code = "" ;
                                o.description = "" ;
                                o.amount = "" ;
                                o.status = '' ;
                                o.times = '' ;
                                o.type = 'percent' ;
                                o.expire_date = '' ;
                                
                                o.tit    = 'Add New : Copoun' ;
                            }
                    });


                }
            },
            copounsearch : function(search){

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

               
                let self = this ;

                this.listLoading = true;

                let u = settings.apiUrl+'admin/copouns/?page='+page ;

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
                    return 'copouns' ;
            }
                
        },
        async mounted() {
                
               /*
                * Set Copoun Type Settings
                */
                var t = this.type ;

                if( !t && this.$route.meta.type ){
                    t = this.type = this.$route.meta.type ;
                    this.tit = "Add New "+ this.$route.meta.title ; 
                }else if( !t ){
                    t = this.type = 'percent' ;
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