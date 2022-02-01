<template>
        <div id="customMessage"  class="app-container">

                <el-form ref="form" :model="form" label-width="250px">
                <el-form-item label="Message title">
                    <el-input v-model="form.title"></el-input>
                </el-form-item>
                
                <el-form-item label="Message Type">
                    <el-select v-model="form.type" multiple placeholder="please select Message type">
                    <el-option label="Email" value="email"></el-option>
                    <el-option label="SMS" value="sms"></el-option>
                    <el-option label="Notification" value="pushed"></el-option>
                    </el-select>
                </el-form-item>

                <el-form-item v-if="!form.type.includes('email')" label="Message">
                        <el-input type="textarea" :height="300" v-model="form.description"  ref="description" />
                </el-form-item>
                <el-form-item v-else label="Message">
                        <tinymce :height="300" v-model="form.message"  ref="description" />
                </el-form-item>

                <el-form-item label="Send to Users">
                         <el-select
                            v-model="form.users"
                            multiple
                            filterable
                            remote
                            reserve-keyword
                            placeholder="Please Search for name"
                            :remote-method="searchUser"
                            :loading="loading">
                            <el-option  v-for="item in users" :key="item.id" :label="item.name" :value="item.id"></el-option>
                        </el-select>
                </el-form-item>

                <el-form-item>
                    <el-button type="primary" @click="SendMessage" >Send</el-button>
                </el-form-item>
            </el-form>


        </div>
</template>

<script>
    import settings from '@/settings' ;

    import Tinymce from '@/components/Tinymce' ;

    import UserResource from '@/api/user' ;

    let Users = new UserResource() ;

    import Cookies from 'js-cookie' ;

    const TokenKey = 'Admin-Token'  ;

    export default {
        data(){
            return {
                uploadUrl : settings.apiUrl+'admin/upload' ,
                loading : false,
                type : '' ,
                query : {

                },
                form : {
                    'title' : '' ,
                    'message' : '' ,
                    'type' : ['email'] ,
                    'users' : []
                },
                users : []
            };
        },
        components : { Tinymce } ,
        methods: {
          async getSettings (){
                
          },
          async searchUser(query) {
                if (query.length >= 3 ) {
                    this.loading = true;
                    let self = this ;
                    this.users = await Users.select( { 's' : query } ) ; 
                    this.loading = false;  
                } else {
                    this.users = [];
                }
            } ,
        
          SendMessage(){
                if( !this.form.title || this.form.title.length < 3 ){
                    this.$message.error('Please Set Vaild Title');
                }else if( !this.form.message || this.form.message.length < 20 ){
                    this.$message.error('Please Set Vaild Message');
                }else {
                    let o = this ;
                    let message = {
                        "title"      : this.form.title ,
                        "message" : this.form.message,
                        "users" : this.form.users,
                        "type"  : this.form.type  ,
                    }
                    fetch(settings.apiUrl+'customMessages' , {
                        "method" : "POST" ,
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer '+Cookies.get(TokenKey), 
                        },
                        body : JSON.stringify( message )
                    })
                    .then( res => res.json() )
                    .then( function(res){
                            if( res.errors ){
                                o.$message.error( res.message );
                            }else{
                                o.$message.success('Message Sent Succefully');
                            }
                    });


                }
          }
        },
        mounted(){
            this.getSettings();
        }
    }
</script>