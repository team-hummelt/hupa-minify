jQuery(document).ready(function ($) {

    $( '#container' ).html( '<ul class="filetree start"><li class="wait">' + 'Generating Tree...' + '<li></ul>' );
    let folder = $('#minifyThemeRoot').attr('data-folder');

    getfilelist( $('#container') , folder );

    function getfilelist( cont, root ) {
        let folder = $('#minifyThemeRoot').attr('data-folder');
        $( cont ).addClass( 'wait' );
        $.post( minify_ajax_obj.ajax_url,
            {
                'action': 'HupaMinifyFolder',
                '_ajax_nonce': minify_ajax_obj.nonce,
                dir: root
            }, function( data ) {
            $( cont ).find( '.start' ).html( '' );
            $( cont ).removeClass( 'wait' ).append( data );
            if( folder == root )
                $( cont ).find('UL:hidden').show();
            else
                $( cont ).find('UL:hidden').slideDown({ duration: 500, easing: null });
        });
    }

    $( '#container' ).on('click', 'LI A', function() {
        let entry = $(this).parent();
        $(this).trigger('blur');
        if( entry.hasClass('folder') ) {
            if( entry.hasClass('collapsed') ) {
                entry.find('UL').remove();
                getfilelist( entry, escape( $(this).attr('rel') ));
                entry.removeClass('collapsed').addClass('expanded');
            }
            else {
                entry.find('UL').slideUp({ duration: 500, easing: null });
                entry.removeClass('expanded').addClass('collapsed');
            }
           let selectFolder = $(this).attr('data-folder');
           let currentSelect = $( '#container li a');
           currentSelect.removeClass('active');
           $(this).addClass('active');
            $('.btn-select-folder').attr('data-source', selectFolder);
           let html = `<i class="fa fa-folder-open text-muted"></i>&nbsp;
                        <b class="strong-font-weight wp-blue">${selectFolder}</b>`;
            $('.ordner-select').html(html)

        } else {
            const regex = /.*?-.+\d\/|(.+)/gm;
            let text = $(this).attr( 'rel' );
            let m;
            while ((m = regex.exec(text)) !== null) {
                // This is necessary to avoid infinite loops with zero-width matches
                if (m.index === regex.lastIndex) {
                    regex.lastIndex++;
                }
                // The result can be accessed through the `m`-variable.
                m.forEach((match, groupIndex) => {
                   // console.log(`Found match, group ${groupIndex}: ${match}`);
                    if(groupIndex === 1) {
                      $( '#selected_file' ).text(match);
                        return false;
                    }
                });
            }
        //   $( '#selected_file' ).text( "File:  " + $(this).attr( 'rel' ));
        }
        return false;
    });

    //folder
    $(document).on('click', '.btn-show-folder-tree', function () {
        $('.show-form-input').toggleClass('d-none');
        let btnTarget = $(this).attr('data-target');
        if(btnTarget) {
           let btnSelectFolder = $('.btn-select-folder');
           btnSelectFolder.attr('data-target',btnTarget);
        }
    });
});