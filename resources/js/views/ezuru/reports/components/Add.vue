<template>
    <div id="unitSearch">
            <div class="app-container">
                
                <el-form label-position="left" label-width="200px">
                                    <el-row>
                                        <el-col :span="7">
                                                    <el-form-item :label="'Select Unit'" prop="query.unit_id">
                                                        <el-select
                                                            style="width:100%"
                                                            v-model="query.unit_id"
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
                                        <el-col :span="1">
                                            &nbsp;
                                        </el-col>
                                        <el-col :span="8">
                                            <el-form-item :label="'Booking'">
                                                <el-select
                                                    style="width:100%"
                                                    v-model="query.booking_id"
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
                                        <el-col :span="1">
                                            &nbsp;
                                        </el-col>
                                        <el-col :span="7">
                                            <el-form-item :label="'Type'">
                                                <el-select
                                                    style="width:100%"
                                                    v-model="query.type"
                                                    placeholder="Select Type"
                                                    >
                                                        <el-option key="before" value="before" label="Before">Before</el-option>
                                                        <el-option key="after" value="after" label="After">After</el-option>
                                                </el-select>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22">
                                            <el-form-item :label="'Report'">
                                                <el-input v-model="query.report" type="textarea" rows="15" ></el-input>
                                                <br />
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22">
                                            <el-form-item :label="'Notes'">
                                                <el-input v-model="query.notes" type="textarea" rows="5" ></el-input>
                                                <br />
                                            </el-form-item>
                                        </el-col>
                                        
                                        

                                    </el-row>
                                    <el-row>
                                        <el-col :span="24">
                                    <el-upload  class="upload-demo" ref="attachments" drag :show-file-list="false" :action="uploadUrl" :on-success="handleUpload"  multiple>
                                        <i class="el-icon-upload"></i>
                                        <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
                                        <div class="el-upload__tip" slot="tip"> <el-alert type="info">jpg/png files with a size less than 10 MB</el-alert> <br /> <br/> </div>
                                    
                                    </el-upload>
                            </el-col> 

                            <el-col :span="24">
                                    <el-table  row-key="ordr" border fit highlight-current-row :data="query.images" ref="attachments" class="attach"  style="width: 100%">
                                        
                                        <el-table-column label="Image">
                                            <template slot-scope="scope">
                                                 <img :src="scope.row.image" width="50" height="50" />
                                            </template>    
                                        </el-table-column>
                                        
                                        <el-table-column label="Title">
                                            <template slot-scope="scope">
                                                 <el-input v-model="query.images[scope.$index].alt" ></el-input>
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Delete">
                                            <template slot-scope="scope">
                                                <el-button type="danger" @click="handleDelete( scope.row , scope.$index )"> <i class="el-icon-delete"></i> </el-button>
                                            </template>    
                                        </el-table-column>
                                        <!--
                                        <el-table-column>
                                            <template>
                                                    <span class="my-handle">::</span>
                                            </template>
                                        </el-table-column>
                                        -->
                                    </el-table>
                            </el-col>

                            <el-col :span="22">
                                            <el-form-item>
                                                <el-button type="primary" @click="AddReport()"> Save </el-button>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                            </el-form> 
            </div>
    </div>
</template>

<script>
    import settings from '@/settings' ;

    import ReportsResource from '@/api/ezuru/reports' ;

    import UserResource from '@/api/user' ;

    import Cookies from 'js-cookie';

    const TokenKey = 'Admin-Token';

    let Reports = new ReportsResource() ;

    let Users = new UserResource() ;

    export default {
        prop : ['report'] ,
        name : 'attachements' ,
        data(){
            return {
               uploadUrl : settings.apiUrl+'admin/upload', 
               loading: false, 
               query : {
                   unit_id : '' ,
                   user_id : '' ,
                   booking_id : '' ,
                   type : 'before' ,
                   report : '' ,
                   notes : '' ,
                   images : []
               },
               bookingStatus : settings.paymentStatus,
               paymentGateways : settings.paymentGateways,
               type : [],
               users_list : [],
               units_list : [],
               booking_list : []
            }
        },
        methods : {
            AddReport(){
                // this.$parent.AddReport(this.query) ;
                if( !this.query.unit_id ){
                    this.$message.error('Please Select Vaild Unit');
                } else if( !this.query.booking_id ){
                    this.$message.error('Please Select Vaild Booking');
                }else {
                    let o = this ;
                    let tax = this.query ;

                    fetch(settings.apiUrl+'reports' , {
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
                                o.$message.success('Report Saved Succefully');
                                o.$parent.show.add = false ;
                                o.$parent.getList() ;
                            }
                    });


                }
            },
            getUsers(){
                let self = this ;
                fetch(settings.apiUrl+'admin/users/list')
               .then( res => res.json() )
               .then( function(res){
                     self.users = res ; 
               });
            } ,
            DoSearch(){
                this.$parent.ReportSearch() ;
            },
            CancelSearch(){
                var no = { "page" : this.query.page , "limit" : this.query.limit } ;
                this.query = no ;
            },
            async searchUnit(query) {
                    this.loading = true;
                    let self = this ;
                    let units_list = await Reports.selectUnit( query ) ; 
                    if( units_list.hasOwnProperty('units') ){
                        this.units_list = units_list.units ;
                        this.booking_list = units_list.booking ;
                    }
                    this.loading = false;  
                
            } ,
            async searchUser(query) {
                this.loading = true;
                let self = this ;
                this.users_list = await Users.select( { 's' : query } ) ; 
                this.loading = false;  
                
            } ,
            handleUpload(file, response){
                this.query.images.push( { "image" : response.response , title : "" } ) ;
            },
            handleDelete(row , index){
                this.query.images.splice(index,1) ;
            },
        },
        computed : {
            'query' : function(){
                this.searchUser();
                this.searchUnit();
            }
        },
        mounted(){
            this.query = this.$attrs.report ;
            if( this.query == {} ){
                this.query = {
                   unit_id : '' ,
                   user_id : '' ,
                   booking_id : '' ,
                   type : 'before' ,
                   report : '' ,
                   notes : '' ,
                   images : []
               } ;
            }

            this.searchUser();
            this.searchUnit();

        }
    }
</script>

<style>
    #unitSearch{
        padding:20px 0;
        background:#f1f1f1
    }
    .el-form-item__label{
        text-align: center!important
    }
    .el-date-editor--datetimerange.el-input, .el-date-editor--datetimerange.el-input__inner,.el-date-editor--daterange.el-input, .el-date-editor--daterange.el-input__inner{
        max-width: 100%
    }
    .el-upload,.el-upload-dragger,.el-select{width:100% !important;}
</style>