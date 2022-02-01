<template>
        <div id="settings"  class="app-container">

<div id="settingTable">

                <el-table v-loading="loading" :data="table" border fit highlight-current-row style="width: 100%">
                    <!--
                    <el-table-column align="center" label="ID" width="50">
                        <template slot-scope="scope">
                        <span>{{ scope.row.id }}</span>
                        </template>
                    </el-table-column>
                    -->

                    <el-table-column align="center" label="Description">
                        <template slot-scope="scope">
                            <span>{{ scope.row.description }}</span>
                        </template>
                    </el-table-column>

                    <!--
                    <el-table-column align="center" label="Option Var">
                        <template slot-scope="scope">
                            <span>{{ scope.row.option_var }}</span>
                        </template>
                    </el-table-column>
                    -->

                    <el-table-column align="center" label="Value">
                        <template slot-scope="scope">
                            <div v-if="scope.row.html == 'image' && scope.row.option_value != ''" >
                                <img style="width:100px;height:100px;" :src="scope.row.option_value" />
                            </div>
                            <div v-else-if="scope.row.html == 'yesno' || scope.row.html == 'select'" >
                                {{ scope.row.options[scope.row.option_value] }}
                            </div>
                            <div v-else-if=" scope.row.html == 'multi_select' " >
                                <p>
                                    <span v-for="item in JSON.parse(scope.row.option_value)"> {{ scope.row.options[item] }} -</span>
                                </p>
                            </div>
                            <div v-else-if="isHTML(scope.row.option_value)" >
                                {{ scope.row.option_value.replace(/<[^>]*>?/gm, '').substring(1,500) }} ... 
                            </div>
                            <div v-else-if="scope.row.option_value" >
                                {{ scope.row.option_value.substring(1,500) }} 
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Edit">
                        <template slot-scope="scope">
                            <el-button type="warning" @click="EditForm( scope.$index )"><i class="el-icon-edit"></i> Edit </el-button>
                        </template>
                    </el-table-column>

                </el-table>
                        
    </div>


    <el-dialog :title="'Edit Setting '+this.edit.description" :visible.sync="doEdit">
      <div class="form-container" v-loading="loading">
        <el-form ref="userForm" :model="edit" label-position="left" label-width="150px" style="max-width: 500px;">
          
          <el-form-item :label="$t('Option Var')" prop="option_var">
            <el-input :disabled=true v-model="edit.option_var"  />
          </el-form-item>

          <el-form-item :label="$t('Option Value')" prop="option_value">
            
              <div v-if="edit.html == 'text'">
                  <el-input v-model="edit.option_value" />
              </div>
              <div v-else-if="edit.html == 'textarea'">
                  <el-input type="textarea" rows="10" v-model="edit.option_value" />
              </div>
              <div v-else-if="edit.html == 'yesno'">
                  <el-switch v-model="edit.option_value" active-value="yes" inactive-value="no"></el-switch>
              </div>
              <div v-else-if="edit.html == 'number'">
                  <el-input-number v-model="edit.option_value" ></el-input-number>
              </div>
              <div v-else-if="edit.html == 'date'">
                  <el-date-picker v-model="edit.option_value" type="date" placeholder="Pick a Date" format="yyyy-MM-dd"></el-date-picker>
              </div>
              <div v-else-if="edit.html == 'image'"> 
                  <el-upload :action="uploadUrl"  :show-file-list=false accept="image/png, image/jpeg , image/gif" list-type="picture-card" :multiple=false :on-remove="handleRemove" :on-success="setImage">
                    <img v-if="edit.option_value" :src="edit.option_value" style="width:100%;height:100%;" />
                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                  </el-upload>
              </div>
              <div v-else-if="edit.html == 'editor'">
                  <tinymce :height="300" v-model="edit.option_value" :ref="'edi'+edit.option_var"  />
              </div>
              <div v-else-if="edit.html == 'multi_select'">
                  <el-select
                    v-model="edit.option_value"
                    multiple
                    placeholder="Select">
                    <el-option
                    v-for="( item , k ) in edit.options" :key="k" :label="item" :value="k">
                    </el-option>
                </el-select>
              </div>
              <div v-else-if="edit.html == 'select'">
                  <el-select
                    v-model="edit.option_value"
                    
                    placeholder="Select">
                    <el-option
                    v-for="( item , k ) in edit.options" :key="k" :label="item" :value="k">
                    </el-option>
                </el-select>
              </div>
              <div v-else>
                  <el-input v-model="edit.option_value" />
              </div>

          </el-form-item>
        </el-form>

        <div slot="footer" class="dialog-footer">
          <el-button @click="doEdit = false">
            {{ $t('table.cancel') }}
          </el-button>
          <el-button type="primary" @click="saveData()">
            {{ $t('Save') }}
          </el-button>
        </div>
        
      </div>
    </el-dialog>


        </div>

</template>

<script>
    import settings from '@/settings' ;
    import settingResource from '@/api/settings' ;
    let Options = new settingResource() ;
    import Tinymce from '@/components/Tinymce' ;
    export default {
        data(){
            return {
                uploadUrl : settings.apiUrl+'admin/upload' ,
                loading : false,
                type : this.$route.meta.type ,
                doEdit : false ,
                table : [] ,
                edit : {} ,
                query : {

                }
            };
        },
        components : { Tinymce } ,
        methods: {
            async getSettings (){
                this.loading = true ;    
                var listOptions = await Options.list( this.query ) ;
                this.table = listOptions ;
                this.loading = false ;
             },
             EditForm(k){
                 this.doEdit = true ;

                 

                 this.edit = this.table[k] ;

                 if( this.edit.html == 'multi_select' && (this.edit.option_value != '' || this.edit.option_value != 'null') ){
                     this.edit.option_value = JSON.parse( this.edit.option_value ) ;
                 }

                 if( this.table[k].html == 'editor' ){

                    var content = this.table[k].option_value ;

                    setTimeout( ()=> {
                        this.$refs['edi'+this.table[k].option_var].setContent("") ;
                    } , 200 ) ;

                    setTimeout( ()=> {
                        this.$refs['edi'+this.table[k].option_var].setContent( content ) ;
                    } , 500 ) ;
                    
                 }


             },
             saveData(){
                this.loading = true ;    
                var save = Options.update( this.edit.id , this.edit ).then(response => {
                    this.$message({
                        message: 'Data Saved for ' + this.edit.option_var ,
                        type: 'success',
                        duration: 5 * 1000,
                    });
                    this.doEdit = false;
                }).catch(error => {
                    console.log(error);
                }).finally(() => {
                    
                });
                
                this.loading = false ;
             },
            handleRemove(file, fileList) {
                this.edit.option_value = '' ;
            },
            setImage(response , file , list){
                this.edit.option_value = response ;
            },
            isHTML(str) {
            var a = document.createElement('div');
            a.innerHTML = str;

            for (var c = a.childNodes, i = c.length; i--; ) {
                if (c[i].nodeType == 1) return true; 
            }

            return false;
            }
        },
        mounted(){
            this.query.type = this.type ;
            this.getSettings();
        }
    }
</script>