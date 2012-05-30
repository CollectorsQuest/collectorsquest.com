<!-- Load Feather code -->
<script type="text/javascript" src="http://feather.aviary.com/js/feather.js"></script>

<!-- Instantiate Feather -->
<script type="text/javascript">
  var featherEditor = new Aviary.Feather({
    apiKey: '3058e289b',
    apiVersion: 2,
    minimumStyling: true,
    tools: 'crop,orientation,enhance,brightness,contrast,sharpness,saturation',
    onSave: function(imageID, newURL) {
      var img = document.getElementById(imageID);
      img.src = newURL;
    }
  });

  function imageEditor(id, src) {
    featherEditor.launch({
      image: id,
      url: src
    });
    return false;
  }
</script>
