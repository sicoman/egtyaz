<template>
    <div id="reports" class="app-container">
        <h1 class="title">  </h1>

            <el-container>
                <el-header>
                    <h3>
                        Agent Reports 
                        <el-button type="primary" class="el-left" @click="toggle('search')"><i class="el-icon-search"></i> Search </el-button>
                        &nbsp;
                        <el-button type="warning" v-role="['agent','admin']" class="el-left" @click="toggle('add')"><i class="el-icon-add"></i> Add Report </el-button>
                    </h3>
                    <hr/>
                </el-header>
                <el-main height="">
                    <el-row >
                        <el-col v-if="show.add==true" :span="24">
                            <Add v-if="show.add" :report="editRow" ></Add>
                        </el-col>
                        <el-col v-else :span="24">

                            <el-form  id="unitSearch" v-show="show.search" label-position="left" label-width="150px">
                                    <el-row>
                                            <el-col :span="6">
                                                        <el-form-item :label="'Search For'" prop="search.unit_id">
                                                            <el-select
                                                                v-model="search.unit_id"
                                                                filterable
                                                                remote
                                                                placeholder="Search Unit title"
                                                                :remote-method="searchUnit"
                                                                :loading="loading">
                                                                    <el-option
                                                                        v-for="item in units_list"
                                                                        :key="item.id"
                                                                        :label="item.title"
                                                                        :value="item.id">
                                                                    </el-option>
                                                            </el-select>
                                                        </el-form-item>
                                            </el-col>

                                            <el-col :span="6">
                                                        <el-form-item :label="'Agent'">
                                                            <el-select
                                                                v-model="search.user_id"
                                                                filterable
                                                                remote
                                                                placeholder="Search Agents"
                                                                :remote-method="searchUser"
                                                                :loading="loading">
                                                                    <el-option
                                                                        v-for="item in users_list"
                                                                        :key="item.id"
                                                                        :label="item.name"
                                                                        :value="item.id">
                                                                    </el-option>
                                                            </el-select>
                                                        </el-form-item>
                                            </el-col>

                                            <el-col :span="6">
                                                        <el-form-item :label="'Booking'">
                                                            <el-select
                                                                v-model="search.booking_id"
                                                                filterable
                                                                placeholder="Search Booking"
                                                                :loading="loading">
                                                                    <el-option
                                                                        v-for="item in booking_list"
                                                                        :key="item.id"
                                                                        :label="item.name"
                                                                        :value="item.id">
                                                                    </el-option>
                                                            </el-select>
                                                        </el-form-item>
                                            </el-col>

                                            <el-col :span="6">
                                                        <el-form-item :label="'Status'">
                                                            <el-select
                                                                v-model="search.status"
                                                                filterable
                                                                >
                                                                    <el-option
                                                                        v-for="(item , k) in reportStatus"
                                                                        :key="k"
                                                                        :label="item"
                                                                        :value="k">
                                                                    </el-option>
                                                            </el-select>
                                                        </el-form-item>
                                            </el-col>

                                    </el-row>

                                    <el-row>

                                            <el-col :span="10">
                                                <el-form-item :label="'Date'">
                                                    <el-date-picker
                                                        v-model="search.date"
                                                        type="daterange"
                                                        range-separator="To"
                                                        start-placeholder=" From"
                                                        end-placeholder=" To">
                                                    </el-date-picker> 
                                                </el-form-item>       
                                            </el-col>

                                            <el-col :span="8">
                                                        <el-form-item :label="'Answer'">
                                                            <el-select
                                                                v-model="search.answer"
                                                                filterable
                                                                >
                                                                    <el-option
                                                                        v-for="(item , k) in reportAnswer"
                                                                        :key="k"
                                                                        :label="item"
                                                                        :value="k">
                                                                    </el-option>
                                                            </el-select>
                                                        </el-form-item>
                                            </el-col>

                                            <el-col :span="6">
                                                        <el-button type="primary" @click="DoSearch()">Search</el-button>
                                                        <el-button type="warning" @click="CancelSearch()">Cancel</el-button>
                                            </el-col>
                                    </el-row>


                            </el-form>
                            
                              <el-table  ref="dragTable" :data="reportData" style="width: 100%" v-loading="listLoading"  row-key="id" border fit highlight-current-row >
                                    <!-- <el-table-column prop="id" width="50" label="ID"></el-table-column>-->
                                    <el-table-column label="Unit">
                                            <template slot-scope="scope">
                                                {{ scope.row.unit.title }} <br /> <small>Owner is : {{ scope.row.booking.owner.name }}</small>
                                            </template>
                                    </el-table-column>

                                    <el-table-column label="Guest">
                                            <template slot-scope="scope">
                                                {{ scope.row.booking.user.name }}
                                            </template>
                                    </el-table-column>

                                    <el-table-column label="Agent">
                                            <template slot-scope="scope">
                                                {{ scope.row.user.name }}
                                            </template>
                                    </el-table-column>

                                    <el-table-column label="Booking Period">
                                            <template slot-scope="scope">
                                                {{ scope.row.booking.date_start+' - '+scope.row.booking.date_end }}
                                            </template>
                                    </el-table-column>

                                    <el-table-column prop="type" label="Type"></el-table-column>

                                    <el-table-column label="Images">
                                            <template slot-scope="scope" >
                                                   <el-button @click="showImageSlider(scope.row)">{{ scope.row.images.length }}</el-button>
                                            </template>
                                    </el-table-column>

                                    <el-table-column label="Report">
                                            <template slot-scope="scope" >
                                                   <el-button @click="showReport(scope.row)">{{ $t('Show Report') }}</el-button>
                                            </template>
                                    </el-table-column>

                                    <el-table-column label="Answered">
                                            <template slot-scope="scope" >
                                                <div>
                                                    <el-tooltip  v-if="scope.row.answered_at && scope.row.answer" :content="scope.row.answered_at">
                                                        <el-button type="success">  Answered    </el-button>
                                                    </el-tooltip>
                                                    <el-button type="dander" v-else>Not Answered</el-button>
                                                </div>        
                                                   
                                            </template>
                                    </el-table-column>

                                    <el-table-column label="Operations">
                                        <template slot-scope="scope">

                                            <el-tooltip content="Edit" placement="top">
                                                <el-button size="mini" type="warning" @click="UpdateReport(scope.row)" ><i class="el-icon-edit"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip v-if="scope.row.status == 1" content="Disable" placement="top">
                                                <el-button  size="mini" type="info" @click="ActiveReport(scope.row , 0)" ><i class="el-icon-error"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip v-else-if="scope.row.status == 0" content="Active" placement="top">
                                                <el-button  size="mini" type="success" @click="ActiveReport(scope.row , 1)" ><i class="el-icon-success"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip content="Delete" placement="top">
                                                <el-button size="mini" type="danger" @click="DeleteReport(scope.row)" ><i class="el-icon-delete"></i></el-button>
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

                                <el-dialog title="Report Image Slider" :visible.sync="show.slider">
                                        <el-carousel :interval="4000" type="card" height="230px">
                                            <el-carousel-item v-for="(item , i) in imageSlider" :key="i">
                                                    <img class="medium" :src="imageSlider[i].image" :title="imageSlider[i].alt" />
                                                    <h3>{{imageSlider[i].alt}}</h3>
                                            </el-carousel-item>
                                        </el-carousel>
                                </el-dialog> 

                                <el-dialog title="View Report" :visible.sync="show.view">
                                        <el-collapse v-model="show.active">
                                            <el-collapse-item title="Report" name="1">
                                                <div v-html="viewRow.report"></div>
                                            </el-collapse-item>
                                            <el-collapse-item title="Notes" name="2">
                                                <div v-html="viewRow.notes"></div>
                                            </el-collapse-item>
                                            <el-collapse-item title="Answer" name="3" v-role="['admin','manager','editor']">
                                                <div v-role="['admin','manager','editor']">
                                                     <el-input v-model="viewRow.answer" type="textarea"></el-input>
                                                     <br /><br />
                                                     <el-button type="primary" @click="AnswerReport(viewRow)" >Answer On Report</el-button>
                                                </div>
                                            </el-collapse-item>
                                        </el-collapse>
                                </el-dialog>  

                        </el-col>
                    </el-row>
                </el-main>
            </el-container>
      

    </div>
