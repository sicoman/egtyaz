<template>
    <div id="booking" class="app-container">
         <!-- <router-link :to="'/ezuru/units/add'" ><el-button type="primary">Add Unit</el-button></router-link> -->
         <el-button v-permission="['manage payments search']" type="primary" @click="search^=true"><i class="el-icon-search"></i> Search</el-button>
         <el-button v-permission="['manage payments export']" type="warning" @click="exportTable()"><i class="el-icon-share"></i> Export</el-button>
         <hr />
         <Search v-if="search"  :type="query.type" :search="query"></Search>
         <Table @reloadAgain="gety" :list="list"  :type="query.type" :loading="loading" ></Table>
         <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="gety" />
    </div>
</template>

<script>
    import Pagination from '@/components/Pagination' ;

    import Table from './components/Table' ;

    import Search from './components/Search' ;

    import PaymentsResource from '@/api/ezuru/payments' ;

    import TableExport from 'tableexport' ;

    import permission from '@/directive/permission/index.js' ;

    const Payments = new PaymentsResource() ;

    export default {
        'name' : 'bookingManager' ,
        // directives : { permission } ,
        data(){
            return {
                list : [] ,
                loading: true ,
                search : false ,
                query:{
                    limit : 10 ,
                    type : 'package'
                },
                total : 0
            }
        },

        components : { Table , Pagination , Search } ,

        methods : {
              async gety(){
                this.loading = true ;    
                var listres = await Payments.list( this.query ) ;

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
                var o = this ;
                var qs = this.$route.query ;
                    Object.keys(qs).forEach(k => {
                        o.query[k] = qs[k] ;
                    });
                o.gety() ;

                this.query.type = this.$route.meta.type ;
        }
    }
</script>