$(function () {
   
    $("#login_button").click(function (event) {
        event.preventDefault();
        var email = $("#login_user").val();
        var pwd = $("#login_pwd").val();
        
        // Validate that both are not empty
        if (email.trim() != "" && pwd.trim() != ""){
            // Perform the ajax call
            var jsonObject = {
                "action" : "LOGIN",
                "email" : email,
                "pwd" : pwd
            };

            $.ajax({
                type: "POST",
                url: "data/ApplicationLayer.php",
                data : jsonObject,
                dataType : "json",
                contentType : "application/x-www-form-urlencoded",
                success: function(jsonData) {
                    if (jsonData.status == 1){
                        swal("Inicio de sesión exitoso");
                        // window.location.replace("home.html");
                    } else {
                        if (jsonData.status == 2){
                            swal("Datos incorrectos");
                        } else {
                                swal("Ocurrió un error al iniciar sesión");
                        }
                    }

                },
                error: function(errorMsg){
                    alert("Error al registrar");
                }
            }); // End of ajax call
        } else {
            swal("Por favor, llene los campos para iniciar sesión.");
        }
    })
    
});