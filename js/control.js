$(document).keypress(function(e){
    var x = e.keyCode || e.which;    
    //document.getElementById("demo").innerHTML = "The Unicode value is: " + x;
    //alert(x);
    switch (x){
    	case 106: $("#prev").click(); break; //j
    	case 107: $("#next").click(); break; //k
    	case 102: $("#fileList").toggle(); break; //f
    	case 114: $("#refresh").click(); break; //r
    	default: //do nothing
    }    
});

$(document).ready(function(){
    $(".flist").hover(function(){
    	$(this).css("cursor","pointer");
    	$(this).css("color","white");
        $(this).css("background", "linear-gradient(to right, #EB6572 , #F5B78D)");        
        }, function(){
        $(this).css("background", "white");
        $(this).css("color","#242958");
    });

    $(".flist").click(function(){
    	//alert($(this).html());
    	var fileName = $(this).html();
	    var method = "post"; // Set method to post by default if not specified.
	    var path = "";
	    // The rest of this code assumes you are not using a library.
	    // It can be made less wordy if you use one.
	    var form = document.createElement("form");
	    form.setAttribute("method", method);
	    form.setAttribute("action", path);

        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", "fileName");
        hiddenField.setAttribute("value", fileName);
        form.appendChild(hiddenField);

        hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", "fileUpload");
        hiddenField.setAttribute("value", "YES");
        form.appendChild(hiddenField);


	    document.body.appendChild(form);
	    form.submit();

    });


});