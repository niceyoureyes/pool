<template>
    <div>
        <h1>Это главная страница Pool 1.0</h1>
        <br>
        <h2>Этот сайт поможет вам отслеживать ваши тренировки по плаванию в лучшей форме</h2>
        <br>
        <h2><b>{{ message }}</b></h2>
        <div class="container-fluid">
            <div class="row">
                <div class="col-4">
                    <h2>Средний показатель Swolf (на 25 м):</h2>
                    <h2>Лучший средний темп (50 м):</h2>
                    <h2>Лучший средний темп (100 м):</h2>
                    <h2>Лучший средний темп (200 м):</h2>
                    <h2>Лучший средний темп (1000 м):</h2>
                    <h2>Дата последнего заплыва:</h2>
                    <h2>Суммарное время в бассейне:</h2>
                </div>
                <div class="col-4">
                    <h2>{{ mean_swolf }}</h2>
                    <h2>{{ best_mean_tempo_50 }}</h2>
                    <h2>{{ best_mean_tempo_100 }}</h2>
                    <h2>{{ best_mean_tempo_200 }}</h2>
                    <h2>{{ best_mean_tempo_1000 }}</h2>
                    <h2>{{ last_date }}</h2>
                    <h2>{{ all_duration }}</h2>
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