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
        
       <div id="menuBar">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                <a class="formLink" href="register.php">Register</a>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <a class="formLink" href="login.php">Login</a>
                </div>
            </div>
       </div>
       
        <div id="menu">
            <a href="index.php"><strong><i>Bookymark</i></strong></a>
            <a href="http://www.w3schools.com" target="_blank" >W3schools</a>
            <a href="google.html" target="_blank" >facebook</a>
            <a href="http://www.google.com/" target="_blank" >W3schools</a>
            <a href="http://www.mintlaunch.com" target="_blank" >Mintlaunch</a>
            <button id="addLink"  class="pull-right" onclick="addLink()"><span class="glyphicon glyphicon-plus"></span></button>
            <button id="mainMenu" class="pull-right" ><span class="glyphicon glyphicon-option-horizontal"></span></button>
        </div>
           
        <iframe id="bmFrame" name="toFrame" src="http://www.w3schools.com">
            Your browser doesn't support IFrames
        </iframe>
       
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
        <script>
             $(document).ready(function(){
            $("#mainMenu").click(function(){
                $("#menuBar").slideToggle();
            });
        });
        </script>
    </body>
</html>