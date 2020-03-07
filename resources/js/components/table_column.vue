<template>
    <span :class="class_name">
        <a v-if="fil" :class="class_name_a" :href="url.substring(0, url.length - 1) + (id - 1)">{{ name }}</a>
        <span v-else>{{ name }}</span>
    </span>
</template>

<script>
export default {
    props: [
        'id', 'name', 'ind', 'fil', 'url'
    ],
    data(){
        return{

        }
    },
    mounted(){
        
    },
    computed: {
        class_name: function() {
            if(this.ind == null)
                return null;

            console.log(this.ind[1]);

            if(this.ind[2] == "asc"){
                return "dropup";
            }
            else if(this.ind[2] == "desc"){
                return "dropdown";
            }
            return null; 
        },
        class_name_a: function() {
            if(this.ind == null)
                return null;

            if(this.ind[2] == "asc"){
                return "dropdown-toggle";
            }
            else if(this.ind[2] == "desc"){
                return "dropdown-toggle";
            }
            return null;
        }
    },
    methods:{
        
    }
}
</script>