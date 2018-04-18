<header>
    <div id="hd_container">
         <div id= "nav_container">
             <nav>
                 <ul>
                     <li><a href="index.php">Home</a></li>
                     <li><a href="categories.php">Categories</a></li>
                     <li><a href="bestseller.php">Best Sellers</a></li>
                     <li><a href="newreleases.php">New Releases</a></li>
                 </ul>
             </nav> 
         </div>
        
         <div id="search_container"> 
           <form action="results.php" method="POST" id="search_form">
             <div id=search>Search
               <input type="search" id="mySearch" name="search">
               <button onclick="this.form.submit()" id="b_search">Search</button>
             </div>
           </form>
         </div>
        
         <div id="log_cart">
             <span><a href="login.php">Login</a></span>
			 <span><a href="register.php">Register</a></span>
             <span><a href="shoppingCart.php">Shopping Cart</a></span>
         </div>
          
    </div>
</header>