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
        <el-form-item>
          <el-button type="primary" @click="onSubmit" :disabled="user.role === 'admin'">Update</el-button>
        </el-form-item>
      </el-tab-pane>

      <el-tab-pane label="Area To Working On" name="Second" v-loading="updating">
        <el-form-item label="Area">
          <el-select  style="width:100%;"
                v-model="area"
                filterable
                multiple
                remote
                placeholder="Search Area"
                :remote-method="searchArea"
                :loading="loading">
                    <el-option
                          v-for="item in area_list"
                          :key="item.id"
                          :label="item.name"
                          :value="item.id">
                    </el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmitArea" :disabled="user.role === 'admin'">Update</el-button>
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
        'https://cdn.laravue.dev/photo2.png',
        'https://cdn.laravue.dev/photo3.jpg',
        'https://cdn.laravue.dev/photo4.jpg',
      ],
      updating: false,
      area : [],
      area_list : [],
      loading : false
    };
  },
  mounted(){
     this.getUserArea() ;
  } ,
  methods: {
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
      console.log('Switching tab ', tab, event);
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
          console.log(error);
          this.updating = false;
        });
    },
    onSubmitArea() {
      this.updating = true;
      userResource
        .updateArea(this.user.id, this.area)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'User information has been updated successfully',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
    },
    async searchArea(query) {
                if (query.length >= 1 ) {
                    this.loading = true;
                    let self = this ;
                    this.area_list = await Taxonomy.select( { 's' : query , 'type' : 'area' } ) ; 
                    this.loading = false;  
                } else {
                    this.area_list = [];
                }
    }
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
