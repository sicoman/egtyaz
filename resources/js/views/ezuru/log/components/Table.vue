<template>
    <div id="logTable">

                <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">

                    <el-table-column align="center" label="ID" width="50">
                        <template slot-scope="scope">
                        <span>{{ scope.row.id }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="User">
                        <template slot-scope="scope">
                                <router-link :to="'/users/user/edit/'+scope.row.user.id"><span>{{ scope.row.user.name }}</span></router-link>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Operation">
                        <template slot-scope="scope">
                                {{ scope.row.log_type }}
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Model">
                        <template slot-scope="scope">
                                {{ scope.row.model }}
                        </template>
                    </el-table-column>

                    <!--
                        <el-table-column v-else-if='type == "unit"' align="center" label="Flagged Unit">
                            <template slot-scope="scope">
                                    <router-link :to="'/ezuru/units/edit/'+scope.row.unit.id"><span>{{ scope.row.unit.title }}</span></router-link>
                            </template>
                        </el-table-column>
                    -->

                    
                    <el-table-column align="center" label="Date">
                        <template slot-scope="scope">
                                <el-button-group>
                                    <el-tooltip :content="scope.row.created_at">
                                        <el-button size="mini" type="primary" icon="el-icon-date"></el-button>
                                    </el-tooltip>
                                </el-button-group>
                        </template>
                    </el-table-column>
<!--
                    <el-table-column align="center" label="More Flags">
                        <template slot-scope="scope">
                                <el-button-group>
                                    <el-tooltip :content="'Show More Flags On This'">
                                        <el-button size="mini" type="info" @click="sameThis( scope.row )" icon="el-icon-info"></el-button>
                                    </el-tooltip>
                                </el-button-group>
                        </template>
                    </el-table-column>
                    
                    
                    <el-table-column align="center" label="Status">
                        <template slot-scope="scope">
                                <el-select v-model="scope.row.status" @change="handleActive(scope.row)">
                                    <el-option v-for="(v , k) in Status" :key="k" :label="v" :value="parseInt(k)"></el-option>
                                </el-select>
                        </template>
                    </el-table-column>
-->

                    <el-table-column align="center" label="Delete" v-premission="['manage log delete']">
                        <template slot-scope="scope">
                                <el-button-group>
                                    <el-tooltip content="Delete" placement="top" >
                                        <el-button size="mini" @click="handleDelete( scope.row.id , scope.row.title )" type="danger" icon="el-icon-delete"></el-button>
                                    </el-tooltip>
                            </el-button-group>
                        </template>
                    </el-table-column>
                    </el-table>
    </div>
</template>

<script>
    import settings from '@/settings' ;
    import LogResource from '@/api/ezuru/log' ;
    const log = new LogResource() ;

    const Vue = window.vue;
    import permission from '@/directive/permission/index.js' ;

    export default { 
        directives : { permission } ,
        data(){
            return {
                Status : settings.flagStatus ,
                sc : {} ,
                html : '',
                dialogVisible : false
            }
        },
        props : ['list' , 'loading' , 'reload' , 'type'],
        methods : {
            reloadAgain() {
                this.$emit('reloadAgain') ;
            }, 
            
            handleView(scope, name) {
                let sc = scope.row ;
                this.sc = sc ;
                this.dialogVisible = true ;
            },
            handleClose(){
                this.dialogVisible = false;
            },

            handleDelete(id, name) {
                let o = this ;
                this.$confirm('This will permanently delete Payment ' + id + '. Continue?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning',
                }).then(() => {

                    log.destroy(id).then(response => {
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

                        this.$confirm('This will Update Unit Status. Continue?', 'Warning', {
                        confirmButtonText: 'OK',
                        cancelButtonText: 'Cancel',
                        type: 'warning'
                        }).then(() => {
                            log.active('' , booking.status , booking.id ).then(response => {
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
        computed : {
            
        }
    }
</script>
