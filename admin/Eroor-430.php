<!DOCTYPE html>
<html>
<head>
  <title>DSME - 403 Forbidden</title>
  <link rel="stylesheet" href="css/fonts.css">
  <link rel="stylesheet" href="css/style.css">
  <link href="css/fontawesome.min.css" rel="stylesheet">
  <link href="css/brands.min.css" rel="stylesheet">
  <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
  <link rel="manifest" href="site.webmanifest">
  <link rel="mask-icon" href="safari-pinned-tab.svg" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">
</head>
<body>
    <style>
        body {
    font-family: 'Libre Franklin', serif;
    font-size: 52px;
}

a {
    text-decoration: none;
}

img {
    vertical-align: top;
    display: inline-block;
    text-align: center;
    position: relative;
    top: 50%;
    transform: translateY(-50%);
}

#centered-horizontal {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

#centered-vertical {
    width: 100%;
    position: relative;
}

#error-code {
    text-transform: uppercase;
    display: inline-block;
    vertical-align: middle;
}

#error-number {
    font-size: 96px;
    font-weight: bolder;
}

#sticker {
    display: inline-block;
    vertical-align: middle;
    width: 259px;
    height: 224px;
    margin: 0 0 0 60px;
}

#error-description {
    margin-top: 1em;
    text-align: left;
    font-size: 28px;
}

#error-number {
    font-size: 96px;
    text-transform: uppercase;
}

#error-name {
    font-weight: bold;
    font-size: 72px;
    text-decoration: underline;
    text-transform: uppercase;
}

#banner {
    font-size: 24px;
    position: absolute; 
    top: 35px;
    left: 50%
}

#footer {
    width: 522px;;
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translate(-50%, -50%);
}

#copyright {
    font-size: 16px;
    text-align: right;
    position: absolute; 
    bottom: 15px;
}

.unselectable {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
    </style>
  <script type="text/javascript" colseed="978" size="250" alpha='0.5' zIndex="-2" src="js/ribbon.min.js"></script>
  <div class="unselectable" id="centered-horizontal">
    <div id="centered-vertical">
      <div id="error-code">
          <span>الكود</span>&nbsp;<span id="error-number">403</span>
          <div id="error-name">غير مصرح</div>
      </div>
      
      <div id="error-description">
        The server understood the request, but is refusing to fulfill it.
      </div>
    </div>
  </div>
  <div class="unselectable" id="footer">
    <div id="copyright">Menhera Error Pages by <a href="https://taskbjorn.com">taskbjorn</a>. Source code available on <i class="fab fa-github-square"></i> <a href="https://github.com/taskbjorn/error-pages">GitHub</a>.</div>
  </div>
</body>
</html>