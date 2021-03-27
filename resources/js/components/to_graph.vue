<template>
    <div class="row justify-content-center">
        <div class="col-4"></div>
        <div class="col-4">
            <div class="row justify-content-center">
                <h3 class="text-center mr-4">Упражнения</h3>
                <span class="btn btn-dark" role="button" @click="Go(url)">График</span>
            </div>
        </div>
        <div class="col-4"></div>
    </div>
</template>

<script>
export default {
    props: [
        'url'
    ],
    methods:{
        Go(url){
            let x = $("input[name='radio-stacked-X']:checked").map(function(){
                return $(this).attr('id');
            }).get();
            
            let y = $("input[name='radio-stacked-Y']:checked").map(function(){
                return $(this).attr('id');
            }).get();

            let array = [("" + x).substring(2), ("" + y).substring(2)];
            array = JSON.stringify(array);
            url = url + '&xy=' + array;

            console.log(array);
            console.log(url);

            window.location.replace(url);
        }
    }
}
</script>