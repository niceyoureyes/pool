<template>
  <div class="small">
    <scatter :chart-data="datacollection" :chart-options="options"></scatter>
    <button @click="fillData()">Randomize</button>
  </div>
</template>

<script>
  import Scatter from './ScatterChart.js'

  export default {
    components: {
      Scatter
    },
    data () {
      return {
        datacollection: null,
        options: null
      }
    },
    mounted () {
      this.fillData()
    },
    methods: {
      fillData () {
          console.log("firing phiil");
        this.datacollection = {
            datasets: [{
            label: 'My First dataset',
            borderColor: 'rgba(47, 152, 208, 0.2)',
            backgroundColor: [
                                'rgba(47, 152, 208, 0.2)',
                            ],
            showLine: true,
            data: [{
                x: 100,
                y: 10,
            }, {
                x: 100.5,
                y: 20,
            }, {
                x: 100.9,
                y: 30,
            }, {
                x: 110,
                y: 50,
            }]
        }]
      }
        console.log(this.datacollection);
      }

  }
}

function randomScalingFactor () {
  return Math.round(Math.random() * 200) - 100;
}
</script>

<style>
  .small {
    max-width: 600px;
    margin:  150px auto;
  }
</style>