<script  src="http://code.jquery.com/jquery-latest.min.js"></script>

<!DOCTYPE html>
<html>
<head>
<meta charset="EUC-KR">
    <title>Parent</title>
    
    <script type="text/javascript">
    
        var openWin;
    
        function openChild()
        {
            // window.name = "부모창 이름"; 
            window.name = "parentForm";
            // window.open("open할 window", "자식창 이름", "팝업창 옵션");
            openWin = window.open("http://blog.daum.net/snow2dayharry/16228650",
                    "childForm", "width=570, height=350, resizable = no, scrollbars = no");    
        }
        
        function setChildText(){
            openWin.document.getElementById("cmmttext16228650").value = document.getElementById("pInput").value;
        }
 
   </script>
    
</head>
<body>
 
    <br>
    <b><font size="5" color="gray">부모창</font></b>
    <br><br>
    <input type="button" value="자식창 열기" onclick="openChild()"><br>
    전달할 값 : <input type="text" id="pInput"> <input type="button" value="전달" onclick="setChildText()">
</body>
</html>