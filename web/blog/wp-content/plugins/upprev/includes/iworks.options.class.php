<?php

if ( class_exists( 'IworksOptions' ) ) {
    return;
}

class IworksOptions
{
    private static $version;
    private $option_function_name;
    private $option_group;
    private $option_prefix;

    public function __construct()
    {
        $this->version              = '1.0.1';
        $this->option_group         = 'index';
        $this->option_function_name = null;
        $this->option_prefix        = null;
    }

    public function get_version()
    {
        return $this->version;
    }

    public function set_option_function_name( $option_function_name )
    {
        $this->option_function_name = $option_function_name;
    }

    public function set_option_prefix( $option_prefix )
    {
        $this->option_prefix = $option_prefix;
    }

    private function get_option_array()
    {
        if ( isset( $this->options[ $this->option_group ] ) ) {
            return $this->options[ $this->option_group ];
        }
        $options = call_user_func( $this->option_function_name );
        if ( isset( $options[ $this->option_group ] ) ) {
            $this->options[ $this->option_group ] = $options[ $this->option_group ];
            return $this->options[ $this->option_group ];
        }
        return array();
    }

    public function build_options( $option_group = 'index', $echo = true )
    {
        $this->option_group = $option_group;
        $options = $this->get_option_array( $option_group );
        /**
         * check options exists?
         */
        if(!is_array($options['options'])) {
            echo '<div class="below-h2 error"><p><strong>'.__('An error occurred while getting the configuration.', 'iworks').'</strong></p></div>';
            return;
        }
        $is_simple = 'simple' == get_option( 'iworks_upprev_configuration', 'advance' );
        $content   = '';
        $hidden    = '';
        $top       = '';
        $use_tabs  = isset( $options['use_tabs'] ) && $options['use_tabs'];
        /**
         * produce options
         */
        if ( $use_tabs ) {
            $top .= '<div id="hasadmintabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">';
        }
        $i           = 0;
        $label_index = 0;
        $last_tab    = null;
        $related_to  = array();
        $configuration = 'all';
        foreach ($options['options'] as $option) {
            if (isset($option['capability'])) {
                if(!current_user_can($option['capability'])) {
                    continue;
                }
            }
            $show_option = true;
            if ( isset( $option['check_supports'] ) && is_array( $option['check_supports'] ) && count( $option['check_supports'] ) ) {
                foreach ( $option['check_supports'] as $support_to_check ) {
                    if ( !current_theme_supports( $support_to_check ) ) {
                        $show_option = false;
                    }
                }
            }
            if ( !$show_option ) {
                continue;
            }
            /**
             * dismiss on special type
             */
            if ( $option['type'] == 'special' ) {
                continue;
            }
            if ( $option['type'] == 'heading' ) {
                if ( isset( $option['configuration'] ) ) {
                    $configuration = $option['configuration'];
                } else {
                    $configuration = 'all';
                }
            }
            if ( ( $is_simple && $configuration == 'advance' ) || ( !$is_simple && $configuration == 'simple' ) ) {
                continue;
            }
            if ( $option['type'] == 'heading' ) {
                if ( $use_tabs ) {
                    if ( $last_tab != $option['label'] ) {
                        $last_tab = $option['label'];
                        $content .= '</tbody></table>';
                        $content .= '</fieldset>';
                    }
                    $content .= sprintf(
                        '<fieldset id="upprev_%s" class="ui-tabs-panel ui-widget-content ui-corner-bottom">',
                        crc32( $option['label'] )
                    );
                    if ( !$use_tabs ) {
                        $content .= sprintf( '<h3>%s</h3>', $option['label'] );
                    }
                    $content .= sprintf(
                        '<table class="form-table%s" style="%s">',
                        isset($options['widefat'])? ' widefat':'',
                        isset($options['style'])? $options['style']:''
                    );
                    $content .= '<tbody>';
                }
                $content .= '<tr><td colspan="2">';
            } else if ( $option['type'] != 'hidden' ) {
                $style = '';
                if ( isset($option['related_to'] ) && isset( $related_to[ $option['related_to'] ] ) && $related_to[ $option['related_to'] ] == 0 ) {
                    $style .= 'style="display:none"';
                }
                $content .= sprintf( '<tr valign="top" class="%s" id="tr_%s"%s>', $i++%2? 'alternate':'', isset($option['name'])? $option['name']:'', $style );
                $content .= sprintf( '<th scope="row">%s</th>', isset($option['th']) && $option['th']? $option['th']:'&nbsp;' );
                $content .= '<td>';
            }
            $html_element_name = isset($option['name']) && $option['name']? $this->option_prefix.$option['name']:'';
            switch ( $option['type'] ) {
            case 'hidden':
                $hidden .= sprintf
                    (
                        '<input type="hidden" name="%s" value="%s" />',
                        $html_element_name,
                        isset($option['dynamic']) && $option['dynamic']? $this->get_option( $option['name'], $option_group ):$option['default']
                    );
                break;
            case 'text':
            case 'password':
                $id = '';
                if ( isset($option['use_name_as_id']) && $option['use_name_as_id']) {
                    $id = sprintf( ' id="%s"', $html_element_name );
                }
                $content .= sprintf
                    (
                        '<input type="%s" name="%s" value="%s" class="%s"%s /> %s',
                        $option['type'],
                        $html_element_name,
                        $this->get_option( $option['name'], $option_group ),
                        isset($option['class']) && $option['class']? $option['class']:'',
                        $id,
                        isset($option['label'])?  $option['label']:''
                    );
                break;
            case 'checkbox':
                $related_to[ $option['name'] ] = $this->get_option( $option['name'], $option_group );
                $content .= sprintf
                    (
                        '<label for="%s"><input type="checkbox" name="%s" id="%s" value="1"%s%s /> %s</label>',
                        $html_element_name,
                        $html_element_name,
                        $html_element_name,
                        $related_to[ $option['name'] ]? ' checked="checked"':'',
                        isset($option['disabled']) && $option['disabled']? ' disabled="disabled"':'',
                        isset($option['label'])?  $option['label']:''
                    );
                break;
            case 'checkbox_group':
                $option_value = $this->get_option($option['name'], $option_group );
                if ( empty( $option_value ) && isset( $option['defaults'] ) ) {
                    foreach( $option['defaults'] as $default ) {
                        $option_value[ $default ] = $default;
                    }
                }
                $content .= '<ul>';
                $i = 0;
                if ( isset( $option['extra_options'] ) && is_callable( $option['extra_options'] ) ) {
                    $option['options'] = array_merge( $option['options'], $option['extra_options']());
                }
                foreach ($option['options'] as $value => $label) {
                    $checked = false;
                    if ( is_array( $option_value ) && array_key_exists( $value, $option_value ) ) {
                        $checked = true;
                    }
                    $id = $option['name'].$i++;
                    $content .= sprintf
                        (
                            '<li><label for="%s"><input type="checkbox" name="%s[%s]" value="%s"%s id="%s"/> %s</label></li>',
                            $id,
                            $html_element_name,
                            $value,
                            $value,
                            $checked? ' checked="checked"':'',
                            $id,
                            $label
                        );
                }
                $content .= '</ul>';
                break;
            case 'radio':
                $option_value = $this->get_option($option['name'], $option_group );
                $content .= '<ul>';
                $i = 0;
                if ( isset( $option['extra_options'] ) && is_callable( $option['extra_options'] ) ) {
                    $option['radio'] = array_merge( $option['radio'], $option['extra_options']());
                }
                foreach ($option['radio'] as $value => $label) {
                    $id = $option['name'].$i++;
                    $content .= sprintf
                        (
                            '<li><label for="%s"><input type="radio" name="%s" value="%s"%s id="%s" %s/> %s</label></li>',
                            $id,
                            $html_element_name,
                            $value,
                            ($option_value == $value or ( empty($option_value) and isset($option['default']) and $value == $option['default'] ) )? ' checked="checked"':'',
                            $id,
                            preg_match( '/\-disabled$/', $value )? 'disabled="disabled"':'',
                            $label
                        );
                }
                $content .= '</ul>';
                break;
            case 'textarea':
                $value = $this->get_option($option['name'], $option_group);
                $content .= sprintf
                    (
                        '<textarea name="%s" class="%s" rows="%d">%s</textarea>',
                        $html_element_name,
                        $option['class'],
                        isset($option['rows'])? $option['rows']:3,
                        (!$value && isset($option['default']))? $option['default']:$value
                    );
                break;
            case 'heading':
                if ( isset( $option['label'] ) && $option['label'] ) {
                    $content .= sprintf(
                        '<h3 id="options-%s"%s>%s</h3>',
                        sanitize_title_with_dashes(remove_accents($option['label'])),
                        get_option( $this->option_prefix.'last_used_tab', 0 ) == $label_index? ' class="selected"':'',
                        $option['label']
                    );
                    $label_index++;
                    $i = 0;
                }
                break;
            case 'info':
                $content .= $option['value'];
                break;
            default:
                $content .= sprintf('not implemented type: %s', $option['type']);
            }
            if ( $option['type'] != 'hidden' ) {
                if ( isset ( $option['description'] ) && $option['description'] ) {
                    if ( isset ( $option['label'] ) && $option['label'] ) {
                        $content .= '<br />';
                    }
                    $content .= sprintf('<span class="description">%s</span>', $option['description']);
                }
                $content .= '</td>';
                $content .= '</tr>';
            }
        }
        if ($content) {
            if ( isset ( $options['label'] ) && $options['label'] && !$use_tabs ) {
                $top .= sprintf('<h3>%s</h3>', $options['label']);
            }
            $top .= $hidden;
            if ( $use_tabs ) {
                $content .= '</tbody></table>';
                $content .= '</fieldset>';
                $content = $top.$content;
            } else {
                $top .= sprintf( '<table class="form-table%s" style="%s">', isset($options['widefat'])? ' widefat':'', isset($options['style'])? $options['style']:'' );
                if ( isset( $options['thead'] ) ) {
                    $top .= '<thead><tr>';
                    foreach( $options['thead'] as $text => $colspan ) {
                        $top .= sprintf
                            (
                                '<th%s>%s</th>',
                                $colspan > 1? ' colspan="'.$colspan.'"':'',
                                $text
                            );
                    }
                    $top .= '</tr></thead>';
                }
                $top .= '<tbody>';
                $content = $top.$content;
                $content .= '</tbody></table>';
            }
        }
        if ( $use_tabs ) {
            $content .= '</div>';
        }
        $content .= sprintf(
            '<p class="submit"><input type="submit" class="button-primary" value="%s" /></p>',
            __( 'Save Changes' )
        );
        /* print ? */
        if ( $echo ) {
            echo $content;
            return;
        }
        return $content;
    }

