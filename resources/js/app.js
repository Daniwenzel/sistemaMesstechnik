
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('gsap/all');
require('./transitions');
require('moment');
require('daterangepicker');
require('./sweetalerts');
require('./datepicker');
require('dropzone');
require('jquery-mask-plugin/dist/jquery.mask');


window.L = require('leaflet/dist/leaflet');
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    iconUrl: require('leaflet/dist/images/marker-icon.png'),
    shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

window.barba = require('@barba/core');