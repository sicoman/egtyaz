<template>
    <div id="unitSearch">
            <div class="app-container">
                <el-form :model="query" label-position="left" label-width="150px">
                    <el-row>
                            <el-col :span="6">
                                        <el-form-item :label="'Search For'" prop="query.unit_id">
                                            <el-select
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

                            <el-col :span="6">
                                        <el-form-item :label="'Agent'">
                                            <el-select
                                                v-model="query.user_id"
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

                    </el-row>

                    <el-row>
                            <el-col :span="16">
                                <el-form-item :label="'Date'">
                                    <el-date-picker
                                        v-model="query.date"
                                        type="daterange"
                                        range-separator="To"
                                        start-placeholder=" From"
                                        end-placeholder=" To">
                                    </el-date-picker> 
                                </el-form-item>       
                            </el-col>
                            <el-col :span="8">
                                        <el-button type="primary" @click="DoSearch()">Search</el-button>
                                        <el-button type="warning" @click="CancelSearch()">Cancel</el-button>
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

    let Reports = new ReportsResource() ;

    let Users = new UserResource() ;

    export default {
        prop : ['search'] ,
        data(){
            return {
               loading: false, 
               query : {},
               bookingStatus : settings.paymentStatus,
               paymentGateways : settings.paymentGateways,
               type : [],
               users_list : [],
               units_list : [],
               booking_list : []
            }
        },
        methods : {
            setSearch(){
               this.$parent.search =  this.query ;
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
                this.$message.info('Please Wait Until Search');
                
                this.$parent.search =  this.query ;

                this.$parent.getList( 1 , true , true ) ;

            },
            CancelSearch(){
                var no = { "page" : this.query.page , "limit" : this.query.limit } ;
                this.query = no ;
            },
            async searchUnit(query) {
                console.log(query) ;
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
            }   
        },
        mounted(){
            this.query = this.$attrs.search ;
        },
        watch : {
            query : {
                deep: true,
                handler() {
                     this.setSearch() ;
                }
            }
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
    
</style>