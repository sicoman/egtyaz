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
    barData : {
        type : Object 
    }
  },
  data() {
    return {
      chart: null,
    };
  },
  watch: {
    barData: {
      deep: true,
      handler(val) {
        this.initChart() ;
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
    initChart( ) {
      this.chart = echarts.init(this.$el, 'macarons');

      const bD = JSON.parse(JSON.stringify(this.barData)) ;

      this.chart.setOption({
        tooltip: {
          trigger: 'axis',
          axisPointer: { // Axis indicator, axis trigger is valid
            type: 'shadow', // The default is a straight line, which can be selected as: 'line' | 'shadow'
          },
        },
        grid: {
          top: 10,
          left: '2%',
          right: '2%',
          bottom: '3%',
          containLabel: true,
        },
        xAxis: [{
          type: 'category',
          data: ['Sat', 'Sun' , 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
          axisTick: {
            alignWithLabel: true,
          },
        }],
        yAxis: [{
          type: 'value',
          axisTick: {
            show: false,
          },
        }],
        series: bD ,
      });
    },
  },
};
</script>
