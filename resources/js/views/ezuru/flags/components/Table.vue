<template>
    <div id="PaymentsTable">
                <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
                    <el-table-column align="center" label="ID" width="50">
                        <template slot-scope="scope">
                        <span>{{ scope.row.id }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Report By">
                        <template slot-scope="scope">
                                <div v-if="scope.row.by">
                                    <router-link  :to="'/users/user/edit/'+scope.row.by.id"><span>{{ scope.row.by.name }}</span></router-link>
                                </div>
                        </template>
                    </el-table-column>

                    <el-table-column v-if='type == "user"' align="center" label="Reported User">
                        <template slot-scope="scope">
                                <router-link :to="'/users/user/edit/'+scope.row.user.id"><span>{{ scope.row.user.name }}</span></router-link>
                        </template>
                    </el-table-column>
                    <el-table-column v-else-if='type == "unit"' align="center" label="Reported Unit">
                        <template slot-scope="scope">
                                <router-link :to="'/ezuru/units/edit/'+scope.row.unit.id"><span>{{ scope.row.unit.title }}</span></router-link>
                        </template>
                    </el-table-column>

                    
                    <el-table-column align="center" label="Reporting Note">
                        <template slot-scope="scope">
                                <span v-text="scope.row.description"></span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Reporting Date">
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
-->                    
                    
                    <el-table-column align="center" label="Status" v-permission="['manage flags status']">
                        <template slot-scope="scope">
                                <el-select v-model="scope.row.status" @change="handleActive(scope.row)">
                                    <el-option v-for="(v , k) in Status" :key="k" :label="v" :value="parseInt(k)"></el-option>
                                </el-select>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Delete">
                        <template slot-scope="scope">
                                <el-button-group>
                                    <el-tooltip content="View" placement="top"   v-permission="['manage flags '+ptype+' view']">
                                        <el-button size="mini" @click="handleView( scope , scope.row.title )" type="warning" icon="el-icon-view"></el-button>
                                    </el-tooltip>
                                    <el-tooltip content="Delete" placement="top"   v-permission="['manage flags '+ptype+' delete']">
                                        <el-button size="mini" @click="handleDelete( scope.row.id , scope.row.title )" type="danger" icon="el-icon-delete"></el-button>
                                    </el-tooltip>
                            </el-button-group>
                        </template>
                    </el-table-column>
                    </el-table>

                    <el-dialog title="View Flag Note" :visible.sync="dialogVisible" width="500px" :before-close="handleClose">
                        <el-row>
                              {{ sc.description }}
                        </el-row>
                        
                        <span slot="footer" class="dialog-footer">
                            <el-button @click="dialogVisible = false">Close</el-button>
                        </span>
                    </el-dialog>
    </div>
</template>

<script>
    import settings from '@/settings' ;
    import FlagsResource from '@/api/ezuru/flags' ;
    const Flags = new FlagsResource() ;

    const Vue = window.vue ;

    import permission from '@/directive/permission/index.js' ;

    export default {
        directives : { permission } ,
        data(){
            return {
                Status : settings.flagStatus ,
                sc : {} ,
                html : '',
                dialogVisible : false,
                ptype : 'users'
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
                this.$confirm('This will permanently delete Reporting ' + id + '. Continue?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning',
                }).then(() => {

                    Flags.destroy(id).then(response => {
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
                            Flags.active('' , booking.status , booking.id ).then(response => {
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
