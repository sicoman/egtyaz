<template>
    <div id="unitSearch">
            <div class="app-container">
                <el-form :model="query" label-position="left" label-width="150px">
                    <el-row>
                            <el-col :span="12">
                                        <el-form-item :label="'Subjects'" prop="query.subjects">
                                            <el-select
                                                v-model="query.subjects"
                                                filterable
                                                multiple
                                                remote
                                                placeholder="Search Subjects"
                                                :remote-method="searchSubjects"
                                                :loading="loading">
                                                    <el-option
                                                        v-for="item in subjects"
                                                        :key="item.id"
                                                        :label="item.name"
                                                        :value="item.id">
                                                    </el-option>
                                            </el-select>
                                        </el-form-item>
                            </el-col>

                            <el-col :span="12" v-if="type == 'free'">
                                        <el-form-item :label="'Students'">
                                            <el-select
                                                v-model="query.user_id"
                                                filterable
                                                multiple
                                                remote
                                                placeholder="Search Students"
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
                                        <el-form-item :label="'Levels'">
                                            <el-select v-model="query.level_id" placeholder="Levels" filterable >
                                                    <el-option v-for="( v , k ) in levels" :key="k" :label="v" :value="parseInt(k)" />
                                            </el-select>
                                        </el-form-item>
                            </el-col>
                            
                            <el-col :span="1">&nbsp;</el-col>
                            <el-col :span="9">
                                    <el-date-picker
                                        v-model="query.date"
                                        type="daterange"
                                        range-separator="To"
                                        start-placeholder=" Request From"
                                        end-placeholder=" Request To">
                                    </el-date-picker>    
                            </el-col>

                        

                            <el-col :span="1">&nbsp;</el-col>
                            <el-col :span="5">
                                        <el-button type="primary" @click="DoSearch">Search</el-button>
                                        <el-button v-if="1==0" type="warning" @click="CancelSearch()">Cancel</el-button>
                            </el-col>
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

    import Cookies from 'js-cookie' ;
    const TokenKey = 'Admin-Token' ;

    export default {
        prop : ['search'] ,
        data(){
            return {
               loading: false, 
               query : {},
               bookingStatus : settings.bookingStatus,
               unitFeature : settings.unitFeature,
               type : [],
               users_list : [],
               units_list : [],
               subjects:[] ,
               levels : []
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
            getTaxonomy : function( type , variable , parent = 0 ){
                if( !type ){
                    type = category ;
                }
                let self = this ;
                fetch( settings.apiUrl+'admin/taxonomy/'+ type +'?parent='+parent , { 
                method: 'get', 
                headers: new Headers({
                    'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                }) 
                })
               .then( res => res.json() )
               .then( function(res){
                     self[variable] = res ;
               });
            },
            searchSubjects : function(s){
                let self = this ;
                fetch( settings.apiUrl+'admin/taxonomy/select?type=subject&s='+s , { 
                method: 'get', 
                headers: new Headers({
                    'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                }) 
                })
               .then( res => res.json() )
               .then( function(res){
                     self.subjects = res ;
               });
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
            }    
        },
        mounted(){
            this.query = this.$attrs.search ;
            this.getType();
            this.getTaxonomy('level' , 'level_id');
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