<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title>Bookymark</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/custom.css">
    </head>
    <body>
        <div id="menu">
            <a href="index.php"><strong><i>Bookymark</i></strong></a>
            <a href="http://www.w3schools.com" target="_blank" >W3schools</a>
            <a href="google.html" target="_blank" >facebook</a>
            <a href="http://www.google.com/" target="_blank" >W3schools</a>
            <a href="http://www.mintlaunch.com" target="_blank" >Mintlaunch</a>
            <button id="addLink"  class="pull-right" onclick="addLink()"><span class="glyphicon glyphicon-plus"></span></button>
        </div>
     <form action="submit.php" method="post">
        <div class="form-group">
            <input type="email" class="form-control input-lg" placeholder="Email address" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control input-lg" placeholder="Password" required>
        </div>
        <div class="smallText">
            <label for="rememberMe" class=" ">
              <input type="checkbox" name="remember_me" id="rememberMe" value="true" class="remember_me">
              Remember me
            </label>
            <a href="#" class="forgot-password pull-right">Forgot password?</a>
        </div>
        <div class="form-group">
            <button class="btn btn-primary btn-lg btn-learn btn-block">Log In</button>
        </div>
        <hr/>
        <div>
         Don't have an account? <a href="register.php">Sign Up</a>
        </div>
     </form>
       <script src="js/jquery-2.1.3.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script>
            function addLink() {
                var url = prompt("What is The Url?");
                var nam = prompt("What is the Name?");
                
                var menu = document.getElementById("menu");
                var link = document.createElement("a");        
                var t = document.createTextNode(nam);      
                link.appendChild(t);
                menu.appendChild(link); 
                link.setAttribute("href", url);
                link.setAttribute("target", "_blank");
            
            }
        </script>
    </body>
</html>