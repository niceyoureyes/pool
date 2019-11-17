<template>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <h3 class="text-center">Загружены файлы ({{ filesFinish.length }})</h3>
            </div>
            <div class="col-6">
                <input type="file" class="btn btn-dark" name="files" multiple="" @change="fileInputChange">
            </div>
        </div>
        <br>
        <div class="row justify-content-center">
            <div class="col-10">
                <ul class="list-group">
                    <li class="list-group-item" v-for="file in filesFinish" v-bind:key="file.id">
                        {{ file.name }} : {{ file.type }} : {{ file.id }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            filesFinish: []
        }
    },
    methods:{
        async fileInputChange(){
            let files = Array.from(event.target.files);

            for(let item of files){
                await this.UploadFile(item);
            }
        },
        async UploadFile(item){
            let form = new FormData();
            form.append('file', item);
            form.append('name', item.name);

            await axios.post('load', form)
            .then(response => {
                item.id = this.filesFinish.length;
                this.filesFinish.push(item);
                console.log(response);
            })
            .catch(error => {
                console.log(error);
            })
        }
    }
}
</script>