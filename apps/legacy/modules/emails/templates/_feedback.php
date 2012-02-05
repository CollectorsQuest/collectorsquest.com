<b>Page:</b> <?= $page; ?><br>
<b>Full Name:</b> <?= $fullname; ?><br>
<b>Email:</b> <?= $email; ?><br>
<br><hr>

<p>
  <br><?= $message; ?>
</p>

<br>
<ul>
  <li> IP Address: <?php echo $f_ip_address; ?> </li>
  <li> Javascript Enabled: <?php echo ($f_javascript_enabled == '1') ? 'true' : 'false'; ?> </li>
  <li> Browser Type: <?php echo $f_browser_type; ?> </li>
  <li> Browser Color Depth: <?php echo $f_browser_color_depth; ?> </li>
  <li> Resolution: <?php echo $f_resolution; ?> </li>
  <li> Browser Size: <?php echo $f_browser_size; ?> </li>
</ul>
