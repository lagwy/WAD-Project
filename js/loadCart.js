var jsonObject = {
    "action": "ADD_PRODUCT",
    "Quantity": parametersQty[1],
    "IdProduct": parametersId[1]
};
$.ajax({
    type: "POST",
    url: "data/ApplicationLayer.php",
    data: jsonObject,
    dataType: "json",
    contentType: "application/x-www-form-urlencoded",
    success: function (jsonData) {
        if (jsonData.status == "SUCCESS") {
            // swal("Producto añadido al carrito exitosamente");
            window.location.replace("catalog.html");
        } else {
            swal("Id de producto equivocado");
            window.location.replace("home.html");
        }
    },
    error: function (errorMsg) {
        alert("Error al añadir al carrito.");
    }
}); // End of ajax call