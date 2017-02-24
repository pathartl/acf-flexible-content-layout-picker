<?php

    class acf_field_flexible_content_layout_picker extends acf_field {

        /*
        *  __construct
        *
        *  This function will setup the field type data
        *
        *  @type  function
        *  @date  5/03/2014
        *  @since 5.0.0
        *
        *  @param n/a
        *  @return  n/a
        */

        function __construct() {
            // vars
            $this->name = 'flexible_content_layout_picker_field';
            $this->label = __( 'Flexible Content Layout' );
            $this->category = __( 'Relational', 'acf' ); // Basic, Content, Choice, etc
            $this->defaults = array(
                'allow_multiple' => 0,
                'allow_null'     => 0,
            );
            // do not delete!
            parent::__construct();
        }

        /*
        *  render_field_settings()
        *
        *  Create extra settings for your field. These are visible when editing a field
        *
        *  @type  action
        *  @since 3.6
        *  @date  23/01/13
        *
        *  @param $field (array) the $field being edited
        *  @return  n/a
        */

        function render_field_settings( $field ) {

            /*
            *  acf_render_field_setting
            *
            *  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
            *  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
            *
            *  More than one setting can be added by copy/paste the above code.
            *  Please note that you must also have a matching $defaults value for the field name (font_size)
            */

            acf_render_field_setting( $field, array(
                'label'   => 'Allow Null?',
                'type'    => 'radio',
                'name'    => 'allow_null',
                'choices' => array(
                    1 => __( "Yes", 'acf' ),
                    0 => __( "No", 'acf' ),
                ),
                'layout'  => 'horizontal',
            ) );
        }

        /*
        *  render_field()
        *
        *  Create the HTML interface for your field
        *
        *  @param $field (array) the $field being rendered
        *
        *  @type  action
        *  @since 3.6
        *  @date  23/01/13
        *
        *  @param $field (array) the $field being edited
        *  @return  n/a
        */

        function render_field( $field ) {

            /*
            *  Review the data of $field.
            *  This will show what data is available
            */

            // vars
            $field = array_merge( $this->defaults, $field );

            // override field settings and render
            $field['type'] = 'select';
            ?>
            <select data-selected-value="<?php echo $field['value']; ?>"
                    id="<?php echo str_replace( array( '[', ']' ), array( '-', '' ), $field['name'] ); ?>"
                    name="<?php echo $field['name']; ?>">
                <?php if ( $field['allow_null'] ) : ?>
                    <option value="">- Select -</option>
                <?php endif; ?>
            </select>

            <script>
                (function ($) {
                    acf.add_action('ready', appendCurrentLayoutsAsOptions);
                    acf.add_action('append', appendCurrentLayoutsAsOptions);

                    acf.add_action('remove', function (el) {
                        appendCurrentLayoutsAsOptions();

                        var currentField = getCurrentField();

                        if (currentField) {
                            var removedLayout = $(el).find('.acf-fc-layout-handle').text();

                            currentField.find('option').each(function(i, option) {
                                if ($(option).text() == removedLayout) {
                                    $(option).remove();
                                }
                            });
                        }

                        // Timeout hack needed until a hook for after remove is added
                        setTimeout(appendCurrentLayoutsAsOptions, 500);
                    });

                    acf.add_action('sortstop', appendCurrentLayoutsAsOptions);

                    function getCurrentField() {
                        var fields = acf.get_fields();

                        var currentFieldPossibilities = $(fields).find('[data-key="<?php echo $field['key']; ?>"]');

                        var currentField = false;

                        currentFieldPossibilities.each(function (i, field) {
                            if ($(field).parents('.values').length > 0) {
                                currentField = $(field);
                            }
                        });

                        return currentField;
                    }

                    function appendCurrentLayoutsAsOptions() {
                        var currentField = getCurrentField();

                        if (currentField) {
                            var flexibleContentParent = currentField.parents('[data-type="flexible_content"]').first();

                            var layouts = flexibleContentParent.find('.values [data-layout]');
                            var select = currentField.find('select');

                            select.html('');

                            layouts.each(function (i, layout) {
                                addOption($(layout).children('.acf-fc-layout-handle').text(), $(layout).attr('data-id'), select);
                            });
                        }
                    }

                    function addOption(label, value, select) {
                        if (label != '' && value != '') {
                            var option = $('<option />', {
                                text: label,
                                'value': value
                            });

                            if (select.attr('data-selected-value') == value) {
                                option.prop('selected', true);
                            }

                            select.append(option);
                        }
                    }
                })(jQuery)
            </script>
            <?php
        }

        /*
        *  format_value()
        *
        *  This filter is applied to the $value after it is loaded from the db and before it is returned to the template
        *
        *  @type  filter
        *  @since 3.6
        *  @date  23/01/13
        *
        *  @param $value (mixed) the value which was loaded from the database
        *  @param $post_id (mixed) the $post_id from which the value was loaded
        *  @param $field (array) the field array holding all the field options
        *
        *  @return  $value (mixed) the modified value
        */

        function format_value( $value, $post_id, $field ) {

            //Return false if value is false, null or empty
            if ( !$value || empty( $value ) ) {
                return false;
            }

        }

    }

    // create field
    new acf_field_flexible_content_layout_picker();