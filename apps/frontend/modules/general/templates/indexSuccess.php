<script>
  // The webfont loading needs to move to app.js
  // but I (Kiril) still need to figure it out
  WebFontConfig = {
    google: { families: [ 'Fugaz One' ] }
  };
  Modernizr.load({load: ('https:' == document.location.protocol ? 'https' : 'http') + '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js'});
</script>

<style type="text/css">
  .wf-loading h1 {
    font-family: serif;
    font-weight: 400;
  }
  .wf-inactive h1 {
    font-family: serif;
    font-weight: 400;
  }
  .wf-active h1 {
    font-family: 'Fugaz One', serif;
    font-weight: 400;
  }
</style>

<h1>This is using Fugaz One as font</h1>
<p>This is using the normal font from the browser!</p>
