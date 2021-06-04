<template>
  <div>
    <scatter :chart-data="datacollection" :chart-options="options"></scatter>
  </div>
</template>

<script>
  import Scatter from './ScatterChart.js'

  export default {
    components: {
      Scatter
    },
    props: [
      'input_xy'
    ],
    data () {
      return {
        datacollection: null
      }
    },
    mounted () {
      this.fillData();
      this.fillXY();
    },
    methods: {
      fillXY(){
        this.input_xy.sort(function (a, b) { return a.x - b.x });
        for(let i = 0; i < this.input_xy.length; i++)
        {
          let x1 = this.input_xy[i].x;
          let y1 = this.input_xy[i].y;
          this.datacollection.datasets[0].data.push({x: x1, y: y1});
        }
      },
      fillData(){
        this.datacollection = {
          datasets: [{
            label: 'График',
            borderColor: 'rgba(208, 152, 60, 0.8)',
            backgroundColor: ['rgba(47, 152, 208, 0.2)',],
            showLine: true,
            data: [],
          }]
        };
      }
    }
  }
</script>

<style>
  .small {
    max-width: 600px;
    margin:  150px auto;
  }
</style>