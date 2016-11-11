 var text = "";
     text += '<ul class="nav navbar-nav navbar-right hidden-sm">';
     text += '<li><a href="catalog.html">Catálogo</a></li>';
    text += '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"';
text += 'aria-expanded="false">Sobre nosotros<span class="caret"></span></a>';
    text += '<ul class="dropdown-menu" role="menu">';
    text += '<li><a href="mission.html">Misión</a></li>';
    text += '<li><a href="vision.html">Visión</a></li>';
    text += '<li><a href="contact.html">Contacto</a></li></ul></li>';
    text += '<li><a href="register.html">Regístrate</a></li>';
 
 var jsonObject = {
     "action" : "SESSION"
 };

 $.ajax({
     type: "POST",
     url: "data/ApplicationLayer.php",
     data : jsonObject,
     dataType : "json",
     contentType : "application/x-www-form-urlencoded",
     success: function(jsonData) {
         if (jsonData.status == 2){
             // There is no active session
             text +='<li><a href="#" data-toggle="modal" data-target="#login-modal">Ingresa</a></li>';
             text += '</ul><form class="navbar-form navbar-left" role="search"><div class="form-group"><input type="text" class="form-control" placeholder="¿Qué deseas buscar?"></div><button type="submit" class="btn btn-default">Submit</button></form>';
             text += '<!-- Modal -->';
             text +='<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;"><div class="modal-dialog"><div class="loginmodal-container"><h1>Ingresar a tu cuenta</h1><br><form><input id="login_user" type="text" name="user" placeholder="E-mail"><input id="login_pwd" type="password" name="pass" placeholder="Contraseña"><div id="login_button" style="text-align:center;cursor:pointer;" class="login loginmodal-submit">Ingresar</div></form> <div class="login-help"><a href="#">Regístrate</a> - <a href="#">Olvidé mi contraseña</a></div></div></div></div>';
         } else {
             text += '</ul><form class="navbar-form navbar-left" role="search"><div class="form-group"><input type="text" class="form-control" placeholder="¿Qué deseas buscar?"></div><button type="submit" class="btn btn-default">Submit</button></form>';
         }
         $('#menu_header').append(text);
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
                             location.reload();
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
         });
     },
     error: function(){
         alert("Error al revisar la sesión");
     }
 });
