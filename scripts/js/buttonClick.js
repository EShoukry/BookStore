function addBookToUserCart(bookId, userId, doRedirect) {
    $.ajax({
        type: "POST",
        url: "http://localhost/BookStore/scripts/php/addToCart.php",
        contentType: "json",
        dataType: "json",
        data: {ajax_bookIdToCart: 'bookId', ajax_userIdToCart: 'userId'},
        success: function (message) {
            window.alert("Added book to cart!\n" + message);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            window.alert("Error:\n" + errorThrown + "\n" + jqXHR + "\n" + textStatus);
        }
    });
    return doRedirect == true; //return true means redirect to another page; else no redirect
}  