<template>
    <div class="app-container">
        <el-form :model="user" v-if="user">
          <el-row :gutter="20">
            <el-col :span="7">
              <user-card :user="user" />
              <user-bio :user="user" />
            </el-col>
            <el-col :span="17">
              <user-activity :user="user" :u_u_id="user.id" />
            </el-col>
          </el-row>
        </el-form>
    </div>
</template>

<script>
import Resource from '@/api/resource';
import UserBio from '@/views/ezuru/users/profile/components/UserBio';
import UserCard from '@/views/ezuru/users/profile/components/UserCard';
import UserActivity from '@/views/ezuru/users/profile/components/UserActivity';

import settings from '@/settings' ;


const userResource = new Resource('users');
export default {
  name: 'EditUser',
  components: { UserBio, UserCard, UserActivity },
  data() {
    return {
      user: {},
    };
  },
  watch: {
    '$route': 'getUser',
  },
  created() {
    const id = this.$route.params && this.$route.params.id;
    this.getUser(id);
  },
  methods: {
    async getUser(id) {
      const { data } = await userResource.get(id);
      this.user = data;
      this.setDefaultThumb();
    },
    setDefaultThumb(){
       if( !this.user.avatar || this.user.avatar == 'null' ){
        this.user.avatar = settings.defaultAvatar ;
      }
    }

  }
};
</script>