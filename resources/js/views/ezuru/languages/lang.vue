<template>
    <div id="LangManager" class="app-container">

        <el-container>
                <el-header>
                    <h3> Search Translations   >>>   <el-input v-model="filter" @change="matchy" v-show="1==0" ></el-input> </h3>

                    <hr/>
                </el-header>
                <el-main height="" >
                    
                    <el-row >
                        <el-col :span="24" v-show="itemsLoaded">
                        
                            <el-form ref="form" :model="form" label-width="250px" v-if="itemsLoaded">

                                <div v-for="(value , key) in items" :key="key">    
                                    <el-form-item :label="key">
                                        <el-input v-model="items[key]"></el-input>
                                    </el-form-item>
                                </div>

                            </el-form>

                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="24">
                            <el-button @click="saveData()" type="primary">Save Translations</el-button>
                        </el-col>
                    </el-row>
                </el-main>
        </el-container>


            

    </div>
</template>

<script>
import settings from '@/settings' ;
import permission from '@/directive/permission/index.js' ;
import Cookies from 'js-cookie' ;
const TokenKey = 'Admin-Token' ;

export default {
    data(){
        return {
            form : {} ,
            itemsLoaded : false ,
            lang: 'ar' ,
            items : [] ,
            filter : '' ,
            matchSearch : []
        } 
    },
    methods : {
        matchy(filter){
            if( filter.length < 2 ){ return false; }
            var keys = [];
            //var pat = new RegExp( filter , "g" );
            var pat = "'"+filter+"'" ;

            Object.keys(this.items).forEach( (key , val) => {
                
                if ( pat.match(key) ){
                    console.log(key) ;
                    // this.matchSearch.push(key) ;
                }
            }) ;
        },
        loadData(){
            
            fetch( settings.apiUrl +'admin/lang/'+this.lang , {
                "method" : "get" ,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                }
            })
            .then( res => res.json() )
            .then( res => {
                 this.items = res ;
                 this.itemsLoaded = true ;
            });
        } ,
        saveData(){

            var o = this ;
            this.$confirm('This will Update '+this.lang+' Translation. Continue?', 'Warning', {
                        confirmButtonText: 'OK',
                        cancelButtonText: 'Cancel',
                        type: 'warning'
                        }).then( () => {

                            fetch( settings.apiUrl +'admin/lang/'+o.lang , {
                                    "method" : "post" ,
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                        'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                                    },
                                    body : JSON.stringify( o.items )
                                })
                                .then( res => res.json() )
                                .then( res => {
                                    if( res.res == 0 || res.res === false ){
                                        o.$message({
                                            type: 'error',
                                            message: 'Unable to Write Translation',
                                        });    
                                    }else{
                                        o.$message({
                                            type: 'success',
                                            message: 'Translations Changed Succefully',
                                        });
                                    }
                                    
                                });

                        });


            
                        
        } 
    },
    mounted() {

        this.lang = this.$route.meta.type ;

        this.loadData() ;
    }
}
</script>