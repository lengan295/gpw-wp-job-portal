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
registerBlockType( 'wpjobportal/wpjobportalcompaniesblock', {
    title: 'Companies',
    icon: 'universal-access-alt',
    category: 'layout',// category of block (need to check if i can define custom category)
    attributes: {
        title: {
            type: 'string',
            default: 'Featured Companies'
        },
        companytype: {
            type: 'select',
            default: '1'
        },
        fieldcolumn:{
            type:'select',
            default:'1'
        },
        listingstyle:{
            type:'select',
            default:'1'
        },
        noofcompanies:{
            type:'string',
            default: '1'
        }
    },
    edit: function( props ) {
        return el('div',{},
                el(ServerSideRender, {
                    block: "wpjobportal/wpjobportalcompaniesblock",
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
                        label: __( 'Company Type' ),
                        value: props.attributes.companytype,
                        onChange: function(value) {
                            props.setAttributes({companytype: value});
                        },
                        options: [
                           { value: '0', label: __( 'Select widget' ) },
                           { value: '2', label: __( 'Featured Companies' ) },
                        ],
                    }
                ),
                el(
                    SelectControl, {
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
                ),
                el(
                    TextControl, {
                        label: __( 'No. of Companies' ),
                        value: props.attributes.noofcompanies,
                        onChange: function(value) {
                            props.setAttributes({noofcompanies: value});
                        }
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