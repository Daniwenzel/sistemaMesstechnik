$(document).ready(function () {
    var tl = new TimelineMax();

    barba.init({
        transitions: [
            {
                name: 'legacy-example',
                leave: function (data) {
                    var done = this.async();
                    TweenMax.to(data.current.container, 1, {
                        opacity: 0,
                        onComplete: done,
                    });
                    console.log('transicao lul');
                },
                enter: function (data) {
                    var done = this.async();
                    TweenMax.from(data.next.container, 1, {
                        opacity: 0,
                        onComplete: done,
                    });
                    console.log('transicao fecha');
                }
            },
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
                    console.log('beforeappear');
                },
                appear() {
                    console.log('appear');
                },
                afterAppear() {
                    console.log('afterappear');
                },
                beforeLeave() {
                    console.log('beforeleave');
                },
                leave: function () {
                    tl.to('.main-panel', 0.1, {opacity: 0});
                    tl.to('.page-transition-top', 0.8, {ease: Bounce.easeOut, y: "50%"}, "-=0");
                    tl.to('.page-transition-bot', 0.8, {ease: Bounce.easeOut, y: "-50%"}, "-=0.8");
                    console.log('leave')
                },

                afterLeave() {
                    console.log('afterleave');
                },
                beforeEnter() {
                    console.log('beforeenter');
                },
                enter: function () {
                    tl.to('.main-panel', 1, {opacity: 1});
                    tl.to('.page-transition-top', 0.8, { ease: Expo.easeOut, x: "-100%"}, "-=1");
                    tl.to('.page-transition-bot', 0.8, { ease: Expo.easeOut, x: "100%"}, "-=1");
                    console.log('enter');
                },
                afterEnter() {
                    console.log('afterenter');
                },
            },
        ],
    });

});