</template>

<script>
    import settings from '@/settings' ;

    import ReportsResource from '@/api/ezuru/reports' ;

    import TableExport from 'tableexport' ;

    const Reports = new ReportsResource() ;

    import permission from '@/directive/permission/index.js' ;
    import role from '@/directive/role/index.js' ;

    import Cookies from 'js-cookie' ;

    const TokenKey = 'Admin-Token' ;

    import UserResource from '@/api/user' ;

    import Add from './components/Add.vue' ;

    let Users = new UserResource() ;

    export default {
        directives : { permission , role } ,
        components : { Add } ,
        data() {
           return {
               "tit" : "Add New Report",
               "id"   : 0 ,
               "status" : '0' ,
               imageSlider : [] ,
               viewRow : {} ,
               editRow : {
                   unit_id : '' ,
                   user_id : '' ,
                   booking_id : '' ,
                   type : 'before' ,
                   report : '' ,
                   notes : '' ,
                   images : []
               } ,
               reportData : [] ,
               search : {
                   'unit_id'    : '' ,
                   'booking_id' : '' ,
                   'user_id'    : '',
                   'date'       : [] ,
                   'status'     : '' ,
                   'answer'     : ''
               },
               pagination : {

               },
               apiUrl  : settings.apiUrl+'admin/upload' ,
               listLoading : false,
               show : {
                   add    : false,
                   search : false,
                   view   : false,
                   slider : false,
                   active : 1
               },
               booking_list : [] ,
               reportStatus : settings.reportStatus ,
               reportAnswer : {
                   0 : 'Not Answered' ,
                   1 : 'Answered'
               },
               loading: false ,
               users_list : [] ,
               units_list : []
           }
        },
        methods : {
            showImageSlider(row){
                this.show.slider = true ;
                this.imageSlider = row.images ;
            },
            showReport(row){
                this.show.view = true ;
                this.viewRow = row ;
            },
            UpdateReport : function(report){
                this.editRow = report ;
                this.tit    = 'Update Report No: '+report.id+ '  >>> '+report.unit.name;
                this.show.add = true ;
            },
            DeleteReport : function(report){
                var o = this ;

                this.$confirm('This will permanently delete the Report. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'danger'
                }).then(() => {

                    fetch(settings.apiUrl+'reports/'+report.id , {
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
                                o.$message.error( 'Unable to Delete No.'+o.id );
                            }else{
                                o.$message.success('Deleted Succefully');
                                o.getList() ;
                            }
                    });

                }).catch(() => {
                    this.$message({ type: 'info', message: 'Delete canceled'});          
                });
            } ,
            ActiveReport : function(report , status){
                var o = this ;

                this.$confirm('This will Update Report Status. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {

                    fetch(settings.apiUrl+'reports/active/'+report.id , {
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
            AnswerReport : function(row){
                var o = this ;

                this.$confirm('This will Update Report Answer. Continue?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {

                    fetch(settings.apiUrl+'reports/answer/'+row.id , {
                        "method" : "POST" ,
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                            'Content-Type': 'application/json'
                        },
                        body : JSON.stringify( { "answer" : row.answer  } )
                    })
                    .then( res => res.json() )
                    .then( function(res){
                            if( res.res == 0 ){
                                o.$message.error( 'Unable to Update Report '+row.id+' Answer' );
                            }else{
                                o.$message.success('Updated Succefully');
                                o.getList() ;
                            }
                    });

                }).catch(() => {
                    this.$message({ type: 'info', message: 'Update canceled'});          
                });
            },
            AddReport : function(){
                
            },
            
            gotoPage : function(a){
                this.getList(a , true , false) ;
            },
             convert(str) {
            var date = new Date(str),
                mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                day = ("0" + date.getDate()).slice(-2);
            return [date.getFullYear(), mnth, day].join("-");
            },
            getList : function( page = 1 , search = false , message = false ){

                if( !this.type ){
                    this.type = this.$route.params.type ;
                }
                let self = this ;

                this.listLoading = true;

                let u = settings.apiUrl+'reports/?page='+page ;

                if( search && message ){
                    u = u+'&unit_id='+self.search.unit_id+'&status='+self.search.status+'&user_id='+self.search.user_id+'&booking_id='+self.search.booking_id+'&answer='+self.search.answer ;
                    if( this.search.date.length > 0 ){
                        u = u+'&date_start='+this.convert(self.search.date[0])+'&date_end='+this.convert(self.search.date[1]) ;
                    }


                }

               fetch( u , { 
                method: 'get', 
                headers: new Headers({
                    'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                }) 
                } )
               .then( res => res.json() )
               .then( function(res){
                     self.reportData = res.data ;

                     self.pagination.total = res.total ;
                     self.pagination.per_page = res.per_page ;
                     self.pagination.current_page = res.current_page ;

                     if( search && message ){
                        self.$message.success('We Found '+ res.total+' Result' );
                    }
                    self.listLoading = false;
               });


            },
            toggle(sh){
               var v = this.show[sh]  ;
               if( v ){
                  this.show[sh] = false;  
               }else{
                  this.show[sh] = true;   
               }
            },
            async searchUnit(query) {
                if (query.length >= 2 ) {
                    this.loading = true;
                    let self = this ;
                    let units_list = await Reports.selectUnit( query ) ; 
                    if( units_list.hasOwnProperty('units') ){
                        this.units_list = units_list.units ;
                        this.booking_list = units_list.booking ;
                    }
                    this.loading = false;  
                } else {
                    this.units_list = [];
                }
            } ,
            async searchUser(query) {
                if (query.length >= 3 ) {
                    this.loading = true;
                    let self = this ;
                    this.users_list = await Users.select( { 's' : query , 'role' : 'agent' } ) ; 
                    this.loading = false;  
                } else {
                    this.users_list = [];
                }
            } ,
            CancelSearch(){
                this.search = {
                   'unit_id'    : '' ,
                   'booking_id' : '' ,
                   'user_id'    : '',
                   'date'       : [] ,
                   'status'     : ''
               } ;
               this.show.search = false ;
            } ,

            DoSearch(){
                this.show.search = false ;
                this.getList(1 , true , true) ;
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

  
  .el-carousel__item img {
    width:100%;height:200px;
  }
  .el-carousel__item h3 {
    color: #000;
    font-size: 14px;
    opacity: 0.75;
    line-height: 30px;
    margin: 0;text-align:center
  }

  .el-carousel__item:nth-child(2n) {
    background-color: #99a9bf;
  }

  .el-carousel__item:nth-child(2n+1) {
    background-color: #d3dce6;
  }

</style>