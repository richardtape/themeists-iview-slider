var iti_widget_image_context = false;

function iti_widget_image_return(iti_widget_image_id,iti_widget_image_thumb){
    // show our image for reference
    iti_widget_image_context.find('img').remove();
    iti_widget_image_context.append('<img src="' + iti_widget_image_thumb + '" alt="Image" />');

    // save our image id
    iti_widget_image_context.find('input').val(iti_widget_image_id);
}

function iti_widget_image_update_thickbox(){
    if(iti_widget_image_context){

        // need to add our own button
        if(jQuery('#TB_iframeContent').contents().find('td.savesend').length){
            jQuery('#TB_iframeContent').contents().find('td.savesend').each(function(){
                if(jQuery(this).find('input.iti-widget-image-choose').length==0){
                    jQuery(this).find('input').hide();
                    jQuery(this).prepend('<input type="submit" name="itiwidgetimagechoose" class="iti-widget-image-choose button" value="Use this image" />');
                }
            });
        }

        // need to handle the click event
        jQuery('#TB_iframeContent').contents().find('td.savesend input.iti-widget-image-choose').unbind('click').click(function(e){
            e.preventDefault();
            iti_widget_image_parent = jQuery(this).parent().parent().parent();
            iti_widget_image_id = iti_widget_image_parent.find('td.imgedit-response').attr('id').replace('imgedit-response-','');
            iti_widget_image_thumb = iti_widget_image_parent.parent().parent().find('img.pinkynail').attr('src');
            iti_widget_image_ref = iti_widget_image_parent.clone();

            iti_widget_image_return(iti_widget_image_id,iti_widget_image_thumb);

            // close everything and wrap up
            iti_widget_image_context = false;
            tb_remove();
        });

        // update button
        if(jQuery('#TB_iframeContent').contents().find('.media-item .savesend input[type=submit], #insertonlybutton').length){
            jQuery('#TB_iframeContent').contents().find('.media-item .savesend input[type=submit], #insertonlybutton').val('Use this image');
        }
        if(jQuery('#TB_iframeContent').contents().find('#tab-type_url').length){
            jQuery('#TB_iframeContent').contents().find('#tab-type_url').hide();
        }
        if(jQuery('#TB_iframeContent').contents().find('tr.post_title').length){
            // we need to ALWAYS get the fullsize since we're retrieving the guid
            // if the user inserts an image somewhere else and chooses another size, everything breaks
            jQuery('#TB_iframeContent').contents().find('tr.image-size input[value="full"]').prop('checked', true);
            jQuery('#TB_iframeContent').contents().find('tr.post_title,tr.image_alt,tr.post_excerpt,tr.image-size,tr.post_content,tr.url,tr.align,tr.submit>td>a.del-link').hide();
        }
    }

    if(jQuery('#TB_iframeContent').contents().length==0&&iti_widget_image_context){
        // the thickbox was closed
        clearInterval(iti_widget_image_thickbox_updater);
        iti_widget_image_context = false;
    }
}



