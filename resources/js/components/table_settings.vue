<template>
    <div class="col-12">
        <div v-if="id != 1" class="row custom-control custom-radio">
            <input type="radio" class="custom-control-input" name="radio-stacked-X" :id="'rx' + id">
            <label class="custom-control-label" :for="'rx' + id">{{ id }}</label>
        </div>
        <div v-else class="row">
            X
        </div>

        <div v-if="id != 1" class="row custom-control custom-radio">
            <input type="radio" class="custom-control-input" name="radio-stacked-Y" :id="'ry' + id">
            <label class="custom-control-label" :for="'ry' + id">{{ id }}</label>
        </div>
        <div v-else class="row">
            Y
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'id'
    ],
    data(){
        return{

        }
    },
    mounted(){
        
    },
    computed: {

    },
    methods:{
        
    }
}
</script>