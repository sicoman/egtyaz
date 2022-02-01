<template>
  <el-card v-if="user.name">
    <el-tabs v-model="activeActivity" @tab-click="handleClick">
      
      <el-tab-pane label="Account" name="first" v-loading="updating">
        <el-form-item label="Name">
          <el-input v-model="user.name" :disabled="user.role === 'admin'"></el-input>
        </el-form-item>
        <el-form-item label="Email">
          <el-input v-model="user.email" :disabled="user.role === 'admin'"></el-input>
        </el-form-item>
        <el-form-item label="Password">
          <el-input v-model="user.password" :disabled="user.role === 'admin'"></el-input>
        </el-form-item>
        <el-form-item label="Confirm Password">
          <el-input v-model="user.password2" :disabled="user.role === 'admin'"></el-input>
        </el-form-item>

        <!-- AreaManager Percent -->
        <el-form-item label="Manager Percent" v-show="user.role === 'mediamanager'">
          <el-input-number v-model="user.percent" :step=".1" :disabled="user.role === 'admin'"></el-input-number>
        </el-form-item>


        <el-form-item>
          <el-button type="primary" @click="onSubmit" :disabled="user.role === 'admin'">Update</el-button>
        </el-form-item>
      </el-tab-pane>

      <el-tab-pane label="Places Premissions" v-if="user.id != auth.id" name="Second" v-loading="updating">
        <el-row >
            <el-col :span="11">
<el-row>
                <el-col :span="24" v-if="!disabled.country">
                    <el-form-item label="Country">
                        <el-select v-model="country" :disabled="disabled.country" placeholder="Country ... type to search" @change="getGovs" >
                                <el-option v-for="v in country_list" :key="v.id" :label="v.name" :value="parseInt(v.id)" />
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="24" v-if="!disabled.government">
                    <el-form-item label="Government">
                        <el-select v-model="government" :disabled="disabled.government" placeholder="Select Government ..." @change="getCity"  >
                                <el-option v-for="v in gov_list" :key="v.id" :label="v.name" :value="parseInt(v.id)" />
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="24" v-if="!disabled.city">
                    <el-form-item label="City">
                        <el-select v-model="city" :disabled="disabled.city" @change="getArea" placeholder="Select City"  >
                                <el-option v-for="v in cites_list" :key="v.id" :label="v.name" :value="parseInt(v.id)" />
                        </el-select>
                    </el-form-item>
                </el-col>

                <el-col :span="24">
                    <el-form-item label="Area">
                        <el-select v-model="area" :disabled="disabled.area" placeholder="Select Area"  >
                                <el-option v-for="v in area_list " :key="v.id" :label="v.name" :value="parseInt(v.id)" />
                        </el-select>
                    </el-form-item>
                </el-col>
                
            </el-row>
        
            </el-col>
            <el-col :span="1">&nbsp;</el-col>
            <el-col :span="12">
                    <table border="1" width="100%">
                          <tr v-if="selected.country.length > 0">
                              <th>Country</th>
                              <td>
                                  <el-tag
                                    v-for="(tag , index) in selected.country"
                                    :key="tag.name"
                                    closable
                                    @close="RemoveTag( index , 'country' )"
                                    type="primary">
                                    {{tag.name}}
                                  </el-tag>
                              </td>
                          </tr>

                          <tr v-if="selected.government.length > 0">
                              <th>Government</th>
                              <td>
                                  <el-tag
                                    v-for="(tag , index) in selected.government"
                                    :key="tag.name"
                                    closable
                                    @close="RemoveTag( index , 'government' )"
                                    type="warning">
                                    {{tag.name}}
                                  </el-tag>
                              </td>
                          </tr>

                          <tr v-if="selected.city.length > 0">
                              <th>City</th>
                              <td>
                                  <el-tag
                                    v-for="(tag , index) in selected.city"
                                    :key="tag.name"
                                    closable
                                    @close="RemoveTag( index , 'city' )"
                                    type="info">
                                    {{tag.name}}
                                  </el-tag>
                              </td>
                          </tr>

                          <tr v-if="selected.area.length > 0">
                              <th>Area</th>
                              <td>
                                  <el-tag
                                    v-for="(tag , index) in selected.area"
                                    :key="tag.name"
                                    closable
                                    @close="RemoveTag( index , 'area' )"
                                    type="primary">
                                    {{tag.name}}
                                  </el-tag>
                              </td>
                          </tr>

                    </table>
            </el-col>
        </el-row>
        
        <el-form-item>
          <el-button type="primary" @click="AddToList" :disabled="user.role === 'admin'">Add To List</el-button>

           <el-button type="primary" @click="onSubmitArea" :disabled="user.role === 'admin'">Save</el-button>

        </el-form-item>
      </el-tab-pane>
    </el-tabs>
  </el-card>
</template>

