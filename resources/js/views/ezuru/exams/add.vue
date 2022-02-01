<template>
    <div id="booking" class="app-container">
         <el-row>
            <el-form :model="query" label-position="left" label-width="150px"> 
             <el-col :span="getSize(0)">
                    <el-card class="box-card" shadow="always">
                        <div slot="header" class="clearfix">
                            <span>Exam Information</span>
                        </div>
                        <el-row>
                             <el-col :span="24" v-if="type == 'free' || type == 'challenge'">
                                        <el-form-item :label="'Student'">
                                            <el-select
                                                v-model="exam.student_id"
                                                filterable
                                                remote
                                                placeholder="Search Students"
                                                :remote-method="searchUser"
                                                >
                                                    <el-option
                                                        v-for="item in users_list"
                                                        :key="item.id"
                                                        :label="item.name"
                                                        :value="item.id">
                                                    </el-option>
                                            </el-select>
                                        </el-form-item>
                            </el-col>
                            <el-col :span="24" v-if="type == 'mock' || type == 'challenge'">
                                        <el-form-item :label="'title'">
                                            <el-input v-model="exam.title" ></el-input>
                                        </el-form-item>
                            </el-col>
                            <el-col :span="24">
                                        <el-form-item :label="'Subjects'" >
                                            <el-select
                                                v-model="exam.subjects"
                                                filterable
                                                multiple
                                                placeholder="Search Subjects"
                                                >
                                                    <el-option
                                                        v-for="item in subjects"
                                                        :key="item.id"
                                                        :label="item.name"
                                                        :value="item.id">
                                                    </el-option>
                                            </el-select>
                                        </el-form-item>
                            </el-col>
                            <el-col :span="24">
                                        <el-form-item :label="'Skills'" >
                                            <el-select
                                                v-model="exam.skills"
                                                filterable
                                                multiple
                                                placeholder="Search Skills"
                                                >
                                                    <el-option
                                                        v-for="item in skills"
                                                        :key="item.id"
                                                        :label="item.name"
                                                        :value="item.id">
                                                    </el-option>
                                            </el-select>
                                        </el-form-item>
                            </el-col>
                             <el-col :span="24">
                                        <el-form-item :label="'Levels'">
                                            <el-select v-model="exam.level_id" placeholder="Levels" filterable >
                                                    <el-option v-for="level in levels" :key="level.id" :label="level.name" :value="parseInt(level.id)" />
                                            </el-select>
                                        </el-form-item>
                            </el-col>

                            <el-col :span="24">
                                        <el-form-item :label="'Questions Count'">
                                            <el-input v-model="exam.questions_count" ></el-input>
                                        </el-form-item>
                            </el-col>

                            <el-col :span="24">
                                        <el-form-item :label="'Available Time'">
                                            <el-input v-model="exam.available_time" >
                                                    <el-select style="width:100px" v-model="time_per" slot="prepend" placeholder="Time Per">
                                                        <el-option label="in Minutes" value="1"></el-option>
                                                        <el-option label="in Seconds" value="2"></el-option>
                                                    </el-select>
                                            </el-input>
                                        </el-form-item>
                            </el-col>


                            <el-col :span="24" v-if=" type == 'challenge'">
                                        <el-form-item :label="'Challengers'">
                                            <el-select
                                                v-model="exam.challengers"
                                                filterable
                                                multiple
                                                placeholder="Select Challengers"
                                                >
                                                    <el-option
                                                        v-for="item in challenge_users"
                                                        :key="item.id"
                                                        :label="item.name"
                                                        :value="item.id">
                                                    </el-option>
                                            </el-select>
                                        </el-form-item>
                            </el-col>

                            <el-col v-if="type == 'challenge'" :span="24">
                                    <el-form-item label="Competitions">
                                        <el-select v-model="exam.parent" filterable>
                                            <el-option key="0" value="" label="Without"></el-option>
                                            <el-option v-for="item in parents" :key="item.id" :value="item.id" :label="item.title" />      
                                        </el-select> 
                                    </el-form-item>
                            </el-col>


                            <el-col :span="24">
                                        <el-form-item :label="'Control Questions'">
                                            <el-switch v-model="control_exams"></el-switch>
                                        </el-form-item>

                            </el-col>
                            

                            <el-col :span="24">
                                        <el-form-item :label="' '">
                                            <el-button type="success" @click="saveExam" >
                                                    Save Exam
                                            </el-button>
                                        </el-form-item>

                            </el-col>
                            
                            
                        </el-row>
                    </el-card>
             </el-col>
             <el-col :span="getSize(1)">&nbsp;</el-col>
             <el-col  :span="getSize(2)" >

                    <el-card class="box-card">
                        <div slot="header" class="clearfix">
                            <span>Questions List</span>
                        </div>
                        <el-col :span="18">
                                        <el-form-item :label="'Questions'">
                                            <el-select
                                                v-model="question_id"
                                                multiple 
                                                filterable 
                                                remote
                                                placeholder="Search Questions"
                                                :remote-method="searchQuestions"
                                                >
                                                    <el-option
                                                        v-for="item in questions"
                                                        :key="item.id"
                                                        :label="item.title"
                                                        :value="item.id">
                                                    </el-option>
                                            </el-select>
                                        </el-form-item>
                            </el-col>
                            <el-col :span="6">
                                    <el-button type="primary" size="mini" @click="addThisToQuestions" icon="el-icon-plus"></el-button>
                            </el-col>
                        <div>
                             <el-table :data="selected_questions">
                                  <el-table-column align="center" label="Question">
                                        <template slot-scope="scope">
                                            <p>{{ scope.row.title.replace( /<.*?>/g, '' ) }}</p>
                                        </template>
                                  </el-table-column>

                                <el-table-column align="center" label="Delete">
                                    <template slot-scope="scope">
                                            <router-link :to="'/set-questions/questions/edit/'+scope.row.id">
                                                <el-button type="warning" icon="el-icon-edit"></el-button>
                                            </router-link>
                                    </template>
                                 </el-table-column>

                                  

                                  <el-table-column align="center" label="Delete">
                                    <template slot-scope="scope">
                                            <el-button @click="delQuestion( scope )" type="danger" icon="el-icon-delete"></el-button>
                                    </template>
                                 </el-table-column>
                             </el-table>

                             <el-divider> Or Get Questions from Mock <i class="el-icon-star-on"></i></el-divider>
                        
                            <el-col :span="24">
                                    <el-form-item label="Mock Tests To Copy From">
                                        <el-select v-model="exam.copy" filterable>
                                            <el-option key="0" value="" label="Dont Copy"></el-option>
                                            <el-option v-for="item in mocks" :key="item.id" :value="item.id" :label="item.title" />      
                                        </el-select> 
                                    </el-form-item>
                                    <el-alert
                                        title="When Chose Copy : We ignore Any Other Questions "
                                        type="error">
                                    </el-alert>
                                    <br />
                            </el-col>
                        </div>
                    </el-card>

             </el-col>
             </el-form>
         </el-row>
    </div>
