<template>
    <div id="unitSearch">
            <div class="app-container">
                <el-form :model="query" label-position="left" label-width="150px">

                    <el-row>
                            <!-- 
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
                                        <el-form-item :label="'Guest'">
                                            <el-select
                                                v-model="query.user_id"
                                                filterable
                                                remote
                                                placeholder="Search Guests Names"
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
                            -->    

                            <el-col :span="6">
                                        <el-form-item :label="'Owner'">
                                            <el-select
                                                v-model="query.owner_id"
                                                filterable
                                                remote
                                                placeholder="Search Owners Names"
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
                                        <el-form-item :label="'Type'">
                                            <el-select
                                                v-model="query.type"
                                                filterable
                                                placeholder="Search Category" 
                                                :loading="loading">
                                                    <el-option
                                                        v-for="item in type"
                                                        :key="item.id"
                                                        :label="item.name"
                                                        :value="item.id">
                                                    </el-option>
                                            </el-select>
                                        </el-form-item>
                            </el-col>

                            <el-col :span="6" v-role="['admin' , 'manager']">
                                        <el-form-item :label="'Country'">
                                            <el-select
                                                v-model="query.country"
                                                filterable
                                                @change="getGovernment"
                                                placeholder="Search Country"
                                                :loading="loading">
                                                    <el-option
                                                        v-for="item in country"
                                                        :key="item.id"
                                                        :label="item.name"
                                                        :value="item.id">
                                                    </el-option>
                                            </el-select>
                                        </el-form-item>
                            </el-col>

                            <el-col :span="6" v-role="['admin' , 'manager']">
                                        <el-form-item :label="'Government'">
                                            <el-select
                                                v-model="query.government"
                                                filterable
                                                @change="getCity"
                                                placeholder="Search Government"
                                                :loading="loading">
                                                    <el-option
                                                        v-for="item in government"
                                                        :key="item.id"
                                                        :label="item.name"
                                                        :value="item.id">
                                                    </el-option>
                                            </el-select>
                                        </el-form-item>
                            </el-col>

                            <el-col :span="6" v-role="['admin' , 'manager']">
                                        <el-form-item :label="'City'">
                                            <el-select
                                                v-model="query.city"
                                                filterable
                                                @change="getArea"
                                                placeholder="Search City"
                                                :loading="loading">
                                                    <el-option
                                                        v-for="item in city"
                                                        :key="item.id"
                                                        :label="item.name"
                                                        :value="item.id">
                                                    </el-option>
                                            </el-select>
                                        </el-form-item>
                            </el-col>

                            <el-col :span="6" v-role="['admin' , 'manager']">
                                        <el-form-item :label="'Area'">
                                            <el-select
                                                v-model="query.area"
                                                filterable
                                                placeholder="Search Area"
                                                :loading="loading">
                                                    <el-option
                                                        v-for="item in area"
                                                        :key="item.id"
                                                        :label="item.name"
                                                        :value="item.id">
                                                    </el-option>
                                            </el-select>
                                        </el-form-item>
                            </el-col>

                            <el-col :span="6" v-role="['admin' , 'manager']">
                                        <el-form-item :label="'Area'" prop="query.area">
                                            <el-select
                                                v-model="query.area"
                                                filterable
                                                remote
                                                placeholder="Search Area"
                                                :remote-method="searchArea"
                                                :loading="loading">
                                                    <el-option
                                                        v-for="item in area_list"
                                                        :key="item.id"
                                                        :label="item.name"
                                                        :value="item.id">
                                                    </el-option>
                                            </el-select>
                                        </el-form-item>
                            </el-col>

                            <el-col :span="6">
                                        <el-form-item :label="'Status'">
                                            <el-select v-model="query.status" placeholder="Status" filterable >
                                                    <el-option :label="'None'" :value="''"></el-option>
                                                    <el-option v-for="( v , k ) in unitStatus" :key="k" :label="v" :value="parseInt(k)" />
                                            </el-select>
                                        </el-form-item>
                            </el-col>

                            <el-col :span="6">
                                        <el-form-item :label="'Feature'">
                                            <el-select v-model="query.featured" placeholder="Feature" filterable >
                                                    <el-option :label="'None'" :value="''"></el-option>
                                                    <el-option v-for="( v , k ) in unitFeature" :key="k" :label="v" :value="parseInt(k)" />
                                            </el-select>
                                        </el-form-item>
                            </el-col>

                    </el-row>

                    <el-row>
                            
                            <el-col :span="6">
                                        <el-form-item :label="'Cancel Policy'">
                                            <el-select v-model="query.cancel_id" placeholder="Cancel Policy" filterable >
                                                    <el-option :label="'None'" :value="''"></el-option>
                                                    <el-option
                                                        v-for="item in cancel"
                                                        :key="item.id"
                                                        :label="item.name"
                                                        :value="item.id">
                                                    </el-option>
                                            </el-select>
                                        </el-form-item>
                            </el-col>

                            <el-col :span="1">&nbsp;</el-col>
                            <el-col :span="10">
                                    <el-date-picker style="width:100%"
                                        v-model="query.date"
                                        type="daterange"
                                        range-separator="To"
                                        start-placeholder=" Filter start"
                                        end-placeholder=" Filter End">
                                    </el-date-picker>    
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
    import settings from '@/settings' 
    import UnitResource from '@/api/ezuru/units' ;
    import UserResource from '@/api/user' ;

    let Units = new UnitResource() ;
    let Users = new UserResource() ;

        import TaxonomyResource from '@/api/ezuru/taxonomy' ;
    const Taxonomy = new TaxonomyResource() ;

    import store from '@/store';
    import role from '@/directive/role' ;
    import permission from '@/directive/permission/index.js' ;

    export default {
        directives : { permission , role } ,
        prop : ['search'] ,
        data(){
            return {
               loading: false, 
               query : {},
               unitStatus : settings.unitStatus,
               unitFeature : settings.unitFeature,
               type : [],
               country : [],
               government : [],
               city : [],
               area : [],
               area_list : [] ,
               cancel : [] ,
               users_list : [],
               units_list : []
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
            getCountry(){
                let self = this ;
                fetch(settings.apiUrl+'admin/taxonomy/country?parent=0')
               .then( res => res.json() )
               .then( function(res){
                     self.country = res ; 
                     self.query.country = '' ;
                     self.query.government = '' ;
               });
            } ,
            getGovernment(){
                let self = this ;
                fetch(settings.apiUrl+'admin/taxonomy/government?parent='+this.query.country )
               .then( res => res.json() )
               .then( function(res){
                     self.government = res ; 
                     self.query.government = '' ;
                     self.city = [] ;
                     self.query.city = '' ;
                     self.area = [] ;
                     self.query.area = '' ;
               });

            } ,
            getCity(){
                let self = this ;
                fetch(settings.apiUrl+'admin/taxonomy/city?parent='+this.query.government )
               .then( res => res.json() )
               .then( function(res){
                     self.city = res ; 
                     self.query.city = '' ;
                     self.area = [] ;
                     self.query.area = '' ;
               });
            } ,
            getArea(){
                let self = this ;
                var ct = '' ;
                if( this.query.city && this.query.city > 0 ){
                        ct = '?parent='+this.query.city ;
                }
                fetch(settings.apiUrl+'admin/taxonomy/area'+ct )
               .then( res => res.json() )
               .then( function(res){
                     self.area = res ; 
               });
            } ,
            async searchArea(query) {
                if (query.length >= 3 ) {
                    this.loading = true;
                    let self = this ;
                    this.area_list = await Taxonomy.select( { 's' : query , 'type' : 'area' } ) ; 
                    this.loading = false;  
                } else {
                    this.area_list = [];
                }
            }, 
            getCancel(){
                let self = this ;
                fetch(settings.apiUrl+'cancel/select')
               .then( res => res.json() )
               .then( function(res){
                     self.cancel = res ; 
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
            this.getCountry();
            this.getCancel();
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