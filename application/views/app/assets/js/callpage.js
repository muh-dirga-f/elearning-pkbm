  function callPage(pageRefInput,menu) {
    $.ajax({
      url: pageRefInput,
      type: "GET",
      dataType: "text",
      success: function(res){
        console.log("halaman sedang diload");
        $("#content").html(res);
        //set session
        sessionStorage.setItem("lokasi", pageRefInput);
        sessionStorage.setItem("menu", menu);
        //set menu
        $('.hmenu').find('li.active').removeClass('active');  
        $('#'+menu).addClass('active');
      },
      error: function(err){
        console.log("halaman gagal terload");
        alert("halaman gagal terload");
      },
      complete: function(xhr, status){
        console.log("halaman telah selesai diload");
      }
    })
  }