<script>
import Resource from '@/api/user';
const userResource = new Resource('users');
import TaxonomyResource from '@/api/ezuru/taxonomy' ;
const Taxonomy = new TaxonomyResource() ;

import {getToken} from '@/utils/auth' ;
import {getInfo} from '@/api/auth' ;

export default {
  props: {
    user: {
      type: Object,
      default: () => {
        return {
          name: '',
          email: '',
          avatar: '',
          roles: [],
          area : []
        };
      },
    },
  },
  data() {
    return {
      activeActivity: 'first',
      carouselImages: [
        'https://cdn.laravue.dev/photo1.png',
      ],
      auth : {} ,
      updating: false,
      area : [],
      city:[] ,
      government:[] ,
      country : [] ,
      area_list : [],
      cites_list : [],
      country_list:[],
      gov_list : [] ,
      loading : false ,
      only : {
         country : [] ,
         government : [] ,
         city : [] ,
         area : []
      },
      disabled : {
         country : false,
         government : false,
         city : false,
         area : false
      },
      selected : {
         country : [] ,
         government : [] ,
         city : [] ,
         area : []
      }
    };
  },

   async mounted(){
      var o = this ;
      var auth =  await getInfo( getToken() ) ;
      o.auth = auth.data ;
    
       auth.data.area.forEach(function(ar){
            o.only[ar.type].push( ar.area_id ) ;
       });

       let loaded = 0 ;

       if( o.only.country.length == 0 && !( o.auth.roles.includes('admin') || o.auth.roles.includes('manager')  ) ){ o.disabled.country = true ;  }else{ o.getCountry(); loaded = 1 ; }
       
       if( o.only.government.length == 0 && !( o.auth.roles.includes('admin') || o.auth.roles.includes('manager')  ) ){ if( o.disabled.country === true ){ o.disabled.government = true ; } }else if(loaded == 0){ o.getGovs() ; loaded = 1 ; }
       
       if( o.only.city.length == 0 && !( o.auth.roles.includes('admin') || o.auth.roles.includes('manager')  ) ){ if( o.disabled.government === true ){o.disabled.city = true ; } }else if(loaded == 0){ o.getCity() ; loaded = 1 ; }
       
       if( o.only.area.length == 0 && !( o.auth.roles.includes('admin') || o.auth.roles.includes('manager')  ) ){ if( o.disabled.city === true ){ o.disabled.area = true ; } }else if(loaded == 0){ o.getArea() ; loaded = 1 ; }


       // Get User Area Data to Selected
       o.user.area.forEach(function(are){
          o.selected[are.type].push( { "id" : are.area_id , "name" : are.taxonomy.name } ) ;
       }) ;
       
  } ,
  methods: {
    CheckIfAreaIsselected(id , type){
        this.selected[type].forEach(function(q){
           if( q.id == id ){ 
                    return false;
            }
        });
        return true ;
    },
    AddToList(){
        var o = this ;

        if( o.area != '' && o.area != [] && this.CheckIfAreaIsselected(o.area , 'area') ){ 
            var name = '' ;
            o.area_list.forEach(function(q){
                if( q.id == o.area ){ name = q.name ; }
            });
            this.selected.area.push( { id : o.area , 'name' : name  } ) ;
         }else if( o.city != '' && o.city != [] && this.CheckIfAreaIsselected(o.city , 'city') ){ 
            var name = '' ;
            o.cites_list.forEach(function(q){
                if( q.id == o.city ){ name = q.name ; }
            });
            this.selected.city.push( { id : o.city , 'name' : name  } ) ;
         }else if( o.government != '' && o.government != [] && this.CheckIfAreaIsselected(o.government , 'government') ){ 
            var name = '' ;
            o.gov_list.forEach(function(q){
                if( q.id == o.government ){ name = q.name ; }
            });
            this.selected.government.push( { id : o.government , 'name' : name  } ) ;
         }else if( o.country != '' && o.country != [] && this.CheckIfAreaIsselected( o.country , 'country')  ){ 
            var name = '' ;
            o.country_list.forEach(function(q){
                if( q.id == o.country ){ name = q.name ; }
            });
            this.selected.country.push( { id : o.country , 'name' : name  } ) ;
         }else{
            console.log('No Thing Updated') ;
         }
    } ,
    RemoveTag(index , type){
        this.selected[type].splice(index , 1) ;
    },
    getQS(type){
        var o = this ;
        if( o.auth.roles.includes('admin') || o.auth.roles.includes('manager') ){
           return '' ;
        }
        var id_in = '' ; var par = '' ;

        if( type == 'country' ){
            if( o.only.country.length > 0 ){
                 o.only.country.forEach(function(cou){
                      id_in = id_in+','+cou ;
                 });
            }
        }else if( type == 'government' ){
            if( o.only.country.length > 0 ){
                 o.only.country.forEach(function(cou){
                      par = par+','+cou ;
                 });
            }
            if( o.only.government.length > 0 ){
                 o.only.government.forEach(function(cou){
                      id_in = id_in+','+cou ;
                 });
            }
        }else if( type == 'city' ){
            if( o.only.government.length > 0 ){
                 o.only.government.forEach(function(cou){
                      par = par+','+cou ;
                 });
            }
            if( o.only.city.length > 0 ){
                 o.only.city.forEach(function(cou){
                      id_in = id_in+','+cou ;
                 });
            }
        }else if( type == 'area' ){
            
            if( o.only.city.length > 0 ){
                 o.only.city.forEach(function(cou){
                      par = par+','+cou ;
                 });
            }

            if( o.only.area.length > 0 ){
                 o.only.area.forEach(function(cou){
                      id_in = id_in+','+cou;
                 });
            }
        }

        if( id_in == '' && par == '' ){
            return '' ;
        }

        var retu = {
          "type" : type ,
          "id_in" : '' ,
          "parent" : "" ,
          'filterable' : 1
        } ;

        if( id_in != '' ){
            retu["id_in"] = id_in ;
        }

        if( par != '' ){
            retu["parent"] = par ;
        }

        return retu ;

    },
    getUserArea(){
       var o = this ;
       var area = o.user.area ;
       if( !area ){
          setTimeout(function(){
              o.getUserArea();
          }, 200 );
       }else{
          area.forEach( ar => {
              this.area.push( ar.area_id ) ;
          });
          this.searchArea('   ') ;
       }
    },
    handleClick(tab, event) {
      
    },
    onSubmit() {
      this.updating = true;
      userResource
        .update(this.user.id, this.user)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'User information has been updated successfully',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          this.updating = false;
        });
    },
    onSubmitArea() {
      this.updating = true;
      userResource
        .updateArea(this.user.id, this.selected)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'User information has been updated successfully',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          this.updating = false;
        });
    },
    
    async getCountry() {
        var qs = this.getQS('country') ;
        if( qs == '' ){
           qs = { 'parent' : 0 , 'type' : 'country' , 'filterable' : 1 } ;
        }
        this.loading = true;
        let self = this ;
        this.country_list = await Taxonomy.select( qs ) ; 
        this.gov_list = [] ;
        this.cites_list = [] ;
        this.government = '' ;
        this.city = '' ;
        this.loading = false;  
    },
    async getGovs(){
        var qs = this.getQS('government') ;
        if( qs == '' ){
           qs = { 'parent' : this.country , 'type' : 'government', 'filterable' : 1 } ;
        }
        this.loading = true;
        let self = this ;
        this.gov_list = await Taxonomy.select( qs ) ;
        this.cites_list = [] ;
        this.city = '' ;
        this.government = '' ;
        this.loading = false; 
    },
    async getCity() {
        var qs = this.getQS('city') ;
        if( qs == '' ){
           qs = { 'parent' : this.government , 'type' : 'city', 'filterable' : 1 } ;
        }
        this.loading = true;
        let self = this ;
        this.cites_list = await Taxonomy.select( qs ) ; 
        this.city = '' ;
        this.loading = false;  
    },
    async getArea() {
        this.loading = true;
        let self = this ;
        this.area_list = await Taxonomy.select( { 'parent' : this.city , 'type' : 'area' , 'filterable' : 1 } ) ; 
        this.area = '' ;
        this.loading = false;  
    },

  },

};
</script>