jQuery(document).ready(function(){

    //Move the 'clone' button next to the Widget Save button
    jQuery( '.clonebutton' ).each( function(){

        var thisbutton = jQuery(this);
        var thisnewplace = thisbutton.parent().parent().children( '.widget-control-actions' );
        var thisnewplaceright = thisnewplace.children( '.alignright' );

        thisnewplaceright.prepend( thisbutton );

        thisbutton.css( 'margin-right', '10px' ).css( 'float', 'left' );

    } );

    //When the clone button is clicked, we need to clone the form
    jQuery( '.clonebutton' ).live( 'click', function(){

        //First ensure we're cloning the correct .widget-content
        var thiscontent = jQuery(this).parent().parent().parent().children('.widget-content');

        var repeatingGroup = thiscontent.children( '.cloneable' );
        var lastRepeatingGroup = repeatingGroup.last();
        
        //Each field is something like name="widget-themeists_iview_slider_widget[2][title]"
        //So we'll need a new number
        var newIdNumber = Math.floor( Math.random() * 1001 );

        //Clone the last available cloneable div
        var newSection = lastRepeatingGroup.clone();

        //Empty the classes then add the correct one
        jQuery( newSection ).removeClass().addClass( 'cloneable' ).addClass( 'widget-themeists_iview_slider_widget-'+newIdNumber+'-id' );

        //Add the clone after the last
        newSection.css('display', 'none').insertAfter( lastRepeatingGroup ).fadeIn();

        //Now add a 'remove' button
        newSection.append( '<input type="button" class="remove_clone button button-secondary" value="x" />' );


        //Ensure the id and name attributes are unique
        newSection.find( "input.titlefield" ).each( function( index, input ){

            new_id = "widget-themeists_iview_slider_widget-" + newIdNumber + "-title";
            new_name = "widget-themeists_iview_slider_widget[" + newIdNumber + "][title]";

            input.id = new_id;
            input.name = new_name;
            input.value = "";

        });

        //Do the same for the second input
        newSection.find( "input.subtitlefield" ).each( function( index, input ){

            new_id = "widget-themeists_iview_slider_widget-" + newIdNumber + "-subtitle";
            new_name = "widget-themeists_iview_slider_widget[" + newIdNumber + "][subtitle]";

            input.id = new_id;
            input.name = new_name;
            input.value = "";

        });

        //Do the same for the button_text input
        newSection.find( "input.button_textfield" ).each( function( index, input ){

            new_id = "widget-themeists_iview_slider_widget-" + newIdNumber + "-button_text";
            new_name = "widget-themeists_iview_slider_widget[" + newIdNumber + "][button_text]";

            input.id = new_id;
            input.name = new_name;
            input.value = "";

        });

        //Do the same for the button_link field
        newSection.find( "input.button_linkfield" ).each( function( index, input ){

            new_id = "widget-themeists_iview_slider_widget-" + newIdNumber + "-button_link";
            new_name = "widget-themeists_iview_slider_widget[" + newIdNumber + "][button_link]";

            input.id = new_id;
            input.name = new_name;
            input.value = "";

        });

        //Do the same for the menu_title field
        newSection.find( "input.menu_titlefield" ).each( function( index, input ){

            new_id = "widget-themeists_iview_slider_widget-" + newIdNumber + "-menu_title";
            new_name = "widget-themeists_iview_slider_widget[" + newIdNumber + "][menu_title]";

            input.id = new_id;
            input.name = new_name;
            input.value = "";

        });

        //Also do the labels
        newSection.find( "label" ).each( function( index, label ){

            var l = jQuery(label);
            
            new_for = l.next().attr('name');
            l.name = new_for;

        });

        //Now we need to do the image fields. First the div of the image
        newSection.find( '.iti-image-widget-image' ).each( function( index, div ){

            new_id = "themeists_iview_slider_widget-" + newIdNumber;
            div.id = new_id;


        });

        //And now the hidden input in that div
        newSection.find( 'input.hiddenimageid' ).each( function( index, input ){

            new_id = "widget-themeists_iview_slider_widget-" + newIdNumber + "-image";
            new_name = "widget-themeists_iview_slider_widget[" + newIdNumber + "][image]";

            input.id = new_id;
            input.name = new_name;
            input.value = "";

        });

        //Remove the image which has been cloned
        newSection.find( 'img' ).remove();


    } );

    jQuery('.widgets-holder-wrap').on('click', 'a.iti-image-widget-trigger', function(e){

        e.preventDefault();

        var href = jQuery(this).attr('href'), width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;

        if ( ! href ) return;

        href = href.replace(/&width=[0-9]+/g, '');
        href = href.replace(/&height=[0-9]+/g, '');

        jQuery(this).attr( 'href', href + '&width=' + ( W - 80 ) + '&height=' + ( H - 85 ) );
        
        iti_widget_image_context = jQuery(this).parent().find('.iti-image-widget-image');
        
        jQuery('#TB_title').remove();       // TODO: why is this necessary?
        
        tb_show(jQuery(this).attr('title'), e.target.href, false);

        iti_widget_image_thickbox_updater = setInterval( iti_widget_image_update_thickbox, 500 );

    });

    //Deal with the remove buttons being pressed
    jQuery( '.remove_clone' ).live( 'click', function(){

        jQuery(this).parent().remove();

    } );

});

//We need to hook into the ajax success (widget save) so we can ensure we're not outputting another 'add new' button
jQuery(document).ajaxSuccess(function(e, xhr, settings) {
    var widget_id_base = 'themeists_iview_slider_widget';

    if(settings.data && settings.data.search('action=save-widget') != -1 && settings.data.search('id_base=' + widget_id_base) != -1) {
        jQuery( '.clonebutton' ).each( function( index, input ){
            if( jQuery(this).parent().hasClass( 'widget-content' ) ){
                jQuery(this).hide();
            }
        });
    }
    
});