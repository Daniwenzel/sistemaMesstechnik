<template>
    <div class="col-lg-12 grid-margin site-button-card">
        <div class="card card-button" @click="showSiteInfo">
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
                        <div class="card-img-holder card-img-absolute">
                            <img :src="path" class="site-image">
                        </div>
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
    .site-image-container {
        height: 40vh;
        width: 25vw;
    }
    .site-image {
        max-width: 100%;
	    max-height: 100%;
    }
</style>