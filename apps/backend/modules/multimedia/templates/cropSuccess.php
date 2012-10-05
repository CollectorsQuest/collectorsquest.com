<?php
  /* @var $multimedia iceModelMultimedia */

  /* @var cqApplicationConfiguration $configuration */
  $configuration = sfProjectConfiguration::getActive();
  $configuration->loadHelpers(array('cqImages'));
?>

<input type="submit" value="  Crop  " onclick="javascript:update()" /> &nbsp;&nbsp;&nbsp;
<input type="submit" value="  Back  " onclick="javascript:history.go(-1)" /> <br/>

<div class="clear"></div>

<h1>Original</h1>
<?php echo image_tag_multimedia($multimedia, 'original', array('id'=>'current_photo', 'style'=> 'float: left')); ?>

<h3>&nbsp;&nbsp;&nbsp;Thumbnail</h3>
<div id="preview" style="float: left; margin-top: 10px; margin-left: 10px;">
  <?php echo image_tag_multimedia($multimedia, 'thumbnail'); ?>
</div>

<div class="clear"></div>

<!-- we can display this is we decide to not have aspectRatio: '1:1' as a constant -->
<table style="margin-top: 1em; display: none">
  <thead>
  <tr>
    <th colspan="2" style="font-size: 110%; font-weight: bold; text-align: left; padding-left: 0.1em;">

      Coordinates
    </th>
    <th colspan="2" style="font-size: 110%; font-weight: bold; text-align: left; padding-left: 0.1em;">
      Dimensions
    </th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td style="width: 10%;"><b>X<sub>1</sub>:</b></td>

    <td style="width: 30%;"><input type="text" id="x1" value="-" /></td>
    <td style="width: 20%;"><b>Width:</b></td>
    <td><input type="text" value="-" id="w" /></td>
  </tr>
  <tr>
    <td><b>Y<sub>1</sub>:</b></td>
    <td><input type="text" id="y1" value="-" /></td>

    <td><b>Height:</b></td>
    <td><input type="text" id="h" value="-" /></td>
  </tr>
  <tr>
    <td><b>X<sub>2</sub>:</b></td>
    <td><input type="text" id="x2" value="-" /></td>
    <td></td>

    <td></td>
  </tr>
  <tr>
    <td><b>Y<sub>2</sub>:</b></td>
    <td><input type="text" id="y2" value="-" /></td>
    <td></td>
    <td></td>

  </tr>
  </tbody>
</table>


<script type="text/javascript">
  $(document).ready(function () {
    /*
     * removing aspectRatio: '1:1' will make cropping area
     * @see http://odyniec.net/projects/imgareaselect/examples.html
     */
    $('img#current_photo').imgAreaSelect({ handles: true, aspectRatio: '1:1', onSelectChange: preview });
  });

  function reloadImage()
  {
    d = new Date();

    $("#preview img").attr("src", $("#preview img").attr("src") + "?" + d.getTime())
  }

  function preview(img, selection) {
    $("#x1").val(selection.x1);
    $("#y1").val(selection.y1);
    $("#x2").val(selection.x2);
    $("#y2").val(selection.y2);
    $("#w").val(selection.width);
    $("#h").val(selection.height);
  }

  function update(img, selection)
  {
    var x1 = $("#x1").val();
    var y1 = $("#y1").val();
    var x2 = $("#x2").val();
    var y2 = $("#y2").val();
    var w = $("#w").val();
    var h = $("#h").val();

    <?php // @todo loading css not loaded I guess - it is not showing properly ?>
    $('.row-fluid').showLoading();

    $('div#preview')
      .load(
        '<?php echo url_for ('multimedia/cropUpdate?id='. $multimedia->getId()); ?>'
        + '?x1=' + x1 + '&y1=' + y1 + '&width=' + w + '&height=' + h,
        function () { reloadImage(); $('.row-fluid').hideLoading(); }
      )
  }
</script>



