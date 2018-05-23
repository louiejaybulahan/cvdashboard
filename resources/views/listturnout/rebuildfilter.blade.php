<html>
    <head>
        <title>Uploading baseline data</title>
        <style type="text/css">
            body{ font-family: monospace,tahoma; font-size: 11px; }
        </style>
        <script type="text/javascript">        
            var speed = 9999;
            var timer = null;
            var currentpos = 0, alt = 1, curpos1 = 1, curpos2 = -1;
            function initialize() { startit(); }
            function scrollwindow() {
                if (document.all && !document.getElementById) temp = document.body.scrollTop;
                else temp = window.pageYOffset;
                if (alt == 0) alt = 2;
                else alt = 1;
                if (alt == 0) curpos1 = temp;
                else curpos2 = temp;
                if (curpos1 != curpos2) {
                    if (document.all) currentpos = document.body.scrollTop + speed;
                    else currentpos = window.pageYOffset + speed;
                    window.scroll(0, currentpos);
                }
                else {
                    currentpos = 0;
                    window.scroll(0, currentpos);
                }
            }
            function startit() { timer = setInterval("scrollwindow()", 100); }
            function stopInterval() { clearInterval(timer); }
            window.onload = initialize();
        </script>
    </head>
<body>
    <strong>Successfully completed performing request</strong><br>         
    <script type="text/javascript">setTimeout(function(){parent.jsQueryProcessDone(); },5000); </script>    
 </body>
</html>
        