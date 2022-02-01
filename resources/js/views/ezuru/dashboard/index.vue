<template>
    <div id="dashboard" class="dashboard-editor-container">

        <panelgroup :counts="countsAndLineChart.counts" :charts="countsAndLineChart.charts" />
        
        <br />

        <el-row :gutter="32">
            <el-col :xs="12" :sm="12" :lg="12">
                <div class="chart-wrapper" v-if="pieChart">
                    <pie-chart :pieData="pieChart" />
                </div>
            </el-col>
            <el-col :xs="12" :sm="12" :lg="12">
                <div class="chart-wrapper" v-if="barChart">
                    <bar-chart :barData="barChart" />
                </div>
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
    
    export default {
        data(){
            return {
                countsAndLineChart : [] ,
                pieChart : [] ,
                barChart : [] ,
                xxx : ''
            } ;
        },
        components : { panelgroup , pieChart , barChart }  ,
        methods : {
            async getdashboard(){
                const dash = await Dashboard.list({}) ;
                this.countsAndLineChart = dash.countsAndLineChart ;
                const pieChart = dash.pieChart ;
                const newChart =  [];

                pieChart.forEach(element => {
                    newChart.push( { "value" : element.count , "name" : settings.bookingStatus[element.status] }  ) ;
                });

                this.pieChart = JSON.parse(JSON.stringify( newChart ));


                const animationDuration = 6000;
                const barCharty = [] ;
                
                var i = 1 ;
                Object.keys(settings.bookingStatus).forEach(function(key) {
                    var valy = settings.bookingStatus[key] ;
                    let k = key ;
                    var obj = {
                        name: valy ,
                        type: 'bar',
                        stack: valy ,
                        barWidth: '60%',
                        data: [],
                        animationDuration,
                    };

                    if( i > 6 ){ return false; }else{ console.log(i) ; }

                    
                    dash.barChart.forEach(elememt => {
                         if( elememt[key] ){
                             obj.data.push(elememt[key]) ;
                         }else{
                             obj.data.push(0) ;
                         }   
                    }) ;

                    var row = {} ;

                    row[key] = JSON.parse(JSON.stringify(obj)) ;

                    barCharty.push( row );

                    i++;

                });
                let barChart = {} ;
                barCharty.forEach(element => {
                    Object.keys(element).forEach(function(key) {
                         barChart[key] = element[key] ;
                    });
                }) ;


                 let bC = [] ;

                 Object.keys(barChart).forEach(elm => {
                     bC.push( barChart[elm] ) ;
                 })  ;

                this.barChart = JSON.parse(JSON.stringify(bC)) ;

                

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