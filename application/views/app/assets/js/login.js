var loginCookie = sessionStorage.getItem("login");

function cekLogin(level) {
    if ( loginCookie == "true" ) {
    	console.clear();
        console.log("sudah logged in");
        if (sessionStorage.getItem("level")!=level){
            logout();
        }else{
            console.log( "user = " + sessionStorage.getItem("user"));
            console.log( "level = " + sessionStorage.getItem("level"));
        }
    } else {
    	console.clear();
        console.log("belum logged in");
        location.replace('../index.html');
    }

}

function logout() {
    sessionStorage.setItem("login", "false");
    location.replace('../index.html');
}
