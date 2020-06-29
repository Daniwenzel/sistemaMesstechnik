<template>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card card-button" @click="showSiteInfo">
            <div class="card-body">
                <h4 class="card-title">{{ nome }}</h4>
                <div class="row">
                    <div class="col-md-6">
                        <p>codigo: {{ codigo }}</p>
                        <p>estacao: {{ estacao }}</p>
                        <p>sitename: {{ sitename }}</p>
                        <p>ultenvio: {{ ultenvio }}</p>
                    </div>
                    <div class="col-md-6">
                        <div class="site-image-container">
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

<style scoped>

</style>