<template>
    <div id="unitSearch">
            <div class="app-container">
                <el-form :model="query" label-position="left" label-width="150px">
                    <el-row>
                        
                            <el-col :span="6">
                                        <el-form-item :label="'User'">
                                            <el-select
                                                v-model="query.user_id"
                                                filterable
                                                remote
                                                placeholder="Search User"
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
                                        <el-form-item :label="'Model'">
                                            <el-select v-model="query.models" placeholder="Models" >
                                                    <el-option v-for="item in models_list" :key="item" :label="capitalizeFirstLetter(item)" :value="item" />
                                            </el-select>
                                        </el-form-item>
                            </el-col>

                            <el-col :span="1">&nbsp;</el-col>
                            <el-col :span="5">
                                        <el-button type="primary" @click="DoSearch">Search</el-button>
                                        <el-button type="warning" @click="CancelSearch()">Cancel</el-button>
                            </el-col>

                    </el-row>

                    <el-row>
                            
                    </el-row>


                </el-form>
            </div>
    </div>
</template>

<script>
    import settings from '@/settings' ;

    import UnitResource from '@/api/ezuru/units' ;
    import UserResource from '@/api/user' ;

    let Units = new UnitResource() ;
    let Users = new UserResource() ;

    export default {
        prop : ['search'] ,
        data(){
            return {
               loading: false, 
               query : {
                   page : 1 ,
                   limit : 20 ,
                   user_id : '' ,
                   models : ''
               },
               bookingStatus : settings.flagStatus,
               models_list : ["dashboard" , "units" , "users" , "reviews" , "payments" , "messages" , "flags" , "log" , "settings" ,
                "category aminites",
                "category area",
                "category badge",
                "category careers",
                "category category",
                "category city",
                "category contact",
                "category country",
                "category cpolicy",
                "category faq",
                "category fee",
                "category government",
                "category news",
                "category policy",
                "category rest",
                "category review_type",
                "category views",
                "about",
                "blog",
                "cancle",
                "careers",
                "checkin",
                "contact",
                "faq",
                "how_work",
                "news",
                "page",
                "partiners",
                "payment_methods",
                "slider",
                "team",
                "tourist_places",
                "trust",
                "why_ezuru"
               ] ,
               type : [],
               users_list : [] 
            }
        },
        methods : {
            setSearch(){
               this.$parent.query =  this.query ;
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
                this.$parent.gety() ;
            },
            CancelSearch(){
                var no = { "page" : this.query.page , "limit" : this.query.limit , "type" : this.query.type } ;
                this.query = no ;
            },
          capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
},
            async searchUnit(query) {
                if (query.length >= 3 ) {
                    this.loading = true;
                    let self = this ;
                    this.units_list = await Units.select( { 's' : query } ) ; 
                    this.loading = false;  
                } else {
                    this.units_list = [];
                }
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
            } ,
            async searchUser2(query) {
                if (query.length >= 3 ) {
                    this.loading = true;
                    let self = this ;
                    this.users_list2 = await Users.select( { 's' : query } ) ; 
                    this.loading = false;  
                } else {
                    this.users_list2 = [];
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