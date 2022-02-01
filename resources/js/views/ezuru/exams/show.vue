<template>
    <div id="booking" class="app-container">
         <el-row>
             <el-col :span="8">
                    <el-card v-if="exam.hasOwnProperty('id')" class="box-card" shadow="always">
                        <div slot="header" class="clearfix">
                            <span>Exam Information</span>
                        </div>
                        <div>
                             
                             <div><b>User</b> :  <span v-if="exam.user">{{ exam.user.name }}</span>   <el-divider></el-divider></div>  

                             <div><b>Date Created</b> :  {{ exam.created_at }}    <el-divider></el-divider></div>  

                             <div><b>Level</b> :  {{ exam.level.name }}    <el-divider></el-divider></div>  

                             <div><b>Subjects</b> :  <span v-for="subject in exam.subjects">{{ subject.subject.name }} ,</span>    <el-divider></el-divider></div>  
                             
                             <div><b>Questions</b> :  {{ exam.questions_count }}    <el-divider></el-divider></div>  

                             <div><b>Available Time</b> :  

                                <span v-if="exam.available_time <= 60">{{ exam.available_time }} Seconds </span>
                                <span v-else>
                                    <el-tooltip placement="top-start" :content="exam.available_time+' Seconds'">
                                        <span> About {{ (exam.available_time / 60).toFixed(0) }} Minute</span> 
                                    </el-tooltip>
                                </span>
                             
                             <el-divider></el-divider></div>   
                            
                        </div>
                    </el-card>
             </el-col>
             <el-col :span="1">&nbsp;</el-col>
             <el-col :span="15" shadow="always">

                    <el-card class="box-card">
                        <div slot="header" class="clearfix">
                            <span>Exam Results</span>
                        </div>
                        <div>
                             <el-table v-if="exam.hasOwnProperty('results') && exam.results.length > 0" :data="exam.results">
                                 <el-table-column align="center" label="Student">
                                    <template slot-scope="scope">
                                        <router-link :to="'?student='+scope.row.student.id">
                                            <span>{{ scope.row.student.name }}</span>
                                        </router-link>
                                    </template>
                                 </el-table-column>
                                  <el-table-column align="center" label="Result">
                                    <template slot-scope="scope">
                                            <span>{{ scope.row.percent }}%</span>
                                    </template>
                                 </el-table-column>
                                  <el-table-column align="center" label="Vaild">
                                    <template slot-scope="scope">
                                            <span>{{ scope.row.valid_answers }}</span>
                                    </template>
                                 </el-table-column>
                                  <el-table-column align="center" label="Wrong">
                                    <template slot-scope="scope">
                                            <span>{{ scope.row.wrong_answers }}</span>
                                    </template>
                                 </el-table-column>
                             </el-table>
                        </div>
                    </el-card>

                    <el-divider></el-divider>

                    <el-card class="box-card"  >
                        <div slot="header" class="clearfix">
                            <span>Answers</span>
                        </div>
                        <div>

                             <el-table v-if="answers.length > 0" :data="answers">
                                 
                                 <el-table-column align="center" label="Question">
                                    <template slot-scope="scope">
                                            <span>{{ scope.row.question.title }}</span>
                                    </template>
                                 </el-table-column>

                                 <el-table-column align="center" label="Student Answer">
                                    <template slot-scope="scope">
                                            <span>{{ scope.row.answer.text }}</span>
                                    </template>
                                 </el-table-column>

                                 <el-table-column align="center" label="Is True">
                                    <template slot-scope="scope">
                                            <span v-if="scope.row.is_true == 1"> <i class="el-icon-check"></i> </span>
                                            <span v-else> <i class="el-icon-close"></i> </span>
                                    </template>
                                 </el-table-column>

                                 <el-table-column align="center" label="Time Spent">
                                    <template slot-scope="scope">
                                            <el-tooltip placement="top-start" :content="scope.row.spent_time+' Seconds'">
                                                <span> About {{ (scope.row.spent_time / 60).toFixed(0) }} Minute</span> 
                                            </el-tooltip>
                                    </template>
                                 </el-table-column>
                                  
                             </el-table>
                             <el-alert v-else
                                title="We didn't found any results"
                                type="warning"> 
                            </el-alert>
                        </div>
                    </el-card>

             </el-col>
         </el-row>
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
        'name' : 'examShow' ,
        data(){
            return {
                table : [
                    {
                        "k" : "student" 
                    },
                    {
                        "k" : "date" 
                    },
                    {
                        "k" : "level" 
                    },
                    {
                        "k" : "subjects" 
                    }
                ] , 
                exam : {} ,
                answers : [] ,
                loading: true ,
                id : this.$route.params.id ,
                student : this.$route.query.student ,
            }
        },
        methods : {
              async gety(){
                this.loading = true ;   

                var listres = await Exams.get( this.id , this.student ) ;

                this.exam = listres.exam ;

                this.answers = listres.answers ;

                this.loading = false ;

             }
        },
        mounted() {
                var o = this ;
                o.gety() ;

        }
    }
</script>