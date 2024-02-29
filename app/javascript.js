const p1 = document.querySelector(".first");
const p2 = document.querySelector(".second");
const p3 = document.querySelector(".third");
const container = document.querySelector(".message");
container.addEventListener('mouseover',(e)=>{
    if (e.clientX>900) {
        p1.style.transform = "translate(400px)";
        p2.style.transform = "translate(-400px)";
        p3.style.transform = "translate(400px)";
    }
    else{
        p1.style.transform = "translate(-400px)";
        p2.style.transform = "translate(400px)";
        p3.style.transform = "translate(-400px)";
    }
});
container.addEventListener('mouseout',()=>{
    p1.style.transform = "translate(0px)" ;
    p2.style.transform = "translate(0px)" ;
    p3.style.transform = "translate(0px)" ;

});

const nav = document.querySelector("#nav-bar");
window.addEventListener("scroll",(e)=>{
    if (window.scrollY>120){
        nav.style.transform = "translate(0,-200px)";
    }
    else{
        nav.style.transform = "translate(0,0)";

    }
});

function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

function botFunction(){
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;

}

function delay(time) {
    return new Promise(resolve => setTimeout(resolve, time));
}

const title = document.querySelector(".name");
async function revient(){
    title.style.display="none";
    title.style.transform="translate(-1300px)";

    await delay(200);
    title.style.display="block";
    await delay(200);

    title.style.transform="translate(0px)";


}
function bouge(){
    title.style.transform="translate(1300px)";
    setTimeout('revient()',800);
    setTimeout('bouge()',12000);

}
setTimeout('bouge()',5000);



