<template>
    <div id="taxonomy" class="app-container">
        <h1 class="title">  </h1>

            <el-container>
                <el-header>
                    <h3> {{ tit }}    <el-button type="primary" class="el-left" @click="search.visible = true"><i class="el-icon-search"></i> Search For Cancel Policy</el-button> </h3>
                    <hr/>
                </el-header>
                <el-main height="">
                    <el-row >
                        <el-col :span="12">
                            <el-form label-position="left"  label-width="150px">
                                    <el-row>

                                        <el-col :span="22">
                                            <el-form-item label="Name">
                                                <el-input v-model="name" type="text"/>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="22">
                                            <el-form-item label="Name - EN">
                                                <el-input v-model="name_en" type="text"/>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22"><el-divider>
                                            <i class="el-icon-star-on"></i>
                                            عندما يتم الالغاء   
                                            <i class="el-icon-star-on"></i>
                                        </el-divider></el-col>

                                        <el-col :span="22">
                                            <el-form-item label="قبل كام يوم">
                                                <el-input v-model="before" type="number"/>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22">
                                            <el-form-item label="يتم خصم كام فى المية">
                                                <el-input v-model="before_percent" type="number"/>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22">
                                            <el-form-item label="وكام فى الميه من الضرائب والخدمات">
                                                <el-input v-model="before_fee" type="number"/>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22"><el-divider>
                                            <i class="el-icon-star-on"></i>
                                            وعندما يتم الالغاء خلال  
                                            <i class="el-icon-star-on"></i>
                                        </el-divider></el-col>

                                        <el-col :span="22">
                                            <el-form-item label="كام يوم من الحجز">
                                                <el-input v-model="within" type="number"/>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22">
                                            <el-form-item label="يتم خصم كام فى الميه من الحجز">
                                                <el-input v-model="within_percent" type="number"/>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22">
                                            <el-form-item label="وخصم كام فى الميه من الضرائب والخدمات">
                                                <el-input v-model="within_fee" type="number"/>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22">
                                            <el-form-item label="مع احتساب اول كام يوم مدفوع">
                                                <el-input v-model="within_minus" type="number"/>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22"><el-divider>
                                            <i class="el-icon-star-on"></i>
                                            وعندما يتم الالغاء فى فتره الحجز  
                                            <i class="el-icon-star-on"></i>
                                        </el-divider></el-col>

                                        <el-col :span="22">
                                            <el-form-item label="يتم خصم كام فى الميه من الايام المتبقية">
                                                <el-input v-model="checkin_out" type="number"/>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22">
                                            <el-form-item label="وباضافه كام يوم يحتسب كمدفوع">
                                                <el-input v-model="checkin_minus" type="number"/>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22">
                                            <el-form-item label="ويتم خصم كام من الضرائب والخدمات">
                                                <el-input v-model="checkin_fee" type="number"/>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22">
                                            <el-form-item label="Description">
                                                <el-input v-model="description" type="textarea"/>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="22" >
                                            <el-form-item label="Description - EN">
                                                <el-input v-model="description_en" type="textarea" />
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
                                    <el-table-column prop="name_en" label="Name EN"></el-table-column>
                                    <el-table-column label="Operations">
                                        <template slot-scope="scope">

                                            <el-tooltip content="Edit" placement="top">
                                                <el-button size="mini" type="warning" @click="UpdateTaxonomy(scope.row)" ><i class="el-icon-edit"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip v-if="scope.row.status == 1" content="Disable" placement="top">
                                                <el-button  size="mini" type="info" @click="ActiveTaxonomy(scope.row , 0)" ><i class="el-icon-error"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip v-else-if="scope.row.status == 0" content="Active" placement="top">
                                                <el-button  size="mini" type="success" @click="ActiveTaxonomy(scope.row , 1)" ><i class="el-icon-success"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip content="Delete" placement="top">
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

    export default {
        data() {
           return {
               "tit" : "Add New Cancel Policy",
               "id"   : 0 ,
               "name" : '' ,
               "name_en" : '' ,
               "description" : '' ,
               "description_en" : '' ,
               'before' : "",
               'before_percent' : "",
               'before_fee' : "" ,
               'within' : "", 
               'within_percent' : "", 
               'within_fee' : "", 
               'within_minus' : "", 
               'checkin_out' : "", 
               'checkin_minus' : "", 
               'checkin_fee' : "", 
               "status" : '0' ,
               taxData : [] ,
               search : {
                   's' : '' ,
                   'status' : '',
                   'visible' : false
               },
               pagination : {

               },
               apiUrl  : settings.apiUrl+'admin/upload' ,
               listLoading : false
           }
        },
        methods : {
            UpdateTaxonomy : function(taxonomy){
                 var o = this ;
                 Object.keys(taxonomy).forEach(function(key) {
                     o[key] = taxonomy[key] ;
                });  
                 this.tit    = 'Update Cancel No: '+taxonomy.id+ '  >>> '+taxonomy.name;
            },
            DeleteTaxonomy : function(taxonomy){
                var o = this ;

                this.$confirm('This will permanently delete the file. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'danger'
                }).then(() => {

                    fetch(settings.apiUrl+'cancel/'+taxonomy.id , {
                        "method" : "DELETE" ,
                        headers: {
                            'Accept': 'application/json',
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

                    fetch(settings.apiUrl+'cancel/active/'+taxonomy.id , {
                        "method" : "POST" ,
                        headers: {
                            'Accept': 'application/json',
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
                }else if( !this.name_en || this.name_en.length < 2 ){
                    this.$message.error('Please Set Vaild English Name');
                }else {
                    let o = this ;
                    let tax = this.$data ;

                    fetch(settings.apiUrl+'cancel' , {
                        "method" : "POST" ,
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body : JSON.stringify( tax )
                    })
                    .then( res => res.json() )
                    .then( function(res){
                            if( res.errors ){
                                o.$message.error( res.message );
                            }else{
                                o.$message.success('Cancel Policy Saved');
                                o.getList() ;
                                o.id = 0 ;
                                o.name = "" ;
                                o.name_en = "" ;
                                o.description = "" ;
                                o.description_en = "" ;
                                o.status = '' ;
                            }
                    });


                }
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

                let u = settings.apiUrl+'cancel/?page='+page ;

                if( search && message ){
                    u = u+'&s='+self.search.s+'&status='+self.search.status ;
                }

               fetch( u )
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


            }
                
        },
        mounted() {
                this.getList(1) ;
        },
        watch : {
            'status' : function(){
                this.status = this.status.toString() ;
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