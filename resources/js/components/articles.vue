<template>
    <div>
        <div class="container-fluid">
            <h2><b>{{ message }}</b></h2>
            <div class="row">
                <div class="col-6">
                    <h4>Средний показатель Swolf (25 м):</h4>
                    <h4>Лучший средний темп (50 м):</h4>
                    <h4>Лучший средний темп (100 м):</h4>
                    <h4>Лучший средний темп (200 м):</h4>
                    <h4>Лучший средний темп (1000 м):</h4>
                    <h4>Дата последнего заплыва:</h4>
                    <h4>Суммарное время в бассейне:</h4>
                </div>
                <div class="col-6">
                    <h4>{{ mean_swolf }}</h4>
                    <h4>{{ best_mean_tempo_50 }}</h4>
                    <h4>{{ best_mean_tempo_100 }}</h4>
                    <h4>{{ best_mean_tempo_200 }}</h4>
                    <h4>{{ best_mean_tempo_1000 }}</h4>
                    <h4>{{ last_date }}</h4>
                    <h4>{{ all_duration }}</h4>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'message', 'mean_swolf', 'last_date', 'all_duration',
        'best_mean_tempo_50', 'best_mean_tempo_100', 'best_mean_tempo_200', 'best_mean_tempo_1000'
    ],
    data(){
        return{

        }
    }
}
</script>