<template>
    <div id="chat" class="app-container">
        <br />
         <el-row :gutter="12">
            <el-col :span="8">
                <el-card :header="'Unit'" shadow="hover">
                    <router-link :to="'/ezuru/units/edit/'+message.unit.id"><span>{{ message.unit.title }}</span></router-link>
                </el-card>
            </el-col>
            <el-col :span="8">
                <el-card :header="'Guest'" shadow="hover">
                    <router-link :to="'/users/user/edit/'+message.guest.id"><span>{{ message.guest.name }}</span></router-link>
                </el-card>
            </el-col>
            <el-col :span="8">
                <el-card :header="'Owner'" shadow="hover">
                    <router-link :to="'/users/user/edit/'+message.owner.id"><span>{{ message.owner.name }}</span></router-link>
                </el-card>
            </el-col>
        </el-row>

        <br />

        <el-button v-if="query.next != null " type="warning" size="mini" @click="loadPrev">Load Prev Messages</el-button>

        <div id="chatlist">
            <div class="frame">
                
                <ul>
                                <li style="width:100%" v-for="(item , key) in list.slice().reverse()">
                                    <div v-if="item.user_id == message.guest.id" class="msj macro">
                                        <div class="avatar"><img class="img-circle" style="width:100%;" :src="message.guest.avatar" /></div>
                                        <div class="text text-l">
                                            <p>{{ item.message }}</p>
                                            <p><small>{{item.created_at}}</small></p>
                                        </div>
                                        <div class="options">
                                                <el-button type="danger" @click="handleDelete(item.id , key)" size="mini" >
                                                    <i class="el-icon-delete"></i>
                                                </el-button>
                                                <br /><br />
                                                <el-button type="warning" @click="Answer(key)" size="mini" >
                                                    <i class="el-icon-postcard"></i>
                                                </el-button>
                                        </div>
                                    </div>

                                    <div v-else-if="item.user_id == message.owner.id" class="msj-rta macro">
                                        <div class="options">
                                                <el-button type="danger" @click="handleDelete(item.id , key)" size="mini" >
                                                    <i class="el-icon-delete"></i>
                                                </el-button>
                                                <br /><br />
                                                <el-button type="warning" v-permission="['manage chat answer']" @click="Answer(key)" size="mini" >
                                                    <i class="el-icon-postcard"></i>
                                                </el-button>
                                        </div>
                                        <div class="text text-r">
                                            <p>{{ item.message }}</p>
                                            <p><small>{{item.created_at}}</small></p>
                                            <p>
                                                    <el-alert close-text="Delete" v-permission="['manage chat delete']" type="primary" v-for="( ans, k ) in item.answers" :key="k"  @close="return deleteAnswer(ans)">
                                                        {{ ans.answer }}
                                                     </el-alert> 
                                            </p>
                                        </div>
                                        <div class="avatar" style="padding:0px 0px 0px 10px !important"><img class="img-circle" style="width:100%;" :src="message.owner.avatar" /></div>
                                    </div>
                                </li>
                </ul>

                <el-dialog  title="Answer Message" :visible.sync="answer.id > 0">
                    <el-alert type="warning" >
                            {{ answer.message }}
                    </el-alert>
                    <div class="msj-rta macro">                        
                        <div class="text text-r" style="background:whitesmoke !important">
                            <input class="mytext" v-model="answer.answer" placeholder="Type a message"/>
                        </div>
                        
                    </div>

                    <div style="padding:10px;">
                        <span class="glyphicon glyphicon-share-alt"></span>
                    </div>  

                    <span slot="footer" class="dialog-footer">
                        <el-button @click="answer = {}">Cancel</el-button>
                        <el-button type="primary" @click="SendMessage()">SendMessage</el-button>
                    </span>
                                
                </el-dialog>
        </div> 
        </div>
    </div>
</template>

