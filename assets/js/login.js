var loginCookie = localStorage.getItem("login");

function cekLogin(level) {
    if ( loginCookie == "true" ) {
    	console.clear();
        console.log("sudah logged in");
        if (localStorage.getItem("level")!=level){
            logout();
        }else{
            console.log( "user = " + localStorage.getItem("user"));
            console.log( "level = " + localStorage.getItem("level"));
        }
    } else {
    	console.clear();
        console.log("belum logged in");
        // location.replace('../index.html');
        logout();
        //harus di set
        location.replace('../login');
    }

}

function logout() {
    localStorage.setItem("login", "false");
    localStorage.setItem("user", "");
    localStorage.setItem("fullname", "");
    localStorage.setItem("level", "");
    localStorage.setItem("no_induk", "");
    //sesi lokasi dan menu
    localStorage.setItem("lokasi", "");
    localStorage.setItem("menu", "");
    // location.replace('../index.html');
    //harus di set
    location.replace('../login');
}
