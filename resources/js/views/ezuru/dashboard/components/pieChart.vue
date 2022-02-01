<template>
  <div :class="className" :style="{height:height,width:width}"/>
</template>

<script>
import echarts from 'echarts';
require('echarts/theme/macarons'); // echarts theme
import { debounce } from '@/utils';

export default {
  props: {
    className: {
      type: String,
      default: 'chart',
    },
    width: {
      type: String,
      default: '100%',
    },
    height: {
      type: String,
      default: '300px',
    },
    pieData:{
        type: Array ,
    }
  },
  data() {
    return {
      chart: null,
    };
  },
  watch: {
    pieData: {
      deep: true,
      handler(val) {
        const names = [] ;
        val.forEach(element => {
            names.push( element.name ) ;
        });
        this.setOptions( names , val ) ;
      },
    },
  },
  mounted() {
    this.initChart();
    this.__resizeHandler = debounce(() => {
      if (this.chart) {
        this.chart.resize();
      }
    }, 100);
    window.addEventListener('resize', this.__resizeHandler);

  },
  beforeDestroy() {
    if (!this.chart) {
      return;
    }
    window.removeEventListener('resize', this.__resizeHandler);
    this.chart.dispose();
    this.chart = null;
  },
  methods: {
    initChart() {

      this.chart = echarts.init(this.$el, 'macarons');

      this.setOptions( ['a' , 'b'] , [ { value: 320, name: 'a' } , { value: 320, name: 'b' } ] ) ;
      
    },
    setOptions( legen , pdata ){

        

        this.chart.setOption({
            tooltip: {
            trigger: 'item',
            formatter: '{a} <br/>{b} : {c} ({d}%)',
            },
            legend: {
            left: 'center',
            bottom: '10',
            data : legen ,
            },
            calculable: true,
            series: [
            {
                name: 'WEEKLY WRITE ARTICLES',
                type: 'pie',
                roseType: 'radius',
                radius: [15, 95],
                center: ['50%', '38%'],
                data: pdata ,
                animationEasing: 'cubicInOut',
                animationDuration: 2600,
            },
            ],
        });

        

    }
  },
};
</script>
