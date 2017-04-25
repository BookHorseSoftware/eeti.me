function deleteFile(deletingElement, file, user){
  if( confirm("Are you sure you want to delete " + file + "? This is a permanent operation!")){
    $.ajax("?delete=" + file + "&u=" + user).success(function(d){
      deletingElement.parentElement.innerHTML=d;
    }).fail(function(d){
      deletingElement.parentElement.innerHTML="Error deleting file: " + d;
    });
  } else { }

}


function ee(v){
  document.getElementsByTagName("body")[0].innerHTML="<iframe frameborder=0 src='https://youtube.com/embed/" + v + "?autoplay=1' style='height: 100%; width: 100%;'></iframe>";
}

function hashChkr(){
  if( window.location.hash == "#miku" ){
    document.title = "IT'S MIKU TIME!!!!!!!1";
    var miku = ["swqbfMh467A", "qUuK1rdmuv4", "dGNoCICGmo0", "XPvdmCewUss", "1K3in6w9tt4"];
     ee(miku[Math.floor(Math.random()*miku.length)]);
  }
  else if( window.location.hash == "#pingas" ){
    document.title = "As soon as I saw the PINGAS, Doctor Robotnik, I thought you would want to know about it!";
    ee("gri2I0SZwgQ");
  }
}

function eightBallChkr(){

}

window.addEventListener("hashchange", hashChkr);
window.addEventListener("load", hashChkr);

window.addEventListener("keypress", eightBallChkr);
