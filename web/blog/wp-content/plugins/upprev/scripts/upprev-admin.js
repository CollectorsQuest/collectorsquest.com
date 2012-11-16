jQuery(function(){iworks_upprev_tabulator_init();});
/**
 * Tabulator Bootup
 */
function iworks_upprev_tabulator_init()
{
    if (!jQuery("#hasadmintabs").length) {
        return;
    }
    jQuery('#hasadmintabs').prepend("<ul><\/ul>");
    jQuery('#hasadmintabs > fieldset').each(function(i){
        id      = jQuery(this).attr('id');
        caption = jQuery(this).find('h3').text();
        jQuery('#hasadmintabs > ul').append('<li><a href="#'+id+'"><span>'+caption+"<\/span><\/a><\/li>");
        jQuery(this).find('h3').hide();
    });
    index = 0;
    jQuery('#hasadmintabs h3').each(function(i){
        if ( jQuery(this).hasClass( 'selected' ) ) {
            index = i;
        }
    });
    if ( index < 0 ) index = 0;
    jQuery("#hasadmintabs").tabs({ selected: index });
    jQuery('#hasadmintabs ul a').click(function(i){
        jQuery('#hasadmintabs input[name=iworks_upprev_last_used_tab]').val(jQuery(this).parent().index());
    });
}