</template>

<script>
    import Pagination from '@/components/Pagination' ;

    import Table from './components/Table' ;

    import Search from './components/Search' ;

    import ExamsResource from '@/api/ezuru/exams' ;

    import UsersResource from '@/api/user' ;

    import TableExport from 'tableexport' ;

    const Exams = new ExamsResource() ;

    const Users = new UsersResource() ;

    import permission from '@/directive/permission/index.js' ;

    import settings from '@/settings' ;

    import Cookies from 'js-cookie' ;
    
    const TokenKey = 'Admin-Token' ;

    import PostsResource from '@/api/ezuru/posts' ;
    const Post = new PostsResource() ;


    export default {
        directives : { permission } ,
        'name' : 'examShow' ,
        data(){
            return {
                exam : {
                    questions : [] ,
                    subjects : [] ,
                    level_id : [] ,
                    questions_count : 10 ,
                    available_time : 300 ,
                    title   : '' ,
                    challengers : [] ,
                    parent : ''  ,
                    copy : ''
                } ,
                time_per : "1" ,
                challenge_users : [] ,
                question_id : [] ,
                control_exams : 0 ,
                selected_questions : [] ,
                selected_subjects : [] ,
                selected_skills : [] ,
                selected_users : [] ,
                users_list : [] ,
                questions : [] ,
                subjects  : [] ,
                skills  : [] ,
                levels : [] ,
                loading: true ,
                id : this.$route.params.id ,
                student : '' ,
                type : 'free' ,
                query : {}  ,
                parents : [],
                mocks : []
            }
        },
        methods : {
               delQuestion(scope){
                   this.selected_questions.splice( scope.$index , 1 ) ;
               } ,
               async getMockT(){
                    let attachments = await Post.selectByType('competitions') ;
                    this.parents = attachments ;
                },
                async getMocks(){
                    let ex = await Exams.selectByType('mock') ;
                    this.mocks = ex ;
                },
               saveExam(){
                    if( 1 == 0 && this.selected_questions.length > 0 && this.selected_questions.length < this.exam.questions_count ){
                        this.$message.error('Please Select More '+( this.exam.questions_count - this.selected_questions.length )+' Question');
                    }else{
                        var tm = this.exam.available_time ;
                        if( this.time_per == "1" || this.time_per == 1 ){
                            tm = this.exam.available_time * 60 ;
                        }
                        
                        let data = {
                            id : this.id ,
                            type : this.type ,
                            title : this.exam.title ,
                            student_id : this.exam.student_id ,
                            subjects : this.exam.subjects ,
                            skills   : this.exam.skills ,
                            level_id : this.exam.level_id ,
                            parent : this.exam.parent ,
                            questions_count : this.exam.questions_count ,
                            available_time : tm ,
                            challengers : this.exam.challengers ,
                            questions : [] ,
                            copy : this.exam.copy
                        } ;
                        this.selected_questions.map(function(v){
                            data.questions.push( v.id ) ;
                        }) ;

                        

                        Exams.store(
                            data
                        ).then(response => {

                            this.$message({
                                message: 'Exam Saved successfully.',
                                type: 'success',
                                duration: 5 * 1000,
                            });


                            
                        })
                    }
               } ,
               addThisToQuestions(){
                   let o = this ;
                   let Q = {} ;  let QQ = {} ;
                   this.questions.map(function(que){
                       Q[que.id] = que.title ;
                   });
                   this.selected_questions.map(function(que){
                       QQ[que.id] = que.title ;
                   });
                   this.question_id.map( function(v){
                        if( Q.hasOwnProperty(v) && !QQ.hasOwnProperty(v) ){
                            o.selected_questions.push( { 'id' : v , 'title' : Q[v] } ) ;
                        }
                   });
                   this.question_id = [] ;
               } ,
               getSize(i){
                   if( (this.type == 'free' || this.type == 'challenge' )&& !this.control_exams ){
                       if( i == 0){
                           return 24 ;
                       }else{
                           return 0 ;
                       }
                   }else{
                       if( i == 0){
                           return 11 ;
                       }else if( i == 1){
                           return 1 ;
                       }else{
                           return 12 ;
                       }
                   }
               } ,

              async gety(){

                this.loading = true ;  

                var listres = await Exams.addEdit( this.id ) ;

                if( ! listres ) {
                    if( this.exam.type == 'challenge' ){
                        this.getUsers() ;
                    }
                    return false ;
                }

                this.exam = listres ;

                this.type = this.exam.type  ;

                this.exam.available_time = this.exam.available_time / 60 ;

                this.time_per = "1" ;

                this.selected_questions = [] ;

                this.selected_subjects  = [] ;

                this.selected_kills     = [] ;

                var o = this ;

                this.exam.questions.map( function(question){
                    if( question.question && question.question.hasOwnProperty('id')   ) {
                        o.selected_questions.push( {
                            'id' : question.question.id ,
                            'title' : question.question.title
                        } ) ;
                    }
                }) ;

                let sub = [] ;

                this.exam.subjects.map( function(subject){
                    sub.push( subject.subject_id ) ;
                }) ;

                this.exam.subjects = sub ;

                let skil = [] ;

                this.exam.skills.map( function(skill){
                    skil.push( skill.skill_id ) ;
                }) ;

                this.exam.skills = skil ;

                if( this.exam.student_id > 0 ) {
                    this.users_list.push( { 'id' : this.exam.user.id , 'name' : this.exam.user.name } ) ;
                }

                if( this.exam.type == 'challenge' ){

                    var chalgers = [] ;

                    if( this.exam.challenge ){
                        if( this.exam.challenge.challengers.length > 0 ){
                             this.exam.challenge.challengers.map( (ch) => {
                                 chalgers.push( ch.user_id ) ;
                            }) ;
                        }
                    }

                    this.exam.challengers = chalgers ;
                
                    this.getUsers() ;

                    this.getMockT() ;

                }

                this.loading = false ;

             },
             getUsers(){
                let self = this ;
                fetch(settings.apiUrl+'admin/users/list?s=*')
               .then( res => res.json() )
               .then( function(res){
                     self.challenge_users = res ; 
               });
            } ,
            getTaxonomy : function( type , variable , parent = 0 ){
                if( !type ){
                    type = category ;
                }
                let self = this ;
                fetch( settings.apiUrl+'admin/taxonomy/'+ type +'?parent='+parent , { 
                method: 'get', 
                headers: new Headers({
                    'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                }) 
                })
               .then( res => res.json() )
               .then( function(res){
                     self[variable] = res ;
               });
            },
            searchQuestions : function(s){

                if( s.length < 3 ){
                     return false ;
                }    

                let self = this ;
                let query = {
                    's' : s ,
                    'level_id' : this.exam.level_id ,
                    'subjects' : this.exam.subjects
                } ;

                fetch( settings.apiUrl+'admin/questions/select' , { 
                    method: 'post', 
                    headers: new Headers({
                        'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                    }),
                    body: JSON.stringify( query ) 
                })
               .then( res => res.json() )
               .then( function(res){
                     self.questions = res ;
               });
            },
            searchSubjects : function(s){
                let self = this ;
                fetch( settings.apiUrl+'admin/taxonomy/select?type=subject&s='+s , { 
                method: 'get', 
                headers: new Headers({
                    'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                }) 
                })
               .then( res => res.json() )
               .then( function(res){
                     self.subjects = res ;
               });
            },
            async searchUser(query) {
                if ( query.length > 3 ) {
                    this.loading = true;
                    let self = this ;
                    this.users_list = await Users.select( { 's' : query } ) ; 
                    this.loading = false;  
                } else {
                    this.users_list = [];
                }
            } 

        },
        mounted() {

                var o = this ;

                if( this.$route.hasOwnProperty('query') && this.$route.query.hasOwnProperty('type') ) {
                    this.type = this.$route.query.type ;
                    this.exam.type = this.type ;
                }

                if( this.$route.hasOwnProperty('query') && this.$route.query.hasOwnProperty('student') ) {
                    this.student = this.$route.query.student ;
                }


                this.getTaxonomy('level' , 'levels');
                this.getTaxonomy('subject' , 'subjects');
                this.getTaxonomy('skill' , 'skills') ;

                

                this.getMocks();

                o.gety() ;

                if( this.exam.type == 'challenge' || this.type == 'challenge' ){
                    this.getMockT() ;
                }
        }
    }
</script>