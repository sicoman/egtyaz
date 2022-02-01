<template>
    <div id="unitSearch">
            <div class="app-container">
                <el-form :model="query" label-position="left" label-width="150px">
                    <el-row>
                        
                            <el-col :span="6">
                                        <el-form-item :label="'Search'">
                                            <el-input type="text" v-model="query.s" placeholder="Search" />
                                        </el-form-item>
                            </el-col>

                            <el-col :span="6">
                                        <el-form-item :label="'Status'">
                                            <el-select v-model="query.status" placeholder="Status" filterable >
                                                    <el-option v-for="( v , k ) in bookingStatus" :key="k" :label="v" :value="parseInt(k)" />
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
    import settings from '@/settings' 

    export default {
        prop : ['search'] ,
        data(){
            return {
               loading: false, 
               query : {},
               bookingStatus : settings.holidaysStatus,
               type : [],
            }
        },
        methods : {
            setSearch(){
               this.$parent.query =  this.query ;
            },
            
            DoSearch(){
                this.$parent.gety() ;
            },
            CancelSearch(){
                var no = { "page" : this.query.page , "limit" : this.query.limit , "type" : this.query.type } ;
                this.query = no ;
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