/* ChartJS
 * -------
 * Data and config for chartjs
 */
'use strict';

// dados de sensores fixados para exposição

var labels = [
    '00:00:00',
    '00:10:00',
    '00:20:00',
    '00:30:00',
    '00:40:00',
    '00:50:00',
    '01:00:00',
    '01:10:00',
    '01:20:00',
    '01:30:00',
    '01:40:00',
    '01:50:00',
    '02:00:00',
    '02:10:00',
    '02:20:00',
    '02:30:00',
    '02:40:00',
    '02:50:00',
    '03:00:00',
    '03:10:00',
    '03:20:00',
    '03:30:00',
    '03:40:00',
    '03:50:00',
    '04:00:00',
    '04:10:00',
    '04:20:00',
    '04:30:00',
    '04:40:00',
    '04:50:00',
    '05:00:00',
    '05:10:00',
    '05:20:00',
    '05:30:00',
    '05:40:00',
    '05:50:00',
    '06:00:00',
    '06:10:00',
    '06:20:00',
    '06:30:00',
    '06:40:00',
    '06:50:00',
    '07:00:00',
    '07:10:00',
    '07:20:00',
    '07:30:00',
    '07:40:00',
    '07:50:00',
    '08:00:00',
    '08:10:00',
    '08:20:00',
    '08:30:00',
    '08:40:00',
    '08:50:00',
    '09:00:00',
    '09:10:00',
    '09:20:00',
    '09:30:00',
    '09:40:00',
    '09:50:00',
    '10:00:00',
    '10:10:00',
    '10:20:00',
    '10:30:00',
    '10:40:00',
    '10:50:00',
    '11:00:00',
    '11:10:00',
    '11:20:00',
    '11:30:00',
    '11:40:00',
    '11:50:00',
    '12:00:00',
    '12:10:00',
    '12:20:00',
    '12:30:00',
    '12:40:00',
    '12:50:00',
    '13:00:00',
    '13:10:00',
    '13:20:00',
    '13:30:00',
    '13:40:00',
    '13:50:00',
    '14:00:00',
    '14:10:00',
    '14:20:00',
    '14:30:00',
    '14:40:00',
    '14:50:00',
    '15:00:00',
    '15:10:00',
    '15:20:00',
    '15:30:00',
    '15:40:00',
    '15:50:00',
    '16:00:00',
    '16:10:00',
    '16:20:00',
    '16:30:00',
    '16:40:00',
    '16:50:00',
    '17:00:00',
    '17:10:00',
    '17:20:00',
    '17:30:00',
    '17:40:00',
    '17:50:00',
    '18:00:00',
    '18:10:00',
    '18:20:00',
    '18:30:00',
    '18:40:00',
    '18:50:00',
    '19:00:00',
    '19:10:00',
    '19:20:00',
    '19:30:00',
    '19:40:00',
    '19:50:00',
    '20:00:00',
    '20:10:00',
    '20:20:00',
    '20:30:00',
    '20:40:00',
    '20:50:00',
    '21:00:00',
    '21:10:00',
    '21:20:00',
    '21:30:00',
    '21:40:00',
    '21:50:00',
    '22:00:00',
    '22:10:00',
    '22:20:00',
    '22:30:00',
    '22:40:00',
    '22:50:00',
    '23:00:00',
    '23:10:00',
    '23:20:00',
    '23:30:00',
    '23:40:00',
    '23:50:00'
];

var anemometrosData = {
    labels: labels
};

var windvanesData = {
    labels: labels
};

var barometrosData = {
    labels: labels
};

var bateriasData = {
    labels: labels
};

var temperaturasData = {
    labels: labels
};

var umidadesData = {
    labels: labels
};

var options = {
    scales: {
        yAxes: [{
            ticks: {
                // valor mínimo da axis y = 800 para os barometros, caso o valor for menor que 800, o gráfico se adapta.
                suggestedMin: 0
            }
        }],
        xAxes: [{
            ticks: {
                minRotation: 90,
                autoSkip: true,
                maxTicksLimit: 24.1
            }
        }]
    },
    legend: {
        display: true
    },
    elements: {
        point: {
            radius: 0
        }
    }

};

if (jQuery("#anemometroChart").length) {
    var anemometrosCanvas = $("#anemometroChart").get(0).getContext("2d");
    var anChart = new Chart(anemometrosCanvas, {
        type: 'line',
        data: anemometrosData,
        options: options
    });
}

if (jQuery("#windvaneChart").length) {
    var windvanesCanvas = $("#windvaneChart").get(0).getContext("2d");
    var wvChart = new Chart(windvanesCanvas, {
        type: 'line',
        data: windvanesData,
        options: options
    });
}

if (jQuery("#barometroChart").length) {
    var barometroCanvas = $("#barometroChart").get(0).getContext("2d");
    var baChart = new Chart(barometroCanvas, {
        type: 'line',
        data: barometrosData,
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        // valor mínimo da axis y = 800 para os barometros, caso o valor for menor que 800, o gráfico se adapta.
                        suggestedMin: 800
                    }
                }],
                xAxes: [{
                    ticks: {
                        minRotation: 90,
                        autoSkip: true,
                        maxTicksLimit: 24.1
                    }
                }]
            },
            legend: {
                display: true
            },
            elements: {
                point: {
                    radius: 0
                }
            }
        }
    });
}

if (jQuery("#temperaturaChart").length) {
    var temperaturaCanvas = $("#temperaturaChart").get(0).getContext("2d");
    var tempChart = new Chart(temperaturaCanvas, {
        type: 'line',
        data: temperaturasData,
        options: options
    });
}

if (jQuery("#umidadeChart").length) {
    var umidadeCanvas = $("#umidadeChart").get(0).getContext("2d");
    var umiChart = new Chart(umidadeCanvas, {
        type: 'line',
        data: umidadesData,
        options: options
    });
}

if (jQuery("#bateriaChart").length) {
    var bateriaCanvas = $("#bateriaChart").get(0).getContext("2d");
    var batChart = new Chart(bateriaCanvas, {
        type: 'line',
        data: bateriasData,
        options: options
    });
}

function addChartData(chart, label, data) {
    chart.data.labels.push(label);
    chart.data.datasets.push({
        data: data,
        label: label,
        borderColor: randomColor(),
        borderWidth: 2,
        fill: false
    });
    chart.update();
}

function randomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}