<template>
    <div id="dashboard" class="dashboard-editor-container" v-if="stat && stat.hasOwnProperty('users')">

        <el-row :gutter="40" class="panel-group"  >
            <el-col :xs="12" :sm="12" :lg="4" class="card-panel-col">
            <div class="card-panel" @click="show='users'" >
                <div class="card-panel-icon-wrapper icon-people">
                <svg-icon icon-class="peoples" class-name="card-panel-icon" />
                </div>
                <div class="card-panel-description">
                <div class="card-panel-text">Users</div>
                <count-to :start-val="0" :end-val="stat.users.all[0].c" :duration="2600" class="card-panel-num"/>
                </div>
            </div>
            </el-col>
            <el-col :xs="12" :sm="12" :lg="5" class="card-panel-col">
            <div class="card-panel" @click="show='exams'">
                <div class="card-panel-icon-wrapper icon-shopping">
                <svg-icon icon-class="chart" class-name="card-panel-icon" />
                </div>
                <div class="card-panel-description">
                <div class="card-panel-text">Exams</div>
                <count-to :start-val="0" :end-val="stat.exams.all[0].c" :duration="3600" class="card-panel-num"/>
                </div>
            </div>
            </el-col>
            <el-col :xs="12" :sm="12" :lg="5" class="card-panel-col">
            <div class="card-panel" @click="show='questions'">
                <div class="card-panel-icon-wrapper icon-message">
                <svg-icon icon-class="tree" class-name="card-panel-icon" />
                </div>
                <div class="card-panel-description">
                <div class="card-panel-text">Questions</div>
                <count-to :start-val="0" :end-val="stat.questions.all[0].c" :duration="3000" class="card-panel-num"/>
                </div>
            </div>
            </el-col>
            <el-col :xs="12" :sm="12" :lg="5" class="card-panel-col">
            <div class="card-panel" @click="show='payments'">
                <div class="card-panel-icon-wrapper icon-money">
                <svg-icon icon-class="money" class-name="card-panel-icon" />
                </div>
                <div class="card-panel-description">
                <div class="card-panel-text">Payments</div>
                <count-to :start-val="0" :end-val="stat.payments.all[0].c" :duration="3200" class="card-panel-num"/>
                </div>
            </div>
            </el-col>

            <el-col :xs="12" :sm="12" :lg="5" class="card-panel-col">
            <div class="card-panel" @click="show='courses'">
                <div class="card-panel-icon-wrapper icon-shopping">
                <svg-icon icon-class="star" class-name="card-panel-icon" />
                </div>
                <div class="card-panel-description">
                <div class="card-panel-text">Courses</div>
                <count-to :start-val="0" :end-val="stat.courses.all[0].c" :duration="3200" class="card-panel-num"/>
                </div>
            </div>
            </el-col>
            
        </el-row>
  
        <el-row :gutter="32" v-if="show == 'users'">
            <el-col :xs="12" :sm="12" :lg="12">
                    <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>Users By Role</span>
                    </div>
                      <el-row class="panel-group">
                          <el-col :gutter="10" v-for="item in stat.users.role" :key="item.id" :xs="12" :sm="12" :lg="12" class="card-panel-col">
                              <div  class="card-panel" >
                                  <div class="card-panel-icon-wrapper icon-people">
                                  <svg-icon icon-class="peoples" class-name="card-panel-icon" />
                                  </div>
                                  <div class="card-panel-description">
                                  <div class="card-panel-text">{{ item.name }}</div>
                                  <count-to :start-val="0" :end-val="item.c" :duration="2600" class="card-panel-num"/>
                                  </div>
                              </div>
                          </el-col>
                      </el-row>
                    </el-card>
            </el-col>
            <el-col :xs="12" :sm="12" :lg="12">
                    <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>Users By Gender</span>
                    </div>
                      <el-row class="panel-group">
                          <el-col :gutter="10" v-for="item in stat.users.gender" :key="item.id" :xs="12" :sm="12" :lg="12" class="card-panel-col">
                              <div  class="card-panel" >
                                  <div class="card-panel-icon-wrapper icon-people">
                                  <svg-icon icon-class="peoples" class-name="card-panel-icon" />
                                  </div>
                                  <div class="card-panel-description">
                                  <div class="card-panel-text">{{ item.gender }}</div>
                                  <count-to :start-val="0" :end-val="item.c" :duration="2600" class="card-panel-num"/>
                                  </div>
                              </div>
                          </el-col>
                      </el-row>
                    </el-card>
            </el-col>
            <el-col :xs="12" :sm="12" :lg="12">
                    <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>Users By Mar7la</span>
                    </div>
                      <el-row class="panel-group">
                          <el-col :gutter="10" v-for="item in stat.users.mar7la" :key="item.id" :xs="12" :sm="12" :lg="12" class="card-panel-col">
                              <div  class="card-panel" >
                                  <div class="card-panel-icon-wrapper icon-people">
                                  <svg-icon icon-class="peoples" class-name="card-panel-icon" />
                                  </div>
                                  <div class="card-panel-description">
                                  <div class="card-panel-text">{{ item.name }}</div>
                                  <count-to :start-val="0" :end-val="item.c" :duration="2600" class="card-panel-num"/>
                                  </div>
                              </div>
                          </el-col>
                      </el-row>
                    </el-card>
            </el-col>
        </el-row>

        <el-row :gutter="32" v-if="show == 'exams'">
            <el-col :xs="12" :sm="12" :lg="12">
                    <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>Exams By Type</span>
                    </div>
                      <el-row class="panel-group">
                          <el-col :gutter="10" v-for="item in stat.exams.type" :key="item.c" :xs="12" :sm="12" :lg="12" class="card-panel-col">
                              <div  class="card-panel" >
                                  <div class="card-panel-icon-wrapper icon-shopping">
                                  <svg-icon icon-class="chart" class-name="card-panel-icon" />
                                  </div>
                                  <div class="card-panel-description">
                                  <div class="card-panel-text">{{ item.type }}</div>
                                  <count-to :start-val="0" :end-val="item.c" :duration="2600" class="card-panel-num"/>
                                  </div>
                              </div>
                          </el-col>
                      </el-row>
                    </el-card>
            </el-col>
            <el-col :xs="12" :sm="12" :lg="12">
                    <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>Exams Result By Type</span>
                    </div>
                      <el-row class="panel-group">
                          <el-col :gutter="10" v-for="item in stat.exams.examResults" :key="item.c" :xs="12" :sm="12" :lg="12" class="card-panel-col">
                              <div  class="card-panel" >
                                  <div class="card-panel-icon-wrapper icon-shopping">
                                  <svg-icon icon-class="chart" class-name="card-panel-icon" />
                                  </div>
                                  <div class="card-panel-description">
                                  <div class="card-panel-text">{{ item.type }}</div>
                                  <count-to :start-val="0" :end-val="item.c" :duration="2600" class="card-panel-num"/>
                                  </div>
                              </div>
                          </el-col>
                      </el-row>
                    </el-card>
            </el-col>
            <el-col :xs="12" :sm="12" :lg="12">
                    <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>Exams Result By Percent</span>
                    </div>
                      <el-row class="panel-group">
                          <el-col :gutter="10" v-for="item in stat.exams.resultPercents" :key="item.c" :xs="12" :sm="12" :lg="12" class="card-panel-col">
                              <div  class="card-panel" >
                                  <div class="card-panel-icon-wrapper icon-shopping">
                                  <svg-icon icon-class="chart" class-name="card-panel-icon" />
                                  </div>
                                  <div class="card-panel-description">
                                  <div class="card-panel-text">{{ item.result }}</div>
                                  <count-to :start-val="0" :end-val="item.c" :duration="2600" class="card-panel-num"/>
                                  </div>
                              </div>
                          </el-col>
                      </el-row>
                    </el-card>
            </el-col>
        </el-row>

         <el-row :gutter="32" v-if="show == 'courses'">
            <el-col :xs="12" :sm="12" :lg="12">
                    <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>Courses By Status</span>
                    </div>
                      <el-row class="panel-group">
                          <el-col :gutter="10" v-for="item in stat.courses.status" :key="item.status" :xs="12" :sm="12" :lg="12" class="card-panel-col">
                              <div  class="card-panel" >
                                  <div class="card-panel-icon-wrapper icon-shopping">
                                  <svg-icon icon-class="star" class-name="card-panel-icon" />
                                  </div>
                                  <div class="card-panel-description">
                                  <div class="card-panel-text">{{ item.status_name }}</div>
                                  <count-to :start-val="0" :end-val="item.c" :duration="2600" class="card-panel-num"/>
                                  </div>
                              </div>
                          </el-col>
                      </el-row>
                    </el-card>
            </el-col>

            <el-col :xs="12" :sm="12" :lg="12">
                    <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>Courses By Category</span>
                    </div>
                      <el-row class="panel-group">
                          <el-col :gutter="10" v-for="item in stat.courses.category" :key="item.taxonomy_id" :xs="12" :sm="12" :lg="12" class="card-panel-col">
                              <div  class="card-panel" >
                                  <div class="card-panel-icon-wrapper icon-shopping">
                                  <svg-icon icon-class="star" class-name="card-panel-icon" />
                                  </div>
                                  <div class="card-panel-description">
                                  <div class="card-panel-text">{{ item.name }}</div>
                                  <count-to :start-val="0" :end-val="item.c" :duration="2600" class="card-panel-num"/>
                                  </div>
                              </div>
                          </el-col>
                      </el-row>
                    </el-card>
            </el-col>
            
        </el-row>

        <el-row :gutter="32" v-if="show == 'questions'">
            <el-col :xs="12" :sm="12" :lg="8">
                    <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>Questions By category</span>
                    </div>
                      <el-row class="panel-group">
                          <el-col :gutter="10" v-for="item in stat.questions.category" :key="item.c" :xs="24" :sm="24" :lg="24" class="card-panel-col">
                              <div  class="card-panel" >
                                  <div class="card-panel-icon-wrapper icon-message">
                                  <svg-icon icon-class="tree" class-name="card-panel-icon" />
                                  </div>
                                  <div class="card-panel-description">
                                  <div class="card-panel-text">{{ item.name }}</div>
                                  <count-to :start-val="0" :end-val="item.c" :duration="2600" class="card-panel-num"/>
                                  </div>
                              </div>
                          </el-col>
                      </el-row>
                    </el-card>
            </el-col>
            <el-col :xs="12" :sm="12" :lg="8">
                    <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>Questions By Subject</span>
                    </div>
                      <el-row class="panel-group">
                          <el-col :gutter="10" v-for="item in stat.questions.subject" :key="item.c" :xs="24" :sm="24" :lg="24" class="card-panel-col">
                              <div  class="card-panel" >
                                  <div class="card-panel-icon-wrapper icon-message">
                                  <svg-icon icon-class="tree" class-name="card-panel-icon" />
                                  </div>
                                  <div class="card-panel-description">
                                  <div class="card-panel-text">{{ item.name }}</div>
                                  <count-to :start-val="0" :end-val="item.c" :duration="2600" class="card-panel-num"/>
                                  </div>
                              </div>
                          </el-col>
                      </el-row>
                    </el-card>
            </el-col>
            <el-col :xs="12" :sm="12" :lg="8">
                    <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>Questions By Skill</span>
                    </div>
                      <el-row class="panel-group">
                          <el-col :gutter="10" v-for="item in stat.questions.skill" :key="item.c" :xs="24" :sm="24" :lg="24" class="card-panel-col">
                              <div  class="card-panel" >
                                  <div class="card-panel-icon-wrapper icon-message">
                                  <svg-icon icon-class="tree" class-name="card-panel-icon" />
                                  </div>
                                  <div class="card-panel-description">
                                  <div class="card-panel-text">{{ item.name }}</div>
                                  <count-to :start-val="0" :end-val="item.c" :duration="2600" class="card-panel-num"/>
                                  </div>
                              </div>
                          </el-col>
                      </el-row>
                    </el-card>
            </el-col>
        </el-row>

        <el-row :gutter="32"  v-if="show == 'payments'">
            <el-col :xs="12" :sm="12" :lg="12">
                    <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>Payments By Type</span>
                    </div>
                      <el-row class="panel-group">
                          <el-col :gutter="10" v-for="item in stat.payments.type" :key="item.c" :xs="24" :sm="24" :lg="24" class="card-panel-col">
                              <div  class="card-panel" >
                                  <div class="card-panel-icon-wrapper icon-money">
                                  <svg-icon icon-class="money" class-name="card-panel-icon" />
                                  </div>
                                  <div class="card-panel-description">
                                  <div class="card-panel-text">{{ item.type }}</div>
                                  <count-to :start-val="0" :end-val="item.c" :duration="2600" class="card-panel-num"/>
                                  </div>
                              </div>
                          </el-col>
                      </el-row>
                    </el-card>
            </el-col>
            <el-col :xs="12" :sm="12" :lg="12">
                    <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>Payments By Gateway</span>
                    </div>
                      <el-row class="panel-group">
                          <el-col :gutter="10" v-for="item in stat.payments.gateway" :key="item.c" :xs="24" :sm="24" :lg="24" class="card-panel-col">
                              <div  class="card-panel" >
                                  <div class="card-panel-icon-wrapper icon-money">
                                  <svg-icon icon-class="money" class-name="card-panel-icon" />
                                  </div>
                                  <div class="card-panel-description">
                                  <div class="card-panel-text">{{ item.gateway }}</div>
                                  <count-to :start-val="0" :end-val="item.c" :duration="2600" class="card-panel-num"/>
                                  </div>
                              </div>
                          </el-col>
                      </el-row>
                    </el-card>
            </el-col>
        </el-row>
        
        
    </div>
