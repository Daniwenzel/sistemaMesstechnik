$(document).ready(function() {
    var tl = new TimelineMax({
        paused:true,
        repeat:-1,
    });

    tl.set("#login-bg", {backgroundSize:"120% 120%",background:'url("images/auth/home-bg1.webp")'}, "-=0.5")
        .to("#login-bg", 5, {
            backgroundPosition:"0% 80%",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .to("#login-bg", 0.5,{opacity: 0.3}, "-=0.5")
        .to("#login-bg", 0.65,{opacity: 1})
        .set("#login-bg", {backgroundSize:"110% 135%",background:'url("images/auth/home-bg2.webp")'}, "-=0.5")
        .to("#login-bg", 5, {
            backgroundPosition:"65% 0",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .to("#login-bg", 0.5,{opacity: 0.3}, "-=0.5")
        .to("#login-bg", 0.65,{opacity: 1})
        .set("#login-bg", {backgroundSize:"105% 105%",background:'url("images/auth/home-bg3.webp")'}, "-=0.5")
        .to("#login-bg", 5, {
            backgroundPosition:"35% 35%",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .to("#login-bg", 0.5,{opacity: 0.3}, "-=0.5")
        .to("#login-bg", 0.65,{opacity: 1})
        .set("#login-bg", {backgroundSize:"105% 105%", background:'url("images/auth/home-bg4.webp")'}, "-=0.5")
        .to("#login-bg", 5, {
            backgroundPosition:"65% 0",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .to("#login-bg", 0.5,{opacity: 0.3}, "-=0.5")
        .to("#login-bg", 0.65,{opacity: 1})
        .set("#login-bg", {backgroundSize:"105% 105%",background:'url("images/auth/home-bg5.webp")'}, "-=0.5")
        .to("#login-bg", 5, {
            backgroundPosition:"0 100%",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .to("#login-bg", 0.5,{opacity: 0.3}, "-=0.5")
        .to("#login-bg", 0.65,{opacity: 1})
        .set("#login-bg", {backgroundSize:"105% 105%",background:'url("images/auth/home-bg6.webp")'}, "-=0.5")
        .to("#login-bg", 5, {
            backgroundPosition:"0 65%",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .to("#login-bg", 0.5,{opacity: 0.3}, "-=0.5")
        .to("#login-bg", 0.65,{opacity: 1})
        .set("#login-bg", {backgroundSize:"105% 105%",background:'url("images/auth/home-bg7.webp")'}, "-=0.5")
        .to("#login-bg", 5, {
            backgroundPosition:"35% 35%",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .to("#login-bg", 0.5,{opacity: 0.3}, "-=0.5")
        .to("#login-bg", 0.65,{opacity: 1})
        .set("#login-bg", {backgroundSize:"100% 150%", background:'url("images/auth/home-bg8.webp")'}, "-=0.5")
        .to("#login-bg", 5, {
            backgroundPosition:"0% 80%",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .to("#login-bg", 0.5,{opacity: 0.3}, "-=0.5")
        .to("#login-bg", 0.65,{opacity: 1})
        .set("#login-bg", {backgroundSize:"105% 100%",background:'url("images/auth/home-bg9.webp")'}, "-=0.5")
        .to("#login-bg", 5, {
            backgroundPosition:"100% 0%",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .to("#login-bg", 0.5 ,{opacity: 0.3}, "-=0.5")
        .to("#login-bg", 0.65 ,{opacity: 1})
        .set("#login-bg", {backgroundSize:"100% 135%", background:'url("images/auth/home-bg10.webp")'}, "-=0.5")
        .to("#login-bg", 5, {
            backgroundPosition:"0% 100%",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .to("#login-bg", 0.5,{opacity: 0.3}, "-=0.5")
        .to("#login-bg", 0.65,{opacity: 1})
        .progress(1).progress(0)
        .play();
});