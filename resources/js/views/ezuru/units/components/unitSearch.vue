<template>
    <div id="unitSearch">
            <div class="app-container">
                <el-form label-position="left" label-width="100px" style="max-width: 100%;">
                    <el-row>
                            <el-col :span="8">
                                        <el-form-item :label="'Search For'" prop="query.s">
                                            <el-input v-model="query.s" placeholder="Search For Unit Title , Address and Description" />
                                        </el-form-item>
                            </el-col>
                            <el-col :span="2">&nbsp;</el-col>
                            <el-col :span="6">
                                        <el-form-item :label="'Status'">
                                            <el-select v-model="query.status" placeholder="Status" filterable >
                                                    <el-option v-for="( v , k ) in unitStatus" :key="k" :label="v" :value="parseInt(k)" />
                                            </el-select>
                                        </el-form-item>
                            </el-col>
                            <el-col :span="2">&nbsp;</el-col>
                            <el-col :span="6">
                                        <el-form-item :label="'Featured'">
                                            <el-select v-model="query.featured" placeholder="Is Featured" filterable >
                                                    <el-option v-for="( v , k ) in unitStatus" :key="k" :label="v" :value="parseInt(k)" />
                                            </el-select>
                                        </el-form-item>
                            </el-col>
                    </el-row>

                    <el-row>
                            <el-col :span="8">
                                        <el-form-item :label="'Type'" prop="query.type">
                                            <el-select v-model="query.type" placeholder="Select Type" filterable >
                                                    <el-option v-for="( v , k ) in type" :key="v.id" :label="v.name" :value="parseInt(v.id)" />
                                            </el-select>
                                        </el-form-item>
                            </el-col>
                            <el-col :span="2">&nbsp;</el-col>
                            <el-col :span="6">
                                        <el-form-item :label="'Owner'">
                                            <el-select v-model="query.user_id" placeholder="Owner" filterable remote="" :remote-method="getUsers" >
                                                    <el-option v-for="( v , k ) in users" :key="v.id" :label="v.name" :value="parseInt(v.id)" />
                                            </el-select>
                                        </el-form-item>
                            </el-col>
                            <el-col :span="1">&nbsp;</el-col>
                            <el-col :span="6">
                                    <el-date-picker
                                        v-model="query.date"
                                        type="datetimerange"
                                        range-separator="To"
                                        start-placeholder="Date Add from"
                                        end-placeholder="Date Add to">
                                    </el-date-picker>    
                            </el-col>
                    </el-row>

                    <el-row>
                            <el-col :span="6" v-role="['admin' , 'manager']">
                                        <el-form-item :label="'Country'">
                                            <el-select
                                                v-model="query.country"
                                                
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

                            <el-col :span="1" v-role="['admin' , 'manager']">&nbsp;</el-col>
                            <el-col :span="5" v-role="['admin' , 'manager']">
                                        <el-form-item :label="'Government'">
                                            <el-select
                                                v-model="query.government"
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

                            <el-col :span="1" v-role="['admin' , 'manager']">&nbsp;</el-col>
                            <el-col :span="5" v-role="['admin' , 'manager']">
                                        <el-form-item :label="'City'">
                                            <el-select
                                                v-model="query.city"
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
                            <el-col :span="1" v-role="['admin' , 'manager']">&nbsp;</el-col>
                            <el-col :span="5" v-role="['admin' , 'manager']">
                                        <el-form-item :label="'Area'">
                                            <el-select
                                                v-model="query.area"
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
                            
                    </el-row>

                    <el-row>
                            <el-col :span="6">
                                        <el-button type="primary" @click="DoSearch">Search</el-button>
                                        &nbsp;
                                        <el-button type="warning" @click="CancelSearch()">Cancel</el-button>
                            </el-col>
                    </el-row>

                </el-form>
            </div>
    </div>
</template>

<script>
    import settings from '@/settings' ;
    import TaxonomyResource from '@/api/ezuru/taxonomy' ;
    const Taxonomy = new TaxonomyResource() ;

    import role from '@/directive/role' ;
    import permission from '@/directive/permission/index.js' ;

    export default {
        directives : { permission , role } ,
        prop : ['search'] ,
        data(){
            return {
               query : {},
               unitStatus : settings.unitStatus,
               unitFeature : settings.unitFeature,
               type : [],
               users : [],
               country : [],
               city : [],
               government:[],
               area:[],
               loading:false
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
            getUsers(s){
                let self = this ;
                fetch(settings.apiUrl+'admin/users/list?s='+s)
               .then( res => res.json() )
               .then( function(res){
                     self.users = res ; 
               });
            } ,
            DoSearch(){
                this.$parent.getUnits() ;
            },
            CancelSearch(){
                var no = { "page" : this.query.page , "limit" : this.query.limit } ;
                this.query = no ;
            },
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
                     self.query.area = '' ; 
               });
            } ,
        },
        mounted(){
            this.query = this.$attrs.search ;
            this.getType();
            this.getUsers();
            this.getCountry();
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

<style scoped>
    #unitSearch{
        padding:20px;
        background:#f1f1f1
    }
</style>