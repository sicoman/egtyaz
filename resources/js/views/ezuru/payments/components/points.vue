<template>
    <div id="ControlPoints">
            <div class="app-container">
                <el-form label-position="left" label-width="150px">
                    <el-row>
                            
                            <el-form-item :label="$t('Points')" >
                                <el-input-number v-model="query.points" :min="1" :max="999999999999" ></el-input-number>
                            </el-form-item>
                            
                            <el-col :span="24">
                                        <el-form-item :label="'Add - Delete'">
                                            <el-select v-model="query.type" placeholder="Add - Delete" filterable >
                                                    <el-option v-for="( v , k ) in bookingStatus" :key="k" :label="v" :value="k" />
                                            </el-select>
                                        </el-form-item>
                            </el-col>

                    </el-row>

                    <el-row>
                            
                            <el-col :span="24">
                                <el-form-item :label="'Date'">
                                    <el-date-picker
                                        v-model="query.created_at"
                                        type="date"
                                        range-separator="To"
                                        start-placeholder="Date"
                                        end-placeholder="Date">
                                    </el-date-picker> 
                                </el-form-item>       
                            </el-col>

                        
                            <el-col :span="5">
                                        <el-button type="primary" @click="DoSave">Save</el-button>
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

    import PaymentsResource from '@/api/ezuru/payments' ;

    const Payments = new PaymentsResource() ;

    import Cookies from 'js-cookie' ;
    const TokenKey = 'Admin-Token' ;

    export default {
        prop : ['search' , 'user' , 'types'] ,
        data(){
            return {
               loading: false, 
               query : {},
               bookingStatus : {
                   'deposit' : 'Add Points' ,
                   'withdraw' : 'Delete Points' ,
               },
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
            DoSave(){
                
                if( !this.query.points ){
                    this.$message.error('Please Set valid Amount of points ' );
                }else {
                    let o = this ;
                    
                    fetch(settings.apiUrl+'payments/0' , {
                        "method" : "Put" ,
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                            'Content-Type': 'application/json'
                        },
                        body : JSON.stringify( o.query )
                    })
                    .then( res => res.json() )
                    .then( function(res){
                            if( res.errors ){
                                o.$message.error( res.message );
                            }else{
                                o.$message.success(o.query.type+' Saved');
                            }
                    });


                }

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
            this.query.user_id = this.$attrs.user_id ;
            this.query.points = 1 ;
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