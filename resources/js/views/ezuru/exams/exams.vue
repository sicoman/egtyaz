<template>
    <div id="booking" class="app-container">
         <!-- <router-link :to="'/ezuru/units/add'" ><el-button type="primary">Add Unit</el-button></router-link> -->
         <el-button  v-permission="['manage booking search']" type="primary" @click="search^=true"><i class="el-icon-search"></i> Search</el-button>
         <el-button  v-permission="['manage booking export']" type="warning" @click="exportTable()"><i class="el-icon-share"></i> Export</el-button>
         <hr />
         <Search v-if="search" :type="query.type" :search="query"></Search>
         <Table :type="query.type" @reloadAgain="gety" :list="list" :loading="loading" ></Table>
         <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="gety" />
    </div>
</template>

<script>
    import Pagination from '@/components/Pagination' ;

    import Table from './components/Table' ;

    import Search from './components/Search' ;

    import ExamsResource from '@/api/ezuru/exams' ;

    import TableExport from 'tableexport' ;

    const Exams = new ExamsResource() ;

    import permission from '@/directive/permission/index.js' ;

    export default {
        directives : { permission } ,
        'name' : 'bookingManager' ,
        data(){
            return {
                list : [] ,
                loading: true ,
                search : true ,
                query:{
                    limit : 10 ,
                    type : this.$route.meta.type
                },
                total : 0
            }
        },

        components : { Table , Pagination , Search } ,

        methods : {
              async gety(){
                this.loading = true ;    
                var listres = await Exams.list( this.query ) ;

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
                     filename:"exams"
                 });
             }
        },
        mounted() {
                var o = this ;
                o.gety() ;
        }
    }
</script>