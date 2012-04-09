<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <title>Countdown until the new CollectorsQuest.com</title>
  <link rel="stylesheet" type="text/css" media="screen" href="/css/frontend/bootstrap.css" />
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript" charset="utf-8"></script>
  <script src="/js/jquery/countdown.js" type="text/javascript" charset="utf-8"></script>
  <script type="text/javascript">
    $(function(){
      $('#counter').countdown({
        image: '/images/icons/digits.png',
        startTime: '<?php echo sprintf('%d:%d:00:00', $time_left->days, $time_left->h); ?>'
      });
    });
  </script>
  <style type="text/css">
    br { clear: both; }
    .cntSeparator {
      font-size: 54px;
      margin: 10px 7px;
      padding-top: 15px;
      color: #000;
    }
    .desc { margin: 7px 3px; }
    .desc div {
      float: left;
      font-family: Arial, sans-serif;
      width: 70px;
      margin-right: 65px;
      font-size: 13px;
      font-weight: bold;
      color: #000;
    }
  </style>
</head>
<body>
<div style="width: 550px; margin: 200px auto;">
  <h1 style="text-align: center;">The New CollectorsQuest.com!&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h1>
  <br/>
  <div id="counter"></div>
  <div class="desc">
    <div>Days</div>
    <div>Hours</div>
    <div>Minutes</div>
    <div>Seconds</div>
  </div>
</div>
</body>
</html>
