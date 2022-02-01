<template>
    <div id="Question" class="app-container">
        <h1 class="title">  </h1>

            <el-container>
                <el-header v-show="!show_list">
                    <h3>
                     {{ tit }} 
                     <router-link to="/set-questions/questions">
                        <el-button type="success" class="el-left"  v-permissionx="['manage user search']"  >
                            <i class="el-icon-list"></i> Show List {{ type }}
                            </el-button> 
                     </router-link>   
                     </h3>
                    <hr/>
                </el-header>
               
                <el-main height="">
                    <el-row >
                        <el-col :span="24" v-show="!show_list"> <!--  v-permission="['manage Questions add']" -->
                            <el-form label-position="left"  label-width="150px">
                                    <el-row>

                                        <el-col :span="11">
                                            <el-form-item label="Category">
                                                <el-select v-model="category_id" filterable>
                                                      <el-option v-for="item in categories" :key="item.id" :value="item.id" :label="item.name" />      
                                                </el-select>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="1"></el-col>
                                        <el-col :span="11">
                                            <el-form-item label="Subject">
                                                <el-select v-model="subject_id" filterable>
                                                      <el-option v-for="item in subjects" :key="item.id" :value="item.id" :label="item.name" />      
                                                </el-select>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="1"></el-col>
                                        <el-col :span="11">
                                            <el-form-item label="Skills">
                                                <el-select v-model="skill_id" filterable>
                                                      <el-option v-for="item in skills" :key="item.id" :value="item.id" :label="item.name" />      
                                                </el-select>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="1"></el-col>
                                        <el-col :span="11">
                                            <el-form-item label="Level">
                                                <el-select v-model="level_id" filterable>
                                                      <el-option v-for="item in levels" :key="item.id" :value="item.id" :label="item.name" />      
                                                </el-select>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="1"></el-col>
                                        <el-col :span="11">
                                            <el-form-item label="Status">
                                                <el-select v-model="status" value-key="status">
                                                      <el-option value="0" label="Disabled" /> 
                                                      <el-option value="1" label="Enabled" />      
                                                </el-select>   
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="1"></el-col>
                                        <el-col :span="11">
                                            <el-form-item label="Question Attachment">
                                                <el-select v-model="attachment_id" filterable>
                                                      <el-option key="0" value="0" label="Without"></el-option>
                                                      <el-option v-for="item in attachments" :key="item.id" :value="item.id" :label="item.title" />      
                                                </el-select> 
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="1"></el-col>
                                        <el-col :span="11">
                                            <el-form-item label="Add to exams">
                                                <el-select v-model="exam_id" multiple filterable>
                                                      <el-option v-for="item in exams" :key="item.id" :value="item.id" :label="item.title" />      
                                                </el-select> 
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="24">
                                            <el-form-item label="Question">
                                                <tinymce :height="300" v-model="title"  ref="title" />
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="24">
                                            <el-form-item label="Answer Description">
                                                <tinymce :height="300" v-model="description"  ref="description" />
                                            </el-form-item>
                                        </el-col>


                                        <el-col :span="24">
                                            <hr />
                                        </el-col>

                                        <el-col :span="24">
                                                <el-button type="warning" icon="el-icon-plus" @click="answers.push({'text' : '' , 'is_true' : '0' , 'status' : '0'});">Add Item</el-button>

                                                <el-table :data="answers" style="width: 100%">

                                                    <el-table-column prop="text" label="Answer" >
                                                            <template slot-scope="scope">
                                                                <tinymcesm  :height="50" @change="setContentforAnswer( 'title'+scope.index , scope )" v-model="scope.row.text"  :ref="'title'+scope.index" />
                                                            </template>
                                                    </el-table-column>

                                                    <el-table-column prop="text" label="Is True" width="70">
                                                            <template slot-scope="scope">
                                                                <el-switch v-model="scope.row.is_true" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0"></el-switch>
                                                            </template>
                                                    </el-table-column>

                                                    <el-table-column prop="text" label="Status" width="70">
                                                            <template slot-scope="scope">
                                                                <el-switch v-model="scope.row.status" active-color="#0000ff" inactive-color="#cccccc" :active-value="1" :inactive-value="0"></el-switch>
                                                            </template>
                                                    </el-table-column>

                                                    <el-table-column prop="text" label="Delete" width="50">
                                                            <template slot-scope="scope">
                                                                      <el-button type="danger" icon="el-icon-delete" @click="answers.splice(scope.$index, 1);" circle></el-button>
                                                            </template>
                                                    </el-table-column>
                                                    
                                                </el-table>
                                        </el-col>

                                         <el-col :span="24">
                                            <hr />
                                         </el-col>

                             
                                        <el-col :span="22">
                                            <el-form-item>
                                                <el-button type="primary" @click="AddQuestion"> Save & Continue </el-button>
                                            </el-form-item>
                                        </el-col>

                                    </el-row>
                            </el-form> 
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
    import QuestionResource from '@/api/ezuru/questions' ;
    const Question = new QuestionResource() ;

    import PostsResource from '@/api/ezuru/posts' ;
    const Post = new PostsResource() ;

    import ExamsResource from '@/api/ezuru/exams' ;
    const Exams = new ExamsResource() ;

    import Tinymce from '@/components/Tinymce';
    import Tinymcesm from '@/components/Tinymce/small';

    export default {
        directives : { permission } ,
        components : { Tinymce, Tinymcesm } , 
        data() {
           return {
               "show_list" : false ,
               "tit" : "Add New Question" ,
               "type" : 'questions' ,
               "ptype" : this.getPtype() ,
               "id"   : 0 ,
               "title" : '' ,
               "category_id" : "" ,
               "level_id" : '' ,
               "status" : '1' ,
               "subject_id" : "" ,
               "skill_id" : "" ,
               "attachment_id" : "" ,
               "exam_id" : "" ,
               taxData : [] ,
               description:"" ,
               search : {
                   's' : '' ,
                   'status' : '',
                   'visible' : false
               },
               pagination : {

               },
               setting : {

               },
               apiUrl  : settings.apiUrl+'admin/upload' ,
               parent : this.$route.params.type ,
               listLoading : false,
               categories : [] ,
               levels : [] ,
               subjects : [] ,
               skills : [] ,
               attachments : [], 
               exams : [] ,
               answers : [
                   {
                       'text' : '' ,
                       'is_true' : 0 ,
                       'status' : 1 ,

                   },
                   {
                       'text' : '' ,
                       'is_true' : 0 ,
                       'status' : 1 ,

                   },
                   {
                       'text' : '' ,
                       'is_true' : 0 ,
                       'status' : 1 ,

                   },
                   {
                       'text' : '' ,
                       'is_true' : 0 ,
                       'status' : 1 ,
                   },
                ],
              attachtoExams : [] , 
              questions_to_attach : [] , 
              bulkAction: '',  
              selectType: 'only_selected',
              selections: [{
                  "value":"only_selected",
                  "label":"Selected only"
              },
              {
                  "value":"all",
                  "label":"Select all questions from database"
              }],
              bulkActions: [{
                  "value":"attach_to_exam",
                  "label":"Attach to exam"
              },
              {
                  "value":"delete",
                  "label":"Delete"
              }]  ,
              bulkActionAttachToExam: false
           }
        },
        methods : {
            setContentforAnswer(ref){
                console.log(ref) ;
            },
            sendRequest(){
                var o = this ;
                var request_type = this.bulkAction ;
                var questions_ids = [] ;
                
                this.questions_to_attach.map(function(q){
                    questions_ids.push( q.id ) ;
                }) ;

                var conf = 'This will permanently delete the questions. Continue?' ;

                if( request_type == 'attach_to_exam' ) {
                    conf = 'Questions will attach to selected exams?' ;
                }

                this.$confirm( conf , 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'danger'
                 }).then(() => {

                    var uri = settings.apiUrl+'admin/questions/bulk/'+request_type ;

                    var jso = JSON.stringify( { "questions" : questions_ids , 'exams' : this.attachtoExams  } ) ;

                    console.log( [
                        uri , Cookies.get(TokenKey)
                    ] )
                    
                    fetch( uri , {
                        "method" : "POST" ,
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                            'Content-Type': 'application/json'
                        },
                        body : jso
                    })
                    .then( res => res.json() )
                    .then( function(res){
                            if( res.res == 0 ){
                                o.$message.error( 'Failed to do this action' );
                            }else{
                                o.$message.success('Saved Succefully');
                                o.getList() ;
                            }
                    });

                }).catch(() => {
                    this.$message({ type: 'info', message: 'Action canceled'});          
                });
            } ,
            handleSelectionChange(val){
                this.questions_to_attach = val ;
            } ,
            UpdateQuestion : function(Question){
                 this.id = Question.id ;
                 this.show_list = false ;
                 this.$refs.title.setContent( Question.title ) ;
                 this.category_id = Question.category_id ;
                 this.status = Question.status.toString() ;
                 this.level_id = Question.level_id ;
                 this.skill_id  = Question.skill_id ;
                 this.subject_id  = Question.subject_id ;
                 this.answers  = Question.answers ;
                 this.attachment_id  = Question.attachment_id ;
                 this.exam_id = [] ;
                 this.tit    = 'Update Question No: '+Question.id ;
                 this.$refs.description.setContent( Question.description ) ;
                 var o = this.answers ;
                 var ix = 0 ; var thi = this ;
                 o.map((k , v) => {
                     if( ix > 0 && thi.$refs.hasOwnProperty('title'+ix) ){
                        thi.$refs['title'+ix].setContent( v.text ) ;
                     }
                     ix++ ;
                 });
                 this.answers = o ;


            },
            DeleteQuestion : function(Question){
                var o = this ;

                this.$confirm('This will permanently delete the file. Continue?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'danger'
                 }).then(() => {

                    fetch(settings.apiUrl+'admin/questions/'+Question.id , {
                        "method" : "DELETE" ,
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                            'Content-Type': 'application/json'
                        }
                    })
                    .then( res => res.json() )
                    .then( function(res){
                            if( res.res == 0 ){
                                o.$message.error( 'Unable to Delete '+o.type );
                            }else{
                                o.$message.success('Deleted Succefully');
                                o.getList() ;
                            }
                    });

                }).catch(() => {
                    this.$message({ type: 'info', message: 'Delete canceled'});          
                });
            } ,
            ActiveQuestion : function(Question , status){
                var o = this ;

                this.$confirm('This will Update Question Status. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {

                    fetch(settings.apiUrl+'admin/questions/active/'+Question.id , {
                        "method" : "POST" ,
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                            'Content-Type': 'application/json'
                        },
                        body : JSON.stringify( { "status" : status  } )
                    })
                    .then( res => res.json() )
                    .then( function(res){
                            if( res.res == 0 ){
                                o.$message.error( 'Unable to Update '+o.type );
                            }else{
                                o.$message.success('Updated Succefully');
                                o.getList() ;
                            }
                    });

                }).catch(() => {
                    this.$message({ type: 'info', message: 'Update canceled'});          
                });

            } ,
            AddQuestion : function(){
                if( !this.title || this.title.length < 2 ){
                    this.$message.error('Please Set Vaild title');
                }else {

                    var is_true = 0 ; var cnt = 0 ;

                    this.answers.map(function(answer , key){
                        if( answer.text.length >= 1 ){
                            cnt = cnt + 1 ;
                        }
                        if( answer.is_true == 1 || answer.is_true == '1' ){
                            is_true = is_true + 1 ;
                        }
                    }) ;

                    if( is_true != 1 ){
                        this.$message.error( 'Please Select 1 True Answer' );
                        return false; 
                    }

                    if( cnt < 4 ){
                        this.$message.error( 'Please Enter 4 Answers minmum.' );
                        return false; 
                    }



                    let o = this ;
                    let tax = {
                        "title"      : this.title ,
                        "id"       : this.id ,
                        "status" : this.status,
                        "description" : this.description ,
                        "category_id" : this.category_id,
                        "subject_id" : this.subject_id ,
                        "skill_id"  : this.skill_id ,
                        "level_id"   : this.level_id,
                        "attachment_id" : this.attachment_id ,
                        "exam_id" : this.exam_id ,
                        "answers" : this.answers 
                    }
                    fetch(settings.apiUrl+'admin/questions' , {
                        "method" : "POST" ,
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                            'Content-Type': 'application/json'
                        },
                        body : JSON.stringify( tax )
                    })
                    .then( res => res.json() )
                    .then( function(res){
                            if( res.errors ){
                                o.$message.error( res.message );
                            }else{
                                o.$message.success('Question Saved');
                                o.getList() ;
                                o.id = 0 ;
                                o.title = "" ; o.$refs.title.setContent( '' ) ;
                                o.description = "" ;o.$refs.description.setContent( '' ) ;
                                o.status = '1' ;
                                o.attachment_id = '' ;
                                o.answers = [{'text' : '' ,  'is_true' : 0 , 'status' : 1 },{'text' : '' ,'is_true' : 0 , 'status' : 1}, {'text' : '' ,'is_true' : 0 ,'status' : 1},{ 'text' : '' , 'is_true' : 0 , 'status' : 1 } ] ;
                                o.tit    = 'Add New : Question' ;
                                var on = o.answers ;
                                var ix = 0 ;
                                on.map( function(){
                                    if( ix > 0 && thi.$refs.hasOwnProperty('title'+ix) ){
                                        o.$refs['title'+ix].setContent( '' ) ;
                                        ix++ ;
                                    }
                                });
                            }
                    });

                }
            },
            QuestionSearch : function(search){

                if( !search ){
                    this.search.s = '' ;
                    this.search.status = '' ;
                }else{
                    this.$message.info('Please Wait Until Search');
                    this.getList( 1 , true , true ) ;
                }

                this.search.visible = false ;  
            },
            gotoPage : function(a){
                this.getList(a , true , false) ;
            },
            getList : function( page = 1 , search = false , message = false ){

                if( !this.type ){
                    this.type = this.$route.params.type ;
                }
                let self = this ;

                this.listLoading = true;

                let u = settings.apiUrl+'admin/questions/?type='+ this.type+'&page='+page+'&id='+this.$route.params.id ;

                if( search && message ){
                    u = u+'&s='+self.search.s+'&status='+self.search.status ;
                }

               fetch( u , { 
                method: 'get', 
                headers: new Headers({
                    'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                }) 
                } )
               .then( res => res.json() )
               .then( function(res){
                     self.taxData = res.data ;

                     self.pagination.total = res.total ;
                     self.pagination.per_page = res.per_page ;
                     self.pagination.current_page = res.current_page ;

                     if(res.data.length > 0){
                         self.UpdateQuestion(res.data[0]) ;
                     }

                     if( search && message ){
                        self.$message.success('We Found '+ res.total+' Result' );
                    }
                    self.listLoading = false;
               });


            },
            getPtype : function(){
                    return 'Questions' ;
            },
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
            async getAttachments(){
                let attachments = await Post.selectByType('attachments') ;
                this.attachments = attachments ;
            },
            async getMockT(){
                let ex = await Exams.selectByType('mock') ;
                this.exams = ex ;
            },
            async doBulkAction(){
               
               let selected = "all";

               switch(this.bulkAction){
                   case 'attach_to_exam':
                    this.bulkActionAttachToExam = true;
                   break;
                   case 'delete':
                   break;
               }
            },
            async bulkActionAttachToExamClose(){
                //Close attach to exam modal
            }
        },
        async mounted() {
                this.getTaxonomy('category' , 'categories') ;
                this.getTaxonomy('level' , 'levels') ;
                this.getAttachments() ;
                this.getMockT() ;

                this.getList(1) ;

        },
        watch : {
            "category_id": async function(country) {
              this.getTaxonomy('subject' , 'subjects' , this.category_id );
            },
            "subject_id": async function(country) {
              this.getTaxonomy('skill' , 'skills' , this.subject_id );
            },
            "show_list" : async function (){
                if( this.show_list === true ){
                    this.getList(1) ;
                }
            }
        } 

    }
</script>

<style>
  .el-left{
      float:right
  }
  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }
  .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }

  table .el-button + .el-button {
    margin-left: 0px !important ;
 }
</style>