<template>
    <div id="ExamsTable">
                <br />
                    <el-button type="primary" @click="AddEditExam()">
                            <i class="el-icon-add"></i> Add New Exam 
                    </el-button>
                <br /><br />
                <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
                    <el-table-column align="center" label="ID" width="50">
                        <template slot-scope="scope">
                            <router-link :to="'/questions/exams/show/'+scope.row.id"> {{ scope.row.id }} </router-link>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" v-if="type == 'mock' || type == 'challenge'" label="Title" width="150">
                        <template slot-scope="scope">
                            <span v-html="scope.row.title">  </span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Date">
                        <template slot-scope="scope">
                                <el-button-group>
                                    <el-tooltip :content="'Created at '+scope.row.created_at">
                                        <el-button type="warning" size="mini"  icon="el-icon-date"></el-button>
                                    </el-tooltip>
                                </el-button-group>
                        </template>
                    </el-table-column>

                    <el-table-column v-if="type == 'free'" align="center" label="Student">
                        <template slot-scope="scope">
                                <router-link v-if="scope.row.user" :to="'/users/user/edit/'+scope.row.user.id"><span>{{ scope.row.user.name }}</span></router-link>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Level">
                        <template slot-scope="scope">
                            <div v-if="scope.row.level && scope.row.level.hasOwnProperty('id')">
                                <span v-if="scope.row.level_id">{{ scope.row.level.name }}</span>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Subjects">
                        <template slot-scope="scope">
                            <div v-if="scope.row.subjects.length > 0">
                                <span v-for="subject in scope.row.subjects">
                                    <span v-if="subject.subject && subject.subject.hasOwnProperty('name')">
                                    {{ subject.subject.name }} ,
                                    </span>
                                </span>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Questions">
                        <template slot-scope="scope">
                                <span>{{ scope.row.questions_count }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column  align="center" label="Time">
                        <template slot-scope="scope">
                                <span v-if="scope.row.available_time <= 60">{{ scope.row.available_time }} Seconds </span>
                                <span v-else>
                                    <el-tooltip placement="top-start" :content="scope.row.available_time+' Seconds'">
                                        <span> About {{ (scope.row.available_time / 60).toFixed(0) }} Minute</span> 
                                    </el-tooltip>
                                </span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Delete"  v-permission="['manage exam delete']"> 
                        <template slot-scope="scope">
                                <el-button-group>
                                    <el-tooltip content="Delete" placement="top" >
                                        <el-button size="mini" @click="handleDelete( scope.row.id , scope.row.title )" type="danger" icon="el-icon-delete"></el-button>
                                    </el-tooltip>
                                </el-button-group>
                                <el-button-group>
                                    <el-tooltip content="View" placement="top" >
                                        <router-link :to="'/questions/exams/show/'+scope.row.id"> 
                                                <el-button size="mini" type="primary" icon="el-icon-view"></el-button>
                                         </router-link>
                                    </el-tooltip>
                                </el-button-group>
                                <el-button-group>
                                    <el-tooltip content="View" placement="top" >
                                        <router-link :to="'/questions/exams/edit/'+scope.row.id"> 
                                                <el-button size="mini" type="warning" icon="el-icon-edit"></el-button>
                                         </router-link>
                                    </el-tooltip>
                                </el-button-group>
                        </template>
                    </el-table-column>
                    </el-table>

    </div>
</template>

<script>
    import settings from '@/settings' ;
    import ExamsResource from '@/api/ezuru/exams' ;
    const Exams = new ExamsResource() ;
    import permission from '@/directive/permission/index.js' ;
    import tinymce  from '@/components/Tinymce/index.vue' ;
    export default {
        directives : { permission } ,
        components : { tinymce } ,
        data(){
            return {
                Status : settings.bookingStatus ,
            }
        },
        props : ['list' , 'loading' , 'reload' , "type" ],
        methods : {
            AddEditExam(id = ''){
                if( id == '' ){
                    this.$router.push('/questions/exams/add?type='+this.type) ;
                }else{
                    this.$router.push('/questions/exams/edit/'+id ) ;
                }
            },
            reloadAgain() {
                this.$emit('reloadAgain') ;
            }, 
            handleDelete(id, name) {
                let o = this ;
                this.$confirm('This will permanently delete Exam ' + id + '. Continue?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning',
                }).then(() => {

                    Exams.destroy(id).then(response => {
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
            
            
        },
        computed : {
            
        },
        watch : {
            
        }
    }
</script>

<style scoped>
span.x { 
        width: 100%;
        position: relative;
        margin-right:10px;    
}

span.x:before { content: '';
        position: absolute;
        bottom: 50%;
        border-bottom: 2px red solid;
        width: 100%;
        z-index:0;
}

</style>