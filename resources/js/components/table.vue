<template>
    <table class="table">
        <thead>
            <tr>
                <th scope="col" v-for="col in cols" v-bind:key="col.id" v-on:click="TableColumnChange(col)">
                    <div class="row form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" :id="'check' + col.id" :value="col.id">
                        <label class="form-check-label">{{ col.id }}</label>
                    </div>
                    <div class="row mt-4">
                        <ttcolumn v-if="indexes" :id = "col.id"
                                                 :name = "col.name"
                                                 :ind = "indexes1[col.id - 1]"
                                                 :fil = "filters1[col.id - 1]"
                                                 :url = "url">
                        </ttcolumn>
                        <span v-else>{{ col.name }}</span>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="row in rows" v-bind:key="row.id">
                <th scope="row">
                    <i>{{ row.id }}</i>
                </th>
                <td v-for="e in row.data" v-bind:key="e.id">
                    <span v-html="e.val"></span>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: [
        'columns', 'raws', 'indexes', 'filters', 'url'
    ],
    data(){
        return{
            cols: [],
            rows: [],
            indexes1: null,
            filters1: null
        }
    },
    mounted(){
        this.indexes1 = this.indexes;
        this.filters1 = this.filters;
        this.update();
    },
    methods:{
        update: function(){
            for(let i = 0; i < this.columns.length; i++)
            {
                let col = [];

                col.id = i + 1;
                col.name = this.columns[i];

                this.cols.push(col);
            }

            for(let i = 0; i < this.raws.length; i++)
            {
                let row = [];

                row.id = i + 1;
                row.data = [];
                
                for(let j in this.raws[i])
                {
                    let d = [];
                    d.val = this.raws[i][j];
                    d.id  = j;
                    row.data.push(d);
                }

                this.rows.push(row);
            }
        },
        TableColumnChange(col){
            console.log(col.id);
            console.log(col.name);
        }
    }
}
</script>