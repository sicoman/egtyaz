<template>
    <div id="Ticket" class="app-container">
        <h1 class="title">  </h1>

            <el-container>
                <el-header>
                    <h3> {{ tit }}     
                        <el-button v-if="!showform" type="primary" class="el-left"  v-permission="['manage '+ptype+' search']"  @click="search.visible = true"><i class="el-icon-search"></i> Settings </el-button>
                        <el-button v-if="showform" type="primary" class="el-left"  v-permission="['manage '+ptype+' search']"  @click="showform = false;gotoPage(1);"><i class="el-icon-search"></i> List Tickets </el-button>
                    </h3> <hr/>

                </el-header>
                <el-main height="">
                    <el-row >
                        <el-col  v-show="showform" :span="24"  v-permission="['manage '+ptype+' add']">
                            <el-form v-loading="formloading" label-position="top"  label-width="150px">
                                    <el-row>
                                        <el-col :span="8">
                                            <el-form-item label="Title">
                                                <el-input readonly v-model="ticket.subject" type="text"/>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="1"> </el-col>
                                        <el-col :span="7">
                                            <el-form-item label="Status">
                                                <el-select v-model="ticket.status" value-key="status">
                                                      <el-option value="opened" label="Opened" /> 
                                                      <el-option value="pending" label="Pending" />      
                                                      <el-option value="solved" label="Solved" />      
                                                </el-select>   
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="1"> &nbsp; </el-col>
                                        <el-col :span="7">
                                            <el-form-item label="Priority">
                                                <el-select v-model="ticket.priority" value-key="priority">
                                                      <el-option value="low" label="Low" /> 
                                                      <el-option value="normal" label="Normal" />      
                                                      <el-option value="high" label="High" />      
                                                      <el-option value="urgent" label="Urgent" />      
                                                </el-select>    
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="24" v-for="comment in comments" :key="comment.id">
                                            <el-card class="box-card" :style="setStyle( comment )">
                                                    <div slot="header" class="clearfix">
                                                        <span>{{ comment.created_at }}</span>
                                                        <el-button v-show="false" style="float: right; padding: 3px 0" type="text">Operation button</el-button>
                                                    </div>
                                                    <div v-html="unescape(comment.html_body)"></div>
                                                    <hr /> 
                                                    <el-tag
                                                        v-for="file in comment.attachments"
                                                        :key="file.file_name"
                                                        :type="info">
                                                            {{file.file_name}} 
                                                            <el-badge :value="bytesToSize( file.size )" class="item" type="warning">
                                                                <el-button size="mini" @click="openFile( file )"><i class="el-icon-download"></i></el-button>
                                                            </el-badge>
                                                    </el-tag>    
                                            </el-card>
                                            <br />
                                        </el-col>  

                                        <el-col :span="24">
                                            <el-card class="box-card">
                                                    <div slot="header" class="clearfix">
                                                        <span>Replay On Ticket</span>
                                                    </div>
                                                    
                                                    <tinymce :height="300" v-model="newComment.comment" />
                                                     <hr />
                                                    <el-upload
                                                        class="upload-demo"
                                                        :action="apiUrl"
                                                        :on-change="handleChange"
                                                        :file-list="newComment.attachments">
                                                        <el-button size="small" type="primary">Upload Attachments</el-button>
                                                        <div slot="tip" class="el-upload__tip"> Maxium size is 2 MB</div>
                                                    </el-upload>  
                                            </el-card>
                                        </el-col>
                                    
                                        <el-col :span="22">
                                            <el-form-item>
                                                <br /><br />
                                                <el-button type="primary" @click="AddComment"> Save </el-button>
                                            </el-form-item>
                                        </el-col>

                                    </el-row>
                            </el-form> 
                        </el-col>
                        <el-col v-show="!showform" :span="24">

                            <el-dialog title="Settings" :visible.sync="search.visible">
                                <el-form label-position="top" :model="search">
                                    <el-row>
                                        <el-col :span="24">
                                            <el-form-item label="PerPage">
                                                <el-input-number v-model="search.per_page" />
                                            </el-form-item>  
                                       </el-col>
                                    </el-row>
                                </el-form>
                                <span slot="footer" class="dialog-footer">
                                    <el-button @click="TicketSearch(false)">Cancel</el-button>
                                    <el-button type="primary" @click="TicketSearch(true)">Save</el-button>
                                </span> 
                            </el-dialog>



                              <el-table  ref="dragTable" :data="taxData" style="width: 100%" v-loading="listLoading"  row-key="id" border fit highlight-current-row >
                                    <el-table-column prop="id" width="50" label="ID"></el-table-column>
                                    <el-table-column prop="subject" label="Title"  ></el-table-column>
                                    <el-table-column prop="description" label="Description"  ></el-table-column>
                                    <el-table-column prop="status" label="Status"  ></el-table-column>
                                      
                                    <el-table-column label="Operations">
                                        <template slot-scope="scope">

                                            <el-tooltip  v-permission="['manage '+ptype+' edit']" content="Show Ticket" placement="top">
                                                <el-button size="mini" type="warning" @click="UpdateTicket(scope.row)" ><i class="el-icon-view"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip  v-permission="['manage '+ptype+' status']" v-if="scope.row.status != 'solved' " content="mark as Solved" placement="top">
                                                <el-button  size="mini" type="success" @click="ActiveTicket(scope.row , 'solved' )" ><i class="el-icon-check"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip  v-permission="['manage '+ptype+' status']" v-if="scope.row.status != 'open' " content="Re-open" placement="top">
                                                <el-button  size="mini" type="primary" @click="ActiveTicket(scope.row , 'open' )" ><i class="el-icon-error"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip  v-permission="['manage '+ptype+' status']" v-if="scope.row.status != 'pending' " content="mark as  Pending" placement="top">
                                                <el-button  size="mini" type="info" @click="ActiveTicket(scope.row , 'pending' )" ><i class="el-icon-help"></i></el-button>
                                            </el-tooltip>

                                            <el-tooltip content="Delete" placement="top"  v-permission="['manage '+ptype+' delete']">
                                                <el-button size="mini" type="danger" @click="DeleteTicket(scope.row)" ><i class="el-icon-delete"></i></el-button>
                                            </el-tooltip>

                                        </template>
                                    </el-table-column>
                              </el-table>

                                <el-pagination
                                        background
                                        layout="pager"
                                        :total="pagination.total"
                                        :page-size="pagination.per_page"
                                        :current-page="pagination.current_page"
                                        :hide-on-single-page="true"
                                        @current-change="gotoPage"
                                >
                                </el-pagination>  

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
    import TicketsResource from '@/api/ezuru/tickets' ;
    const Ticket = new TicketsResource() ;
    import Tinymce from '@/components/Tinymce';

    export default {
        data() {
           return {
               "tit" : "Show Tickets" ,
               "type" : "tickets" ,
               "ticket" : {

               },
               newComment : {
                   'comment' : '' ,
                   'attachments' : []
               },
               comments : [] ,
               taxData : [] ,
               search : {
                   'per_page' : 10 ,
                   'visible' : false
               },
               pagination : {
                   'total' : 0 ,
                   'per_page' : 10 ,
                   'page' : 1
               },
               setting : {

               },
               apiUrl  : settings.apiUrl+'admin/upload' ,
               listLoading : false,
               formloading : false ,
               showform : false ,
           }
        },
        components : { Tinymce } ,
        methods : {
            handleChange(file, fileList) {
                this.newComment.attachments = fileList.slice(-3);
            },
            UpdateTicket : function(Ticket){
                 this.ticket = Ticket ;
                 this.tit    = 'Update Ticket No: '+Ticket.id+ '  >>> '+Ticket.subject;
                 this.getComments(Ticket.id) ;
                 this.showform = true ;
            },
            htmlDecode(input){
            var e = document.createElement('textarea');
            e.innerHTML = input;
            // handle case of empty input
            return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
            },
            unescape : function(str){
                return this.htmlDecode( str ) ;
            } ,
            AddComment : function(){
                var o = this ;

                this.$confirm('Are you sure you want to answer ticket. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {
                    o.listLoading = true ;
                    fetch(settings.apiUrl+'admin/tickets/'+o.ticket.id , {
                        "method" : "POST" ,
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                            'Content-Type': 'application/json'
                        },
                        body : JSON.stringify( { "comment" : o.newComment.comment , "attachments" : o.newComment.attachments , "status" : o.ticket.status , "priority" : o.ticket.priority   } )
                    })
                    .then( res => res.json() )
                    .then( function(res){
                            o.$message.success('Answered Succefully');
                            o.getList( o.pagination.page ) ;
                    });

                }).catch(() => {
                    this.$message({ type: 'info', message: 'Update canceled'});          
                });
            } ,
            DeleteTicket : function(Ticket){
                var o = this ;

                this.$confirm('This will permanently delete the tickets. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'danger'
                }).then(() => {
                    o.listLoading = true ;
                    fetch(settings.apiUrl+'admin/tickets/'+Ticket.id , {
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
                                o.$message.error( 'Unable to Delete Ticket' );
                            }else{
                                o.$message.success('Deleted Succefully');
                                o.getList( o.pagination.page  ) ;
                            }
                    });

                }).catch(() => {
                    this.$message({ type: 'info', message: 'Delete canceled'});          
                });
            } ,
            setStyle(comment){
                if( this.ticket.requester_id == comment.author_id ){
                    return ' background:#fffdd9 ' ;
                }
                return ' background:#f1f1f1 ' ;
            } ,
            bytesToSize(bytes) {
                var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                if (bytes == 0) return '0 Byte';
                var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
                return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
            },
            openFile( file ){
                window.open( file.content_url , "_blank" ) ;
            },
            getComments( id , assign_ticket = false ) {
                this.formLoading = true ;
                var o = this ;
                fetch(settings.apiUrl+'admin/tickets/'+id , {
                        "method" : "get" ,
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                            'Content-Type': 'application/json'
                        }
                    })
                    .then( res => res.json() )
                    .then( function(res){
                        o.comments = res.comments.comments ;
                        if( assign_ticket === true ) {
                            o.ticket = res.ticket.ticket ; 
                        }
                        o.formLoading = false ;
                    });
            },
            ActiveTicket : function(Ticket , status){
                var o = this ;

                this.$confirm('This will Update Ticket Status. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {
                    o.listLoading = true ;
                    fetch(settings.apiUrl+'admin/tickets/active/'+Ticket.id , {
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
                                o.$message.error( 'Unable to Update Ticket' );
                            }else{
                                o.$message.success('Updated Succefully');
                                o.getList( o.pagination.page ) ;
                            }
                    });

                }).catch(() => {
                    this.$message({ type: 'info', message: 'Update canceled'});          
                });

            } ,
            handleAvatarSuccess(res, file) {
                this.photo = file.response ;
            },
            beforeAvatarUpload(file) {
                const isJPG = ( file.type === 'image/jpeg' || file.type === 'image/png' || file.type === 'image.gif' );
                const isLt2M = file.size / 1024 / 1024 < 10;

                if (!isJPG) {
                    this.$message.error('Avatar picture must be JPG format!');
                }
                if (!isLt2M) {
                    this.$message.error('Avatar picture size can not exceed 2MB!');
                }

                return isJPG && isLt2M;
            },
            TicketSearch : function(search){

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

                let self = this ;

                this.listLoading = true;

                let u = settings.apiUrl+'admin/tickets/?page='+page+'&per_page='+self.search.per_page ;

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

                     if( search && message ){
                        self.$message.success('We Found '+ res.total+' Result' );
                    }
                    self.listLoading = false;
               });


            }
                
        },
        async mounted() {
                if( this.$route.query.hasOwnProperty('id') ){
                    this.showform = true ; 
                    this.getComments( this.$route.query.id , true  ) ;
                }else{
                    this.getList(1) ;
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