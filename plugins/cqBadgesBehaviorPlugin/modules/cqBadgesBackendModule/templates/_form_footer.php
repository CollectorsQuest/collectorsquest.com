<script>
    $(document).ready(function(){
       $('#cq_badge_tier').change(function(){
           if ($(this).val() == 'custom')
           {
               $('.sf_admin_form_field_parent_model, .sf_admin_form_field_parent_model_id').show();
           }
           else
           {
               $('.sf_admin_form_field_parent_model, .sf_admin_form_field_parent_model_id').hide();
           }
       }).change();
    });
</script>