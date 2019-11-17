<template>
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" v-for="col in cols" v-bind:key="col.id">
                        {{ col.name }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in rows" v-bind:key="row.id">
                    <th scope="row">
                        {{ row.id }}
                    </th>
                    <td v-for="val in row.data" v-bind:key="val">
                        {{ val }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: [
        'columns', 'raws'
    ],
    data(){
        return{
            cols: [],
            rows: []
        }
    },
    mounted(){
        this.update();
    },
    methods:{
        update: function(){
            for(let i = 0; i < this.columns.length; i++)
            {
                let col = [];
                col.id = i;
                col.name = this.columns[i];
                this.cols.push(col);
            }

            for(let i = 0; i < this.raws.length; i++)
            {
                let row = [];
                row.id = i;
                row.data = this.raws[i];
                this.rows.push(row);
            }
        }
    }
}
</script>