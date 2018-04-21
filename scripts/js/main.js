function OnSubmitShoppingCartForm()
{
    alert("here");
    if (document.pressed == 'cart_input_update')
    {
        document.form_shoppingCart.action = "books_shoppingCart.php";
    } else if (document.pressed == 'cart_input_purchase')
    {
        document.form_shoppingCart.action = "../reviewOrder.php";
    }
    return true;
}