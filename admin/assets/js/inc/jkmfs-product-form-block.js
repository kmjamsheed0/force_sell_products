( function ( wp ) {
    var el = wp.element.createElement;

    wp.blocks.registerBlockType( 'jkmfs/optional-add-ons-field', {
        title: 'Optional Product form field',
        attributes: {},
        edit: function () {
            return el( 'p', {}, 'Optional products.' );
        },
    } );

    wp.blocks.registerBlockType( 'jkmfs/mandatory-add-ons-field', {
        title: 'Mandatory Product form field',
        attributes: {},
        edit: function () {
            return el( 'p', {}, 'Mandatory products.' );
        },
    } );
} )( window.wp );