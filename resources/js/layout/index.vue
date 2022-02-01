<template>
  <div :class="classObj" class="app-wrapper">
    <div v-if="device==='mobile'&&sidebar.opened" class="drawer-bg" @click="handleClickOutside" />
    <sidebar class="sidebar-container" />
    <div :class="{hasTagsView:needTagsView}" class="main-container">
      <div :class="{'fixed-header':fixedHeader}">
        <navbar />
        <tags-view v-if="needTagsView" />
      </div>
      <app-main />
      <right-panel v-if="showSettings">
        <settings />
      </right-panel>
    </div>
  </div>
</template>

<script>
import RightPanel from '@/components/RightPanel';
import { Navbar, Sidebar, AppMain, TagsView, Settings } from './components';
import ResizeMixin from './mixin/resize-handler.js';
import { mapState } from 'vuex';

import {getToken} from '@/utils/auth' ;
import {getInfo} from '@/api/auth' ;
import settings from '@/settings' ;

export default {
  data(){
    return {
      'settings' : settings ,
      'auth' : {} ,
      'token' : getToken()
    }
  } ,
  name: 'Layout',
  components: {
    AppMain,
    Navbar,
    RightPanel,
    Settings,
    Sidebar,
    TagsView,
  },
  mixins: [ResizeMixin],
  computed: {
    ...mapState({
      sidebar: state => state.app.sidebar,
      device: state => state.app.device,
      showSettings: state => state.settings.showSettings,
      needTagsView: state => state.settings.tagsView,
      fixedHeader: state => state.settings.fixedHeader,
    }),
    classObj() {
      return {
        hideSidebar: !this.sidebar.opened,
        openSidebar: this.sidebar.opened,
        withoutAnimation: this.sidebar.withoutAnimation,
        mobile: this.device === 'mobile',
      } ;
    },
  },
  async mounted(){
      var o = this ;
      var auth =  await getInfo( this.token ) ;
      this.auth = auth.data ;
      this.CallNotifications() ;
  } ,
  methods: {
    handleClickOutside() {
      this.$store.dispatch('app/closeSideBar', { withoutAnimation: false });
    },
    CallNotifications(){

      let o = this ;

      if ("Notification" in window) {
          function spawnNotification(title, body, icon = "notification.png") {
            var options = {
              body: body,
              icon: icon
            };
            var n = new Notification(title, options);
          }

          if (Notification.permission === "granted") {
            setInterval(async () => {
              let user = o.auth ;

              if (user) {

                fetch(settings.apiUrl+'front/notifications' , {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer '+this.token , 
                            'Content-Type': 'application/json'
                        }
                    })
                    .then( res => res.json() )
                    .then( function(notifications){
                        if (notifications.length) {
                          notifications.forEach(noti => {
                                var data = JSON.parse(noti.data) ;
                                spawnNotification(
                                  data.title,
                                  data.message
                                );
                          });
                          
                        }
                });
                
                
              }
            }, 15000);
          } else if (Notification.permission !== "denied") {
            Notification.requestPermission(function(permission) {
              if (!("permission" in Notification)) {
                Notification.permission = permission;
              }
            });
          }
        }


    }
  },
};
</script>

<style lang="scss" scoped>
  @import "~@/styles/mixin.scss";
  @import "~@/styles/variables.scss";

  .app-wrapper {
    @include clearfix;
    position: relative;
    height: 100%;
    width: 100%;

    &.mobile.openSidebar {
      position: fixed;
      top: 0;
    }
  }

  .drawer-bg {
    background: #000;
    opacity: 0.3;
    width: 100%;
    top: 0;
    height: 100%;
    position: absolute;
    z-index: 999;
  }

  .fixed-header {
    position: fixed;
    top: 0;
    right: 0;
    z-index: 9;
    width: calc(100% - #{$sideBarWidth});
    transition: width 0.28s;
  }

  .hideSidebar .fixed-header {
    width: calc(100% - 54px)
  }

  .mobile .fixed-header {
    width: 100%;
  }
</style>
