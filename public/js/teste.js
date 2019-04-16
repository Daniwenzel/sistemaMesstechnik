$(document).ready(function() {
    $('.progress-bar').each(function() {

        var component = $(this).attr('data-tipo');
        var leitura = $(this).attr('data-leitura');

        if (component.startsWith('AN')) {
            if (leitura < 5) {
                console.log('anemometro menor que 5');
            }
            else if (leitura < 8) {
                console.log('anemometro menor que 8');
            }
            else {
                console.log('anemometro caiu no else');

            }
        }
        else if (component.startsWith('Bat12V')) {
            if (leitura < 5) {
                console.log('bateria menor que 5');
            }
            else if (leitura < 8) {
                console.log('bateria menor que 8');
            }
            else {
                console.log('bateria caiu no else');

            }
        }
        else if (component.startsWith('WV')) {
            if (leitura < 100) {
                console.log('angulo wv menor que 100');
            }
            else if (leitura < 200) {
                console.log('angulo wv menor que 200');
            }
            else {
                console.log('wv caiu no else');

            }
        }
        else if (component.startsWith('B')) {
            if (leitura < 5) {
                console.log('barometro menor que 5');
            }
            else if (leitura < 8) {
                console.log('barometro menor que 8');
            }
            else {
                console.log('barometro caiu no else');
            }
        }
        if (component.startsWith('T')) {
            if (leitura < 5) {
                console.log('temperatura menor que 5');
            }
            else if (leitura < 8) {
                console.log('temperatura menor que 8');
            }
            else {
                console.log('temperatura caiu no else');
            }
        }
        if (component.startsWith('U')) {
            if (leitura < 5) {
                console.log('umidade menor que 5');
            }
            else if (leitura < 8) {
                console.log('umidade menor que 8');
            }
            else {
                console.log('umidade caiu no else');
            }
        }
        else {
            console.log('nenhum sensor identificado');
        }
    })
});
