$(document).ready(function() {
    var tl = new TimelineMax({
        paused:true,
        repeat:-1,
    });

    tl.set(".auth-bg-1", {backgroundSize:"105% 105%",background:'url("images/auth/home-bg1.jpg")'})
        .to(".auth-bg-1", 6, {
            backgroundPosition:"25% 0",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .set(".auth-bg-1", {backgroundSize:"105% 105%",background:'url("images/auth/home-bg2.jpg")'})
        .to(".auth-bg-1", 6, {
            backgroundPosition:"0 25%",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .set(".auth-bg-1", {backgroundSize:"105% 105%",background:'url("images/auth/home-bg3.jpg")'})
        .to(".auth-bg-1", 6, {
            backgroundPosition:"20% 20%",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .set(".auth-bg-1", {backgroundSize:"105% 105%", background:'url("images/auth/home-bg4.jpg")'})
        .to(".auth-bg-1", 6, {
            backgroundPosition:"25% 0",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .set(".auth-bg-1", {backgroundSize:"150% 150%",background:'url("images/auth/home-bg5.jpg")'})
        .to(".auth-bg-1", 10, {
            backgroundPosition:"0 100%",
            autoRound:false,
            ease: Power1.ease0ut
        })
        .progress(1).progress(0)
        .play();
});