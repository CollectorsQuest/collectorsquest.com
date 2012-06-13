<?php
/**
 * @var $src string
 * @var $href string
 * @var $width integer
 * @var $height integer
 */
?>
<script>
  if (typeof window.dfp_ord == 'undefined') { window.dfp_ord = Math.random() * 10000000000000000; }
  if (typeof(window.dfp_tile) == 'undefined') { window.dfp_tile = 1; }

  var src = '<?= $src; ?>';
  if (window.dfp_tile === 1) {
    src = src.replace(/pos=\w+/i, 'pos=top');
    src = src.replace(/dcopt=;/i, 'dcopt=ist;');
  }
  src = src +';tile='+ (window.dfp_tile++) +';ord='+ window.dfp_ord +'?';

  document.write('<script language="JavaScript" src="'+ src +'" type="text/javascript"><\/script>');
</script>
<noscript>
  <a href="<?= $href; ?>;tile=1;ord=123456789?" target="_blank">
    <img src="<?= $src ?>;tile=1;ord=123456789?" width="<?= $width ?>" height="<?= $height ?>" border="0" alt="">
  </a>
</noscript>
