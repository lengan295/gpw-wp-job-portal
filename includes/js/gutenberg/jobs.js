var el = wp.element.createElement,
registerBlockType = wp.blocks.registerBlockType;
RichText = wp.editor.RichText;
blocks = wp.blocks;
BlockControls = wp.editor.BlockControls,
InspectorControls = wp.editor.InspectorControls;
TextControl = wp.components.TextControl;
SelectControl = wp.components.SelectControl;
__ = wp.i18n.__;
ServerSideRender = wp.components.ServerSideRender;
registerBlockType( 'wpjobportal/wpjobportaljobsblock', {
    title: 'WP Job Portal',
    icon: 'universal-access-alt',
    category: 'layout',// category of block (need to check if i can define custom category)
    attributes: {
        title: {
            type: 'string',
            default: 'Latest Jobs'
        },
        typeofjobs: {
            type: 'select',
            default: '1'
        },
        noofjobs:{
            type:'select',
            default:'1'
        },
        fieldcolumn:{
            type:'select',
            default:'1'
        },
        listingstyle:{
            type:'select',
            default:'1'
        }
    },
    edit: function( props ) {
        return el('div',{},
                el(ServerSideRender, {
                    block: "wpjobportal/wpjobportaljobsblock",
                    attributes:  props.attributes
                }),
                
            el( 
                InspectorControls,{},
                el(
                    TextControl, {
                        label: __( 'Title' ),
                        value: props.attributes.title,
                        onChange: function(value) {
                            props.setAttributes({title: value});
                        }
                    }
                ),
                el(
                    SelectControl, {
                        type: 'number',
                        label: __( 'Type of jobs' ),
                        value: props.attributes.typeofjobs,
                        onChange: function(value) {
                            props.setAttributes({typeofjobs: value});
                        },
                        options: [
                           { value: '0', label: __( 'Select widget' ) },
                           { value: '1', label: __( 'Newest Jobs' ) },
                           { value: '2', label: __( 'Top Jobs' ) },
                           { value: '3', label: __( 'Hot Jobs' ) },
                           { value: '5', label: __( 'Featured Jobs' ) },
                        ],
                    }
                ),
                el(
                    TextControl, {
                        label: __( 'No. of jobs' ),
                        value: props.attributes.noofjobs,
                        onChange: function(value) {
                            props.setAttributes({noofjobs: value});
                        }
                    }
                ),
                el(
                    SelectControl, {
                        id: 'column_select',
                        type: 'number',
                        label: __( 'Columns' ),
                        value: props.attributes.fieldcolumn,
                        onChange: function(value) {
                            props.setAttributes({fieldcolumn: value});
                        },
                        options:[
                            { value: '1', label: __( '1' ) },
                            { value: '2', label: __( '2' ) },
                            { value: '3', label: __( '3' ) },
                            { value: '4', label: __( '4' ) },
                            { value: '5', label: __( '5' ) },
                        ]
                    }
                )
            )
        )
    },
    save: function(props) {
        // Rendering in PHP
        return null;
    },
} )