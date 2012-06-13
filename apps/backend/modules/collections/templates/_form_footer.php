<script>
  $(document).ready(function()
  {
    $('#collector_collection_description').wysihtml5({
      "font-styles": false, "image": false, "link": false
    });

    $('.sf_admin_form .tag').tagedit({
      autocompleteURL: '<?= url_for('@ajax_typeahead?section=tags&page=edit', true); ?>',
      // return, comma, semicolon
      breakKeyCodes: [ 13, 44, 59 ]
    });
  });
</script>
