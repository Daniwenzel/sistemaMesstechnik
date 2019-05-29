$(document).ready(function () {
    var tl = new TimelineMax();

    barba.init({
        transitions: [
            {
                name: 'page-transition',
                to: {
                    namespace: [
                        'user',
                        'home',
                        'company',
                        'register-company',
                        'roles',
                        'tower',
                        'user-config',
                        'windfarms',
                        'windfarms-info'
                    ],
                },
                sync: true,
                beforeAppear() {
                },
                appear() {
                },
                afterAppear() {
                },
                beforeLeave() {
                },
                leave: function () {
                    tl.to('.main-panel', 0.1, {opacity: 0});
                    tl.to('.page-transition-top', 0.8, {ease: Bounce.easeOut, y: "50%"}, "-=0");
                    tl.to('.page-transition-bot', 0.8, {ease: Bounce.easeOut, y: "-50%"}, "-=0.8");
                },

                afterLeave() {
                },
                beforeEnter() {
                },
                enter: function () {
                    tl.to('.main-panel', 1, {opacity: 1});
                    tl.to('.page-transition-top', 0.8, { ease: Expo.easeOut, x: "-100%"}, "-=1");
                    tl.to('.page-transition-bot', 0.8, { ease: Expo.easeOut, x: "100%"}, "-=1");
                },
                afterEnter() {
                },
            },
        ],
    });

});
