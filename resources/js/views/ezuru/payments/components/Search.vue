<template>
    <div id="unitSearch">
            <div class="app-container">
                <el-form label-position="left" label-width="150px">
                    <el-row>
                            <el-col :span="8" v-if="query.hasOwnProperty('type') && query.type == 'course'">
                                        <el-form-item :label="'Select Course'" prop="query.package_id">
                                            <el-select
                                                v-model="query.package_id"
                                                filterable
                                                :placeholder="'Select '+query.type"
                                                :loading="loading">
                                                    <el-option
                                                        v-for="item in packages_list"
                                                        :key="item.id"
                                                        :label="item.title"
                                                        :value="item.id">
                                                    </el-option>
                                            </el-select>
                                        </el-form-item>
                            </el-col>
                            <el-col :span="8" v-if="query.hasOwnProperty('type')">
                                        <el-form-item :label="'Search Package'" prop="query.package_id">
                                            <el-select
                                                v-model="query.package_id"
                                                filterable
                                                :placeholder="'Select '+query.type"
                                                :loading="loading">
                                                    <el-option
                                                        v-for="item in packages_list"
                                                        :key="item.id"
                                                        :label="item.name"
                                                        :value="item.id">
                                                    </el-option>
                                            </el-select>
                                        </el-form-item>
                            </el-col>

                            <el-col :span="8">
                                        <el-form-item :label="'User'">
                                            <el-select
                                                v-model="query.user_id"
                                                filterable
                                                remote
                                                placeholder="Search Students Names"
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

                            <el-col :span="8">
                                        <el-form-item :label="'Status'">
                                            <el-select v-model="query.status" placeholder="Status" filterable >
                                                    <el-option v-for="( v , k ) in bookingStatus" :key="k" :label="v" :value="parseInt(k)" />
                                            </el-select>
                                        </el-form-item>
                            </el-col>

                    </el-row>

                    <el-row>
                            
                            <el-col :span="6">
                                        <el-form-item :label="'Gateway'">
                                            <el-select v-model="query.gateway" placeholder="Gateway" filterable >
                                                    <el-option v-for="( v , k ) in paymentGateways" :key="k" :label="v" :value="k" />
                                            </el-select>
                                        </el-form-item>
                            </el-col>

                            <el-col :span="12">
                                <el-form-item :label="'Payment Date'">
                                    <el-date-picker
                                        v-model="query.date"
                                        type="daterange"
                                        range-separator="To"
                                        start-placeholder=" Payment From"
                                        end-placeholder=" Payment To">
                                    </el-date-picker> 
                                </el-form-item>       
                            </el-col>

                        
                            <el-col :span="1">&nbsp;</el-col>
                            <el-col :span="5">
                                        <el-button type="primary" @click="DoSearch">Search</el-button>
                                        <el-button type="warning" @click="CancelSearch()">Cancel</el-button>
                            </el-col>
                    </el-row>


                </el-form>
            </div>
    </div>
</template>

<script>
    import settings from '@/settings' ;

    import PackageResource from '@/api/ezuru/package' ;
    import UserResource from '@/api/user' ;

    let Packages = new PackageResource() ;
    let Users = new UserResource() ;

    import Cookies from 'js-cookie' ;
    const TokenKey = 'Admin-Token' ;


    export default {
        prop : ['search'] ,
        data(){
            return {
               loading: false, 
               query : {
               },
               bookingStatus : settings.paymentStatus,
               paymentGateways : settings.paymentGateways,
               type : [],
               users_list : [],
               packages_list : []
            }
        },
        methods : {
            setSearch(){
               this.$parent.query =  this.query ;
            },
            getType(){
                let self = this ;
                fetch(settings.apiUrl+'admin/taxonomy/category?parent=0')
               .then( res => res.json() )
               .then( function(res){
                     self.type = res ; 
               });
            } ,
            getUsers(){
                let self = this ;
                fetch(settings.apiUrl+'admin/users/list')
               .then( res => res.json() )
               .then( function(res){
                     self.users = res ; 
               });
            } ,
            DoSearch(){
                this.$parent.gety() ;
            },
            CancelSearch(){
                var no = { "page" : this.query.page , "limit" : this.query.limit } ;
                this.query = no ;
            },
            getPackages(){
                var self = this ;
                fetch(settings.apiUrl+'admin/packages/select' ,{ 
                method: 'get', 
                    headers: new Headers({
                        'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                    }) 
                })
               .then( res => res.json() )
               .then( function(res){
                     self.packages_list = res ;
                      
               });
            } ,
            getCourses(){
                var self = this ;
                fetch(settings.apiUrl+'admin/course/select' ,{ 
                method: 'get', 
                    headers: new Headers({
                        'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                    }) 
                })
               .then( res => res.json() )
               .then( function(res){
                     self.packages_list = res ; 
               });
            } ,
            async searchUser(query) {
                if (query.length >= 3 ) {
                    this.loading = true;
                    let self = this ;
                    this.users_list = await Users.select( { 's' : query } ) ; 
                    this.loading = false;  
                } else {
                    this.users_list = [];
                }
            }    
        },
        mounted(){
            this.query = this.$attrs.search ;
            this.getType();
            if( this.query.type == 'course' ){
                this.getCourses();
            }else{
                this.getPackages();
            }
            
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