<script>
    import MessagesResource from '@/api/ezuru/messages' ;

    const Messages = new MessagesResource() ;

    import permission from '@/directive/permission/index.js' ;

    export default {
        'name' : 'ChatManager' ,
        directives : { permission } ,
        data(){
            return {
                list : [] ,
                message : {} ,
                answer : {} ,
                loading: true ,
                search : false ,
                query:{
                    limit : 10 ,
                    page : 1
                },
                total : 0 
            }
        },
        methods : {
              async gety(reload){
                this.loading = true ;
                
                if( reload == 1 ){
                    this.list = [] ;
                }

                var listres = await Messages.chat( this.$route.params.id ,  this.query ) ;

                listres.data.forEach(element => {
                    this.list.push(element) ;
                });
                this.total = parseInt(listres.total) ; 
                // this.query.page = parseInt(listres.current_page) ;
                this.query.next = listres.next_page_url ;
                this.query.limit = parseInt(listres.per_page)  ;
                this.loading = false ;
             },
             reloady(){
                 this.query.next = null ;
                 this.gety(1) ;
             },
             async Getmessage(){
                this.loading = true ;    
                const listr = await Messages.get( this.$route.params.id ) ;
                this.message = listr ;
                this.loading = false ;
             },
             formatAMPM(date) {
                var hours = date.getHours();
                var minutes = date.getMinutes();
                var ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                minutes = minutes < 10 ? '0'+minutes : minutes;
                var strTime = hours + ':' + minutes + ' ' + ampm;
                return strTime;
            },
            loadPrev(){
                if( this.query.next != null ){
                    this.query.page = this.query.page + 1 ;
                    this.gety();
                }
            },
            handleDelete(id , key ) {
                let o = this ;
                this.$confirm('This will permanently delete " ' + this.list.slice().reverse()[key].message + '." Continue?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning',
                }).then(() => {
                    Messages.chat_delete(id).then(response => {
                        this.$message({
                            type: 'success',
                            message: 'Delete completed',
                        });
                        this.list.slice().reverse().splice(key, 1);

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
            Answer(key){
                this.answer = this.list.slice().reverse()[key]  ;
            },
            SendMessage(){
                if( this.message.message == '' ){
                    this.$message({
                    type: 'danger',
                    message: 'Please Insert Message',
                    });
                }else{
                   Messages.chat_answer( this.answer.id , this.answer ).then(response => {
                        this.$message({
                            message: 'Message Answered ' + this.answer.answer +'  has been Sent successfully.',
                            type: 'success',
                            duration: 5 * 1000,
                    });
                    this.answer = {} ;
                    this.reloady();
                }) ; 
                }
            },
            deleteAnswer(ans){
                 
                let o = this ;
                this.$confirm('This will permanently delete " ' + ans.answer + '." Continue?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning',
                }).then(() => {
                    Messages.chat_item_delete(this.message.id , ans.id ).then(response => {
                        this.$message({
                            type: 'success',
                            message: 'Delete completed',
                        });
                        this.list.slice().reverse().splice(key, 1);
                        this.reloady();
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
            }
        },
        mounted() {
            var o = this;
            o.Getmessage() ;
            o.gety() ;
        }
    }
</script>

<style lang="css">

@import '//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css' ;
#chat_list{max-width:500px;margin:20px auto}
#chat .mytext{
    border:0;padding:10px;background:whitesmoke;
}
#chat .text{
    width:calc( 100% - 150px ) ;;display:flex;flex-direction:column;
}
#chat .text > p:first-of-type{
    width:100%;margin-top:20px;margin-bottom:auto;line-height: 13px;font-size: 12px;
}
#chat .text > p:last-of-type{
    width:100%;text-align:right;color:silver;margin-bottom:-7px;margin-top:auto;
}
#chat .text-l{
    float:left;padding-right:10px;
}        
#chat .text-r{
    float:right;padding-left:10px;
}
#chat .avatar{
    display:flex;
    justify-content:center;
    align-items:center;
    width:calc( 100% - (100% - 100px) ) ;
    float:left;
    padding-right:10px;
}

#chat .avatar img{
    width:100px;
    height:100px;
}
#chat .macro{
    margin-top:5px;width:85%;border-radius:5px;padding:5px;display:flex;
    height:auto;overflow: hidden; padding-bottom:10px
}
#chat .msj-rta{
    float:right;background:whitesmoke;
}
#chat .msj{
    float:left;background:white;
}
#chat .frame{
    background:#e0e0de;
    height:450px;
    overflow:hidden;
    padding:0;
    position:relative
}
#chat .frame > div:last-of-type{
    position:absolute;bottom:0;width:100%;display:flex;
}
#chat  > div > div > div:nth-child(2) > span{
    background: whitesmoke;padding: 10px;font-size: 21px;border-radius: 50%;
}
#chat_list > div > div > div.msj-rta.macro{
    margin:auto;margin-left:1%;
}
#chat ul {
    width:100%;
    list-style-type: none;
    padding:18px;
    position:absolute;
    bottom:0;
    display:flex;
    flex-direction: column;
    top:0;
    overflow-y:scroll;
}
#chat  .msj:before{
    width: 0;
    height: 0;
    content:"";
    top:-5px;
    left:-14px;
    position:relative;
    border-style: solid;
    border-width: 0 13px 13px 0;
    border-color: transparent #ffffff transparent transparent;            
}
#chat .msj-rta:after{
    width: 0;
    height: 0;
    content:"";
    top:-5px;
    left:14px;
    position:relative;
    border-style: solid;
    border-width: 13px 13px 0 0;
    border-color: whitesmoke transparent transparent transparent;           
}  
#chat input:focus{
    outline: none;
}        
#chat ::-webkit-input-placeholder { /* Chrome/Opera/Safari */
    color: #d4d4d4;
}
#chat ::-moz-placeholder { /* Firefox 19+ */
    color: #d4d4d4;
}
#chat :-ms-input-placeholder { /* IE 10+ */
    color: #d4d4d4;
}
#chat :-moz-placeholder { /* Firefox 18- */
    color: #d4d4d4;
} 
#chat .el-alert--primary{
    background:#f00;color:#fff;
} 

#chat .el-alert--primary .el-alert__closebtn{
    color:#ffffff;font-weight:bold
} 
</style>