<template>
    <div id="flags" class="app-container">
         <!-- <router-link :to="'/ezuru/units/add'" ><el-button type="primary">Add Unit</el-button></router-link> -->
         <el-button v-permission="['manage flags '+ptype+' search']" type="primary" @click="search^=true"><i class="el-icon-search"></i> Search</el-button>
         <el-button v-permission="['manage flags '+ptype+' export']" type="warning" @click="exportTable()"><i class="el-icon-share"></i> Export</el-button>
         <hr />
         <Search v-if="search" :search="query" :types="type"></Search>
         <Table @reloadAgain="gety" :list="list" :loading="loading" :type="type"  ></Table>
         <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="gety" />
    </div>
</template>

<script>
    import Pagination from '@/components/Pagination' ;

    import Table from './components/Table' ;

    import Search from './components/Search' ;

    import FlagsResource from '@/api/ezuru/flags' ;

    import TableExport from 'tableexport' ;

    const Flags = new FlagsResource() ;

    import permission from '@/directive/permission/index.js' ;

    export default {
        'name' : 'FlagsManager' ,
        directives : { permission } ,
        data(){
            return {
                list : [] ,
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
                var listres = await Flags.list( this.query ) ;
                this.list = listres.data ;
                this.total = parseInt(listres.total) ; 
                this.query.page = parseInt(listres.current_page) ;
                this.query.limit = parseInt(listres.per_page)  ;

                this.loading = false ;
             }
             ,
             exportTable(){
                 TableExport(document.getElementsByClassName("el-table__body") , {
                     position:"top",
                     filename:"Payments"
                 });
             } 
        },
        mounted() { 

                if( this.type == 'user' ){
                    this.ptype = 'users' ;
                }else{
                    this.ptype = 'units' ;
                }

                var o = this ;

                o.gety() ;


                
        }
    }
</script>