<html>
<head>
	<title id="gsTitle">GC</title>
	<style>
       
body {
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    background: #cfe0e4;
    padding: 0;
    margin: 0;
}
img {
    padding: 0;
    margin: 0;
}
body {
    background: #cfe0e4;
    margin: 0;
    display: table;
    position: absolute;
    height: 100%;
    width: 100%;
    overflow-x:hidden;
    overflow-y:hidden;
}
a {
    color: #3889AB;
    text-decoration: none;
    font-weight: 400;
}
.btnDonate {
  font-family: 'Open Sans', sans-serif;
  margin: 0 5px;
  float: left;
}
.btn {
  background: #52a6c8;
  color: #fff;
  border-radius: 3px;
  height: 40px;
  line-height: 40px;
  padding: 0 20px;
  display: inline-block;
  border: 0;
  font-size: 14px;
  cursor: pointer;
}
#gsTopBar  {
   	position: fixed;
    background: #efefef;
    top: 0;
    padding: 15px 0;
    line-height: 30px;
    width: 100%;
    max-width: 100%;
    text-align: center;
    color: #444;
    border-bottom: 1px solid #AAA;
    box-shadow: 0 0 4px -2px rgba(0, 0, 0, 0.5);
}
#gsTitleWrap {
    width: 100%;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
}
#gsTopBarTitle {
    font-size: 19px;
    line-height: 26px;
    color: rgb(68, 68, 68);
    font-weight: 300;
    cursor: pointer;
}
#gsTopBarTitle:hover {
    text-decoration: underline;
}
#gsWhitelistLink {
    text-decoration: underline;
    cursor: pointer;
}
#gsPreview {
    width: 100%;
    height: 100%;
    background: #fff;
}
#gsPreviewImg {
    width: 100%;
    margin-top:86px;
}
.centerBoxContainer {
    text-align: center;
    display: table-cell;
    vertical-align: middle;
}
.centerBox {
    margin-left: auto;
    margin-right: auto;
    margin-top: 40px;
}
#suspendedMsg {
    width: 100%;
    height: 100%;
}
#suspendedMsg, #gsPreview {
    cursor: pointer;
}
#suspendedMsg h1, #suspendedMsg h2 {
    font-size: 60px;
    color: #134960;
    margin: 30px 0 0;
    font-weight: 600;
}
#suspendedMsg h2 {
    font-size: 50px;
}
@media all and (max-width: 600px) {
    #suspendedMsg h1 {
        font-size: 45px;
    }
    #suspendedMsg h2 {
        font-size: 55px;
    }
}
#dudePopup {
    position: fixed;
    left: 30px;
    bottom: -240px;
}

@-webkit-keyframes silde_to_top {
    0% {
        bottom: -240px;
    }
    100% {
        bottom: 10px;
        z-index: 1000000;
    }
}

@keyframes fadein {
    from { opacity: 0; }
    to   { opacity: 1; }
}

@-webkit-keyframes fadein {
    from { opacity: 0; }
    to   { opacity: 1; }
}

    </style>
</head>
<body onclick="reload()">

<div id="gsTopBar">
	<div id="gsTitleWrap">
		<img id='gsTopBarImg' /> <a id='gsTopBarTitle'></a>
	</div>
	<a id='gsWhitelistLink'></a>
</div>
<div id="suspendedMsg" class="centerBoxContainer">
    <div class="centerBox">
		<h1>Tela desativada</h1>
		<h2>Click para recarregar</h2>
    </div>
</div>
    
    <script>
    function reload() {
        window.location.href = "{{$url}}";
    }
    </script>

</body>