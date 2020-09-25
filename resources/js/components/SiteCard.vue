<template>
    <div class="col-lg-12 grid-margin">
        <div class="card site-button-card" @click="showSiteInfo">
            <div class="card-body">
                <h4 class="card-title">{{ nome }}</h4>
                <div class="row">
                    <div class="col-md-6">
                        <p>Código: {{ codigo }}</p>
                        <p>Estação: {{ estacao }}</p>
                        <p>Nome do site: {{ sitename }}</p>
                        <p>Último envio EPE: {{ ultenvio }}</p>
                    </div>
                    <div class="col-md-6">
                        <img :src="path" class="site-image float-right">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "NewsPost",
        props: ['nome', 'codigo', 'estacao', 'sitename', 'ultenvio', 'path', 'route'],
        methods: {
            showSiteInfo() {
                window.location=this.route;
            }
        }
    }
</script>

<style lang="scss" scoped>
    .site-button-card {
        display: flex;
        align-items: stretch;
        justify-content: stretch;
    }
    .site-button-card:hover {
        cursor: pointer;
        opacity: 0.4;
    }
    .site-image {
        max-width: 50%;
        max-height: 50%;
        right: 0;
    }
</style>