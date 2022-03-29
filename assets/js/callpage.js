  function callPage(pageRefInput, menu) {
    console.clear();
  	//harus di set
  	page = pageRefInput.slice(0, -5);
  	$.ajax({
  		// url: pageRefInput,
  		url: page,
  		type: "GET",
  		dataType: "text",
  		success: function (res) {
  			console.log("halaman '"+page+"' sedang diload.....");
  			$("#content").html(res);
  			localStorage.setItem("lokasi", pageRefInput);
  			localStorage.setItem("menu", menu);
  			//set menu
  			$('.hmenu').find('li.active').removeClass('active');
  			$('#' + menu).addClass('active');
  		},
  		error: function (err) {
  			console.log("halaman '"+page+"' gagal terload!");
  		},
  		complete: function (xhr, status) {
  			console.log("selesai!");
  		}
  	})
  }
