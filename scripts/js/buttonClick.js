function addBookToUserCart(bookId, userId, doRedirect) {
    $.ajax({
        type: "POST",
        url: "http://localhost/BookStore/scripts/php/addToCart.php",
        contentType: "json",
        async: false,
        data: {ajax_bookIdToCart: bookId, ajax_userIdToCart: userId},
        success: function () {
            window.alert("Added book to cart!");
        },
        error: function (jqXHR, textStatus, errorThrown) {
            window.alert("Error: " + errorThrown);
        }
    });
    return doRedirect == true; //return true means redirect to another page; else no redirect
}  