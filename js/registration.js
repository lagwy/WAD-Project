$(function () {

    function checkEmpty(text) {
        return text === "";
    }

    $('#register').click(function (event) {
        event.preventDefault();

       // Get all the values and then check if one of them is empty
       var name = $('#name').val();
       var last_name = $('#last_name').val();
       var address = $('#address').val();
       var city = $('#city').val();
       var state = $('#state').val();
       var country = $('#country').val();
       var zip_code = $('#zip_code').val();
       var phone= $('#phone').val();
       var email = $('#email').val();
       var conf_email = $('#email-conf').val();
       var passwrd = $('#pwd').val();
       var conf_passwrd = $('#pwd-conf').val();

       var complete = true;

       if (checkEmpty(name)){
           swal("Por favor, escriba su nombre");
           return;
       }

       if (checkEmpty(last_name)){
           swal("Por favor, escriba su apellido");
           return;
       }

       if (checkEmpty(address)){
           swal("Por favor, escriba su dirección");
           return;
       }

       if (checkEmpty(city)){
           swal("Por favor, escriba su ciudad");
           return;
       }

       if (checkEmpty(state)){
           swal("Por favor, escriba su estado");
           return;
       }

       if (checkEmpty(country)){
           swal("Por favor, escriba su país");
           return;
       }

       if (checkEmpty(zip_code)){
           swal("Por favor, escriba su código postal");
           return;
       }

       if (checkEmpty(phone)){
           swal("Por favor, escriba su número de teléfono");
           return;
       }

       if (checkEmpty(email)){
           swal("Por favor, escriba su correo electrónico");
           return;
       }

       if (checkEmpty(conf_email)){
           swal("Por favor, vuelva a escribir su correo electrónico");
           return;
       }

       if (checkEmpty(passwrd)){
           swal("Por favor, escriba una contraseña");
           return;
       }

       if (checkEmpty(conf_passwrd)){
           swal("Por favor, vuelva a escribir su contraseña");
           return;
       }

       if (complete){
           // Every field has data

           // Validate emails match
           if (email === conf_email){
               // Validate password length
               if (passwrd.length < 6 || passwrd.length > 15){
                   swal("La contraseña debe tener entre 6 y 15 caracteres de longitud.");
               } else{
                   if (passwrd === conf_passwrd){
                       // All the information is correct
                       var jsonObject = {
                           "action" : "REGISTER",
                           "name" : name,
                           "last_name" : last_name,
                           "address" : address,
                           "city" : city,
                           "state" : state,
                           "country" : country,
                           "zip" : zip_code,
                           "phone" : phone,
                           "email" : email,
                           "pwd" : passwrd
                       };

                       $.ajax({
                           type: "POST",
                           url: "data/ApplicationLayer.php",
                           data : jsonObject,
                           dataType : "json",
                           contentType : "application/x-www-form-urlencoded",
                           success: function(jsonData) {
                               if (jsonData.status == 1){
                                   swal("Registro exitoso");
                                   window.location.replace("home.html");
                               } else {
                                   if (jsonData.status == 2){
                                       swal("Este email ya está en uso. Por favor, inicie sesión.");
                                       window.location.replace("home.html");
                                   } else {
                                       swal("Ocurrió un error al registrar");
                                   }
                               }

                           },
                           error: function(errorMsg){
                               alert("Error al registrar");
                           }
                       }); // End of ajax call
                   } else {
                       swal("Las contraseñas no coinciden.");
                   }
               }
           } else {
               swal("Las correos electrónicos no coinciden.");
           }
       }
   });


});
