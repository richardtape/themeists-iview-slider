<?php

class WidgetImageField
{
    private $image_id;
    private $src;
    private $width;
    private $height;
    private $widget_field;
    private $widget = null;

    function __construct( $widget = null, $image_id = 0 )
    {
        $uri        = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : NULL ;
        $file       = basename( parse_url( $uri, PHP_URL_PATH ) );

        // set our properties
        $this->widget = $widget;
        if( $image_id )
        {
            $this->image_id         = intval( $image_id );
            $this->src              = $this->get_image_src( $this->image_id );
            $this->width            = $this->get_image_width( $this->image_id );
            $this->height           = $this->get_image_height( $this->image_id );
            $this->widget_field     = $this->get_widget_field( $widget, $this->image_id );
        }
    }

    function get_image( $size = 'thumbnail' )
    {
        $image = false;

        if( $this->image_id )
        {
            $image = wp_get_attachment_image_src( $this->image_id, $size );
        }

        return $image;
    }

    function get_image_src( $size = 'thumbnail' )
    {
        $src = false;

        if( $this->image_id )
        {
            $image      = $this->get_image( $size );
            $src        = $image[0];
        }

        return $src;
    }

    function get_image_dimensions( $size = 'thumbnail' )
    {
        $dimensions = array( null, null );

        if( $this->image_id )
        {
            $image          = $this->get_image( $size );
            $dimensions     = array( $image[1], $image[2] );
        }

        return $dimensions;
    }

    function get_image_width( $size = 'thumbnail' )
    {
        $width = false;

        if( $this->image_id )
        {
            $dimensions     = $this->get_image_dimensions( $size );
            $width          = $dimensions[0];
        }

        return $width;
    }

    function get_image_height( $size = 'thumbnail' )
    {
        $height = false;

        if( $this->image_id )
        {
            $dimensions     = $this->get_image_dimensions( $size );
            $height         = $dimensions[1];
        }

        return $height;
    }

    function get_widget_field( $field_name = null, $image_id = null, $div_id = null )
    {
        $field = false;
        if( $this->widget && ( isset( $this->widget->image_field ) || $field_name ) )
        {

            if( $div_id && $div_id != '' )
            {

                $field_name = "widget-themeists_iview_slider_widget[" . $div_id . "][image]";
                $field_id = "widget-themeists_iview_slider_widget-" . $div_id . "-image";

            }
            else
            {

                $field_name = $this->widget->get_field_name( $this->widget->image_field );
                $field_id = $this->widget->get_field_id( $this->widget->image_field );

            }

            $field  = "
                <div class='iti-image-widget-field'>
                    <div class='iti-image-widget-image' id='" . $this->widget->id . "'>";
                        $field .= "<input class='hiddenimageid' type='hidden' style='display:none;' id='" . $field_id . "' name='" . $field_name . "' value='" . $this->image_id . "' />";

            if( $this->image_id )
            {
                $field .= "<img src='" . $this->src . "' width='" . $this->width . "' height='" . $this->height . "' />";
            }

            $field .= "</div>";

            $field .= "<a class='button iti-image-widget-trigger' href='media-upload.php?TB_iframe=1&amp;width=640&amp;height=1500' title='" . __( 'Choose Image' ) . "'>";
            $field .= __( 'Choose Image' );
            $field .= "</a></div>";
        }
        return $field;
    }
}


?>