<style lang="scss" scoped>
.user-activity {
  .user-block {
    .username, .description {
      display: block;
      margin-left: 50px;
      padding: 2px 0;
    }
    img {
      width: 40px;
      height: 40px;
      float: left;
    }
    :after {
      clear: both;
    }
    .img-circle {
      border-radius: 50%;
      border: 2px solid #d2d6de;
      padding: 2px;
    }
    span {
      font-weight: 500;
      font-size: 12px;
    }
  }
  .post {
    font-size: 14px;
    border-bottom: 1px solid #d2d6de;
    margin-bottom: 15px;
    padding-bottom: 15px;
    color: #666;
    .image {
      width: 100%;
    }
    .user-images {
      padding-top: 20px;
    }
  }
  .list-inline {
    padding-left: 0;
    margin-left: -5px;
    list-style: none;
    li {
      display: inline-block;
      padding-right: 5px;
      padding-left: 5px;
      font-size: 13px;
    }
    .link-black {
      &:hover, &:focus {
        color: #999;
      }
    }
  }
  .el-carousel__item h3 {
    color: #475669;
    font-size: 14px;
    opacity: 0.75;
    line-height: 200px;
    margin: 0;
  }

  .el-carousel__item:nth-child(2n) {
    background-color: #99a9bf;
  }

  .el-carousel__item:nth-child(2n+1) {
    background-color: #d3dce6;
  }
  
}
</style>

<style scoped>
  table td , table th{padding:10px!important}
  table {
    border-collapse: collapse;
  }

  table, th, td {
    border: 1px solid black;
  }
</style>
