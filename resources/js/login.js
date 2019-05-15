$(document).ready(function() {
    var tl = new TimelineMax({
        paused:true,
        repeat:-1,
    });

    tl.set("#login-bg", {backgroundSize:"105% 105%",background:'url("images/auth/home-bg1.jpg")'}, "-=0.5")
        .to("#login-bg", 6, {
            backgroundPosition:"25% 0",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .to("#login-bg", 0.5 ,{opacity: 0.3}, "-=0.5")
        .to("#login-bg", 0.5 ,{opacity: 1})
        .set("#login-bg", {backgroundSize:"105% 105%",background:'url("images/auth/home-bg2.jpg")'}, "-=0.5")
        .to("#login-bg", 6, {
            backgroundPosition:"0 25%",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .to("#login-bg", 0.5 ,{opacity: 0.3}, "-=0.5")
        .to("#login-bg", 0.5 ,{opacity: 1})
        .set("#login-bg", {backgroundSize:"105% 105%",background:'url("images/auth/home-bg3.jpg")'}, "-=0.5")
        .to("#login-bg", 6, {
            backgroundPosition:"20% 20%",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .to("#login-bg", 0.5 ,{opacity: 0.3}, "-=0.5")
        .to("#login-bg", 0.5 ,{opacity: 1})
        .set("#login-bg", {backgroundSize:"105% 105%", background:'url("images/auth/home-bg4.jpg")'},"-=0.5")
        .to("#login-bg", 6, {
            backgroundPosition:"25% 0",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .to("#login-bg", 0.5 ,{opacity: 0.3}, "-=0.5")
        .to("#login-bg", 0.5 ,{opacity: 1})
        .set("#login-bg", {backgroundSize:"150% 150%",background:'url("images/auth/home-bg5.jpg")'},"-=0.5")
        .to("#login-bg", 10, {
            backgroundPosition:"0 100%",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .to("#login-bg", 0.5 ,{opacity: 0.3}, "-=0.5")
        .to("#login-bg", 0.5 ,{opacity: 1})
        .progress(1).progress(0)
        .play();
});