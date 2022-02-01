<template>
    <div id="BadgeTable">
                <el-button type="primary" @click="badge.show = true" >Add New Badge</el-button><br />
                <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
                    <el-table-column align="center" label="ID" width="50">
                        <template slot-scope="scope">
                        <span>{{ scope.row.id }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column v-if="type == 'user'" align="center" label="User">
                        <template slot-scope="scope">
                            <span>{{ scope.row.user.name }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column v-else-if="type == 'unit'" align="center" label="Unit">
                        <template slot-scope="scope">
                            <span>{{ scope.row.unit.title }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Badge Name" >
                        <template slot-scope="scope">
                            <span>
                                {{ scope.row.badge.name }}
                            </span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Badge" >
                        <template slot-scope="scope">
                            <span>
                                <img :src="scope.row.badge.photo" style="width:50px;height:50px;" :title="scope.row.badge.name" />
                            </span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Delete"  v-permission="['manage booking delete']"> 
                        <template slot-scope="scope">
                                <el-button-group>
                                    
                                    <el-tooltip content="Delete" placement="top" >
                                        <el-button size="mini" @click="handleDelete( scope.row.id )" type="danger" icon="el-icon-delete"></el-button>
                                    </el-tooltip>

                            </el-button-group>
                        </template>
                    </el-table-column>
                    </el-table>

                    <el-dialog
                            title="Add Badge"
                            :visible.sync="badge.show"
                            :append-to-body="true"
                            width="30%"
                    >
                            <div id="addBadgeForm">

                                 <el-form :model="form">
                                    <el-form-item label="Badge" :label-width="'150px'">
                                    <el-select v-model="badge.badge_id" placeholder="Please select a Badge">
                                        <el-option v-for="badge in badge_list" :label="badge.name" :key="badge.id" :value="badge.id"></el-option>
                                    </el-select>
                                    </el-form-item>
                                </el-form>

                            </div>

                            <span slot="footer" class="dialog-footer">
                                <el-button @click="badge.show = false">Cancel</el-button>
                                <el-button type="primary" @click="saveBadge">Confirm</el-button>
                            </span>
                    </el-dialog>
    </div>
</template>

<script>
    import settings from '@/settings' ;
    import BadgesResource from '@/api/ezuru/badges' ;
    const Badges = new BadgesResource() ;
    import permission from '@/directive/permission/index.js' ;
    import tinymce  from '@/components/Tinymce/index.vue' ;
    export default {
        directives : { permission } ,
        components : { tinymce } ,
        data(){
            return {
                Status : settings.bookingStatus ,
                badge : {
                    show : false ,
                    badge_id : 0 
                },
                badge_list : []
            }
        },
        props : ['list' , 'loading' , 'reload' , 'type' , 'u_u_id'],
        methods : {
            reloadAgain() {
                this.$emit('reloadAgain') ;
            }, 
            handleDelete(id) {
                let o = this ;
                this.$confirm('This will permanently delete Badge .. Continue?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning',
                }).then(() => {

                    Badges.destroy(id).then(response => {
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
            saveBadge(){
                 var o = this ;
                 var object = {
                     'type' : this.type ,
                     'u_u_id' : this.u_u_id ,
                     'badge' : this.badge.badge_id
                 };
                 Badges.store(object).then(response => {
                    this.$message({
                        message: 'Badge Attached successfully.',
                        type: 'success',
                        duration: 5 * 1000,
                    });
                    this.badge.badge_id = 0 ;
                    o.reloadAgain();
                    this.badge.show = false;
                })
                .catch(error => {
                    this.$message({
                        message: 'Badge Not Attached.',
                        type: 'error',
                        duration: 5 * 1000,
                    });
                }); 
            },
            getBadges(){
                let self = this ;
                fetch(settings.apiUrl+'admin/taxonomy/'+this.type+'_badge?parent=0')
               .then( res => res.json() )
               .then( function(res){
                     self.badge_list = res ; 
               });
            } ,
        },
        computed : {
            
        },
        watch : {
            
        },
        mounted(){
            this.getBadges();
        }
    }
</script>
