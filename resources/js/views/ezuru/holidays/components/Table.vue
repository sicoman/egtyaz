<template>
    <el-row>
        <el-col :span="12">
            <h3>{{title}}</h3>
            <el-form label-position="left"  label-width="150px">
                                    <el-row>
                                        <el-col :span="22">
                                            <el-form-item label="Date">
                                                <el-date-picker style="width:100%"
                                                        v-model="row.date"
                                                        type="date"
                                                        placeholder="Pick a date"
                                                >
                                                </el-date-picker>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="22">
                                            <el-form-item label="Title">
                                                <el-input v-model="row.title" type="text"/>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="22">
                                            <el-form-item label="Status">
                                                <el-select v-model="row.status" value-key="status">
                                                      <el-option value="0" label="Disabled" /> 
                                                      <el-option value="1" label="Enabled" />      
                                                </el-select>   
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="22">
                                            <el-form-item>
                                                <el-button type="primary" @click="AddHoliday"> Save </el-button>
                                            </el-form-item>
                                        </el-col>

                                    </el-row>
                            </el-form>
        </el-col>
        <el-col :span="12">
<div id="PaymentsTable">
                <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">

                    <el-table-column align="center" label="ID" width="50">
                        <template slot-scope="scope">
                        <span>{{ scope.row.id }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Date">
                        <template slot-scope="scope">
                                {{ scope.row.date }}
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Title">
                        <template slot-scope="scope">
                                {{ scope.row.title }}
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Create Date">
                        <template slot-scope="scope">
                                <el-button-group>
                                    <el-tooltip :content="scope.row.created_at">
                                        <el-button size="mini" type="primary" icon="el-icon-date"></el-button>
                                    </el-tooltip>
                                </el-button-group>
                        </template>
                    </el-table-column>                 
                    
                    <el-table-column align="center" label="Status" v-permission="['manage flags status']">
                        <template slot-scope="scope">
                                <el-select v-model="scope.row.status" @change="handleActive(scope.row)">
                                    <el-option v-for="(v , k) in Status" :key="k" :label="v" :value="parseInt(k)"></el-option>
                                </el-select>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Options">
                        <template slot-scope="scope">
                                <el-button-group>
                                    <el-tooltip content="Delete" placement="top"   v-permission="['manage flags '+ptype+' delete']">
                                        <el-button size="mini" @click="handleDelete( scope.row.id , scope.row.title )" type="danger" icon="el-icon-delete"></el-button>
                                    </el-tooltip>
                                    <el-tooltip content="Edit" placement="top"   v-permission="['manage flags '+ptype+' delete']">
                                        <el-button size="mini" @click="handleEdit( scope.row )" type="warning" icon="el-icon-edit"></el-button>
                                    </el-tooltip>
                            </el-button-group>
                        </template>
                    </el-table-column>
                    </el-table>

    </div>
        </el-col>
    </el-row>
</template>

<script>
    import settings from '@/settings' ;
    import HolidaysResource from '@/api/ezuru/holidays' ;
    const Holidays = new HolidaysResource() ;

    const Vue = window.vue ;

    import permission from '@/directive/permission/index.js' ;

    export default {
        directives : { permission } ,
        data(){
            return {
                title : 'Add New Holiday' ,
                Status : settings.holidaysStatus ,
                sc : {} ,
                html : '',
                dialogVisible : false,
                row : {
                    id : 0 ,
                    date : '' ,
                    title : '' ,
                    status : 1
                },
                ptype : 'users'
            }
        },
        props : ['list' , 'loading' , 'reload' , 'type'],
        methods : {
            reloadAgain() {
                this.$emit('reloadAgain') ;
            },
            handleEdit(row){
                this.row = row ;
                this.title = 'Edit Holiday :'+row.title+' & '+row.date ;
            } ,
            AddHoliday(){
                var o = this ;
                Holidays.store(this.row).then(response => {
                    this.$message({
                        message: 'New Holiday ' + this.row.title +' has been created successfully.',
                        type: 'success',
                        duration: 5 * 1000,
                    });
                    this.row.id = 0 ;
                    this.row.title = '' ;
                    this.title = 'Add New Holiday' ;
                    o.reloadAgain();
                })
                .catch(error => {
                    console.log(error);
                });
            } ,
            handleDelete(id, name) {
                let o = this ;
                this.$confirm('This will permanently delete Holiday Date ' + id + '. Continue?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning',
                }).then(() => {

                    Holidays.destroy(id).then(response => {
                        this.$message({
                            type: 'success',
                            message: 'Delete completed',
                        });
                        o.reloadAgain();
                    }).catch(error => {
                    console.log(error);
                    });

                }).catch((e) => {
                    console.log(e);
                    this.$message({
                    type: 'info',
                    message: 'Delete canceled',
                    });
                });
            },
            handleActive : function(booking){

                        let o = this ;

                        this.$confirm('This will Update Holiday Status. Continue?', 'Warning', {
                        confirmButtonText: 'OK',
                        cancelButtonText: 'Cancel',
                        type: 'warning'
                        }).then(() => {
                            Holidays.active('' , booking.status , booking.id ).then(response => {
                                    this.$message({
                                        type: 'success',
                                        message: 'Status Changed Succefully',
                                    });
                                    o.reloadAgain();
                            }).catch(error => {
                                    console.log(error);
                                    this.$message({ type: 'danger', message: error });     
                            });
                        }).catch((e) => {
                            this.$message({ type: 'info', message: 'Update canceled'});          
                        });

            }
        },
        mounted(){
            if( this.type == 'user' ){
                    this.ptype = 'users' ;
            }else{
                    this.ptype = 'units' ;
            }
        }
    }
</script>
