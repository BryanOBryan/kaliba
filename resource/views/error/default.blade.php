<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <style>
            body{
                font-family: Segoe UI;
                color: gray; 
                background-color: #F9F9FA;
            }
            h1{
                font-size: 60px;
                color: #bd2130;
            }
            h2{
                font-size: 42px;
            }
            .container{
                width:auto;
                margin-top: 10%;
                margin-left: 20%;
                margin-right: 20%;
                height: auto;
                text-align: center;
            }           
        </style>
    </head>
    <body>
        <div class="container">
            <h2>{{$message}}</h2>
            <p><a href="javascript:window.history.back();">Go Back</a></p><br/>
        </div>
    </body> 
</html>