    public function options_init()
    {
        $options = call_user_func( $this->option_function_name );
        foreach( $options as $key => $data ) {
            if ( isset ( $data['options'] ) && is_array( $data['options'] ) ) {
                $option_group = $this->option_prefix.$key;
                foreach ( $data['options'] as $option ) {
                    if ( $option['type'] == 'heading' || !isset($option['name']) ) {
                        continue;
                    }
                    register_setting (
                        $option_group,
                        $this->option_prefix.$option['name'],
                        isset($option['sanitize_callback'])? $option['sanitize_callback']:null
                    );
                }
            }
        }
    }

    public function get_option( $option_name, $option_group = 'index' )
    {
        $option_value = get_option( $this->option_prefix.$option_name, null );
        if ( $option_value === null ) {
            $option_value = $this->get_default_value( $option_name, $option_group );
        }
        return $option_value;
    }

    public function get_default_value( $option_name, $option_group = 'index' )
    {
        $this->option_group = $option_group;
        $options = $this->get_option_array( $option_group );
        /**
         * check options exists?
         */
        if(!is_array($options['options'])) {
            return null;
        }
        foreach ( $options['options'] as $option ) {
            if ( isset( $option['name'] ) && $option['name'] == $option_name ) {
                return isset($option['default'])? $option['default']:null;
            }
        }
        return null;
    }

    public function activate()
    {
        $options = call_user_func( $this->option_function_name );
        foreach( $options as $key => $data ) {
            foreach ( $data['options'] as $option ) {
                if ( $option['type'] == 'heading' or !isset( $option['name'] ) or !$option['name'] or !isset( $option['default'] ) ) {
                    continue;
                }
                add_option( $this->option_prefix.$option['name'], $option['default'], '', isset($option['autoload'])? $option['autoload']:'yes' );
            }
        }
        add_option( $this->option_prefix.'cache_stamp', date('c') );
    }

    public function deactivate()
    {
        $options = call_user_func( $this->option_function_name );
        foreach( $options as $key => $data ) {
            foreach ( $data['options'] as $option ) {
                if ( $option['type'] == 'heading' or !isset( $option['name'] ) or !$option['name'] ) {
                    continue;
                }
                delete_option( $this->option_prefix.$option['name'] );
            }
        }
        delete_option( $this->option_prefix.'cache_stamp' );
    }

    public function settings_fields( $option_name )
    {
        settings_fields( $this->option_prefix . $option_name );
    }

    public function update_option( $option_name, $option_value )
    {
        update_option( $this->option_prefix.$option_name, $option_value );
    }
}

