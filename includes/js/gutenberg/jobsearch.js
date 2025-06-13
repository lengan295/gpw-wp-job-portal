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
registerBlockType( 'wpjobportal/wpjobportaljobsearchblock', {
    title: 'Job Search',
    icon: 'universal-access-alt',
    category: 'layout',// category of block (need to check if i can define custom category)
    attributes: {
        title: {
            type: 'string',
            default: 'Job Search'
        },
        showtitle: {
            type: 'select',
            default: '1'
        },
        jobtitle:{
            type:'select',
            default:'1'
        },
        category:{
            type:'select',
            default:'1'
        },
        jobtype:{
            type:'select',
            default: '1'
        },
        jobstatus:{
            type:'select',
            default: '1'
        },
        salaryrange:{
            type:'select',
            default: '1'
        },
        shift:{
            type:'select',
            default: '1'
        },
        duration:{
            type:'select',
            default: '1'
        },
        startpublishing:{
            type:'select',
            default: '1'
        },
        stoppublishing:{
            type:'select',
            default: '1'
        },
        company:{
            type:'select',
            default: '1'
        },
        address:{
            type:'select',
            default: '1'
        },
        columnperrow:{
            type:'string',
            default: '1'
        },
    },
    edit: function( props ) {
        return el('div',{},
                el(ServerSideRender, {
                    block: "wpjobportal/wpjobportaljobsearchblock",
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
                        label: __( 'Show Title' ),
                        value: props.attributes.showtitle,
                        onChange: function(value) {
                            props.setAttributes({showtitle: value});
                        },
                        options: [
                           { value: '0', label: __( 'Hide' ) },
                           { value: '1', label: __( 'Show' ) },
                        ],
                    }
                ),
                el(
                    SelectControl, {
                        type: 'number',
                        label: __( 'Job Title' ),
                        value: props.attributes.jobtitle,
                        onChange: function(value) {
                            props.setAttributes({jobtitle: value});
                        },
                        options: [
                           { value: '0', label: __( 'Hide' ) },
                           { value: '1', label: __( 'Show' ) },
                        ],
                    }
                ),
                el(
                    SelectControl, {
                        type: 'number',
                        label: __( 'Category' ),
                        value: props.attributes.category,
                        onChange: function(value) {
                            props.setAttributes({category: value});
                        },
                        options: [
                           { value: '0', label: __( 'Hide' ) },
                           { value: '1', label: __( 'Show' ) },
                        ],
                    }
                ),
                el(
                    SelectControl, {
                        type: 'number',
                        label: __( 'Job Type' ),
                        value: props.attributes.jobtype,
                        onChange: function(value) {
                            props.setAttributes({jobtype: value});
                        },
                        options: [
                           { value: '0', label: __( 'Hide' ) },
                           { value: '1', label: __( 'Show' ) },
                        ],
                    }
                ),
                el(
                    SelectControl, {
                        type: 'number',
                        label: __( 'Job Status' ),
                        value: props.attributes.jobstatus,
                        onChange: function(value) {
                            props.setAttributes({jobstatus: value});
                        },
                        options: [
                           { value: '0', label: __( 'Hide' ) },
                           { value: '1', label: __( 'Show' ) },
                        ],
                    }
                ),
                el(
                    SelectControl, {
                        type: 'number',
                        label: __( 'Salary Range' ),
                        value: props.attributes.salaryrange,
                        onChange: function(value) {
                            props.setAttributes({salaryrange: value});
                        },
                        options: [
                           { value: '0', label: __( 'Hide' ) },
                           { value: '1', label: __( 'Show' ) },
                        ],
                    }
                ),
                el(
                    SelectControl, {
                        type: 'number',
                        label: __( 'Shift' ),
                        value: props.attributes.shift,
                        onChange: function(value) {
                            props.setAttributes({shift: value});
                        },
                        options: [
                           { value: '0', label: __( 'Hide' ) },
                           { value: '1', label: __( 'Show' ) },
                        ],
                    }
                ),
                el(
                    SelectControl, {
                        type: 'number',
                        label: __( 'Duration' ),
                        value: props.attributes.duration,
                        onChange: function(value) {
                            props.setAttributes({duration: value});
                        },
                        options: [
                           { value: '0', label: __( 'Hide' ) },
                           { value: '1', label: __( 'Show' ) },
                        ],
                    }
                ),
                el(
                    SelectControl, {
                        type: 'number',
                        label: __( 'Start Publishing' ),
                        value: props.attributes.startpublishing,
                        onChange: function(value) {
                            props.setAttributes({startpublishing: value});
                        },
                        options: [
                           { value: '0', label: __( 'Hide' ) },
                           { value: '1', label: __( 'Show' ) },
                        ],
                    }
                ),
                el(
                    SelectControl, {
                        type: 'number',
                        label: __( 'Stop Publishing' ),
                        value: props.attributes.stoppublishing,
                        onChange: function(value) {
                            props.setAttributes({stoppublishing: value});
                        },
                        options: [
                           { value: '0', label: __( 'Hide' ) },
                           { value: '1', label: __( 'Show' ) },
                        ],
                    }
                ),
                el(
                    SelectControl, {
                        type: 'number',
                        label: __( 'Company' ),
                        value: props.attributes.company,
                        onChange: function(value) {
                            props.setAttributes({company: value});
                        },
                        options: [
                           { value: '0', label: __( 'Hide' ) },
                           { value: '1', label: __( 'Show' ) },
                        ],
                    }
                ),
                el(
                    SelectControl, {
                        type: 'number',
                        label: __( 'Address' ),
                        value: props.attributes.address,
                        onChange: function(value) {
                            props.setAttributes({address: value});
                        },
                        options: [
                           { value: '0', label: __( 'Hide' ) },
                           { value: '1', label: __( 'Show' ) },
                        ],
                    }
                ),el(
                    TextControl, {
                        label: __( 'Column rer row' ),
                        value: props.attributes.columnperrow,
                        onChange: function(value) {
                            props.setAttributes({columnperrow: value});
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