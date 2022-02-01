<template>
    <div id="holidays" class="app-container">
         <!-- <router-link :to="'/ezuru/units/add'" ><el-button type="primary">Add Unit</el-button></router-link> -->
         <el-button type="primary" @click="search^=true"><i class="el-icon-search"></i> Search</el-button>
         <el-button type="warning" @click="exportTable()"><i class="el-icon-share"></i> Export</el-button>
         <hr />
         <Search v-if="search" :search="query" :types="type"></Search>
         <Table @reloadAgain="gety" :list="units" :loading="loading" :type="type"  ></Table>
         <el-row>
             <el-col :span="12">&nbsp;</el-col>
             <el-col :span="12">
                 <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="gety" />
             </el-col>
         </el-row>
         
    </div>
</template>

<script>
    import Pagination from '@/components/Pagination' ;

    import Table from './components/Table' ;

    import Search from './components/Search' ;

    import AccountsResource from '@/api/ezuru/accounts' ;

    import TableExport from 'tableexport' ;

    const Accounts = new AccountsResource() ;

    import permission from '@/directive/permission/index.js' ;

    export default {
        'name' : 'HolidaysManager' ,
        // directives : { permission } ,
        data(){
            return {
                units : {} ,
                type : this.$route.meta.type ,
                ptype : 'users',
                loading: true ,
                search : false ,
                query:{
                    type : this.$route.meta.type ,
                    limit : 10 ,
                },
                total : 0
            }
        },
        components : { Table , Pagination , Search } ,
        methods : {
              async gety(){
                this.loading = true ;    
                var listres = await Accounts.list( this.query ) ;
                this.units = listres.units ;
                this.total = parseInt(listres.pager.total) ; 
                this.query.page = parseInt(listres.pager.current_page) ;
                this.query.limit = parseInt(listres.pager.per_page)  ;

                this.loading = false ;
             }
             ,
             exportTable(){
                 TableExport(document.getElementsByClassName("el-table__body") , {
                     position:"top",
                     filename:"holidays"
                 });
             } 
        },
        mounted() { 
                this.gety() ;
        }
    }
</script>