</template>


<script>

    import panelgroup from './components/panelgroup' ;

    import pieChart from './components/pieChart' ;

    import barChart from './components/barChart' ;

    import DashboardResource from '@/api/dashboard' ;

    const Dashboard = new DashboardResource() ;

    import settings from '@/settings' ;

    import CountTo from 'vue-count-to';

    
    export default {
        data(){
            return {
                stat : [] ,
                show : 'users'
            } ;
        },
        components : { panelgroup , CountTo }  ,
        methods : {
            async getdashboard(){
                const dash = await Dashboard.stat({}) ;
                this.stat = dash ;
            }
        },
        mounted(){
            this.getdashboard() ;
        }
    }
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.dashboard-editor-container {
  padding: 32px;
  background-color: rgb(240, 242, 245);
  .chart-wrapper {
    background: #fff;
    padding: 16px 16px 0;
    margin-bottom: 32px;
  }
}
</style>
<style rel="stylesheet/scss" lang="scss" scoped>
.box-card{ margin-top: 20px }
.panel-group {
  margin-top: 18px;
  .card-panel-col{
    margin-bottom: 32px;
  }
  .card-panel {
    height: 108px;
    cursor: pointer;
    font-size: 12px;
    position: relative;
    overflow: hidden;
    color: #666;
    background: #fff;
    box-shadow: 4px 4px 40px rgba(0, 0, 0, .05);
    border-color: rgba(0, 0, 0, .05);
    &:hover {
      .card-panel-icon-wrapper {
        color: #fff;
      }
      .icon-people {
         background: #40c9c6;
      }
      .icon-message {
        background: #36a3f7;
      }
      .icon-money {
        background: #f4516c;
      }
      .icon-shopping {
        background: #34bfa3
      }
    }
    .icon-people {
      color: #40c9c6;
    }
    .icon-message {
      color: #36a3f7;
    }
    .icon-money {
      color: #f4516c;
    }
    .icon-shopping {
      color: #34bfa3
    }
    .card-panel-icon-wrapper {
      float: left;
      margin: 14px 0 0 14px;
      padding: 16px;
      transition: all 0.38s ease-out;
      border-radius: 6px;
    }
    .card-panel-icon {
      float: left;
      font-size: 48px;
    }
    .card-panel-description {
      float: right;
      font-weight: bold;
      margin: 26px;
      margin-left: 0px;
      .card-panel-text {
        line-height: 18px;
        color: rgba(0, 0, 0, 0.45);
        font-size: 16px;
        margin-bottom: 12px;
      }
      .card-panel-num {
        font-size: 20px;
      }
    }
  }
}
</style>
