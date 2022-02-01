<template>
    <div id="units" class="app-container">
         <router-link v-permission="['manage units add']" :to="'/ezuru/units/add'" ><el-button type="primary">Add Unit</el-button></router-link>
         <el-button v-permission="['manage units search']" type="primary" @click="search^=true"><i class="el-icon-search"></i> Search</el-button>
         <el-button v-permission="['manage units export']" type="warning" @click="exportTable()"><i class="el-icon-share"></i> Export</el-button>
         <hr />
         <unitSearch v-permission="['manage units search']" v-if="search" :search="query"></unitSearch>
         <unitTable @reloadAgain="getUnits" :list="list" :loading="loading" ></unitTable>
         <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getUnits" />
    </div>
</template>

<script>
    import Pagination from '@/components/Pagination' ;

    import unitTable from './components/unitTable' ;

    import unitSearch from './components/unitSearch' ;

    import UnitResource from '@/api/ezuru/units' ;

    import TableExport from 'tableexport' ;

    const Units = new UnitResource() ;

    import role from '@/directive/role' ;
    import permission from '@/directive/permission/index.js' ;

    export default {
        directives : { permission , role } ,
        'name' : 'unitsManager' ,
        data(){
            return {
                list : [] ,
                loading: true ,
                search : false ,
                query:{

                },
                total : 0
            }
        },

        components : { unitTable , Pagination , unitSearch } ,

        methods : {
              async getUnits(){
                this.loading = true ;    
                var listUnits = await Units.list( this.query ) ;

                this.list = listUnits.data ;
                this.total = parseInt(listUnits.total) ;
                this.query.page = parseInt(listUnits.current_page) ;
                this.query.limit = parseInt(listUnits.per_page)  ;

                this.loading = false ;
             }
             ,
             exportTable(){
                 TableExport(document.getElementsByClassName("el-table__body") , {
                     position:"top",
                     filename:"units"
                 });
             }
        },
        mounted() {
                var o = this ;
                o.getUnits() ;
                
        }
    }
</script>