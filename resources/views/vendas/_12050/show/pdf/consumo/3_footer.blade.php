<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script>
            function subst() {
              var vars={};
              var x=document.location.search.substring(1).split('&');
              for (var i in x) {var z=x[i].split('=',2);vars[z[0]] = unescape(z[1]);}
              var x=['frompage','topage','page','webpage','section','subsection','subsubsection','date','time'];
              for (var i in x) {
                var y = document.getElementsByClassName(x[i]);
                for (var j=0; j<y.length; ++j) y[j].textContent = vars[x[i]];
              }
            }
        </script>
        <style>
            hr {
                color: black;
                padding-bottom: 5px;
            }
        </style>        
    </head>
    <body onload="subst()" style="border-top: 1px solid black; width: 100%; padding: 10px;">
    </body>
    <html>