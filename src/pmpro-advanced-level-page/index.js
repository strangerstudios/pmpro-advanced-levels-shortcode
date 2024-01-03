import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import './style.scss';
import Edit from './edit';
import metadata from './block.json';
import Save from './save';

export default registerBlockType( metadata.name, {
	description: __( 'Creates an advanced levels block shortcode', 'pmpro-advanced-levels-shortcode'),
		category: 'pmpro',
		icon: {
			background: '#2997C8',
			foreground: '#FFFFFF',
			src: 'bank'
		},
		keywords: [ 
		__( 'pmpro', 'pmpro-advanced-levels-shortcode' ),
		__( 'advanced-levels-shortcode', 'pmpro-advanced-levels-shortcode' ),
		__( 'membership levels', 'pmpro-advanced-levels-shortcode' )
		 ],
	edit: Edit,
	save: Save,
	attributes: {
		back_link: {
			type: 'boolean',
			default: true,
		},
		checkout_button: {
			type: 'string',
			default: 'Select'
		},
		description: {
			type: 'boolean',
			default: true
		},
		more_button: {
			type: 'boolean',
			default: false
		},
		discount_code: {
			type: 'string',
			default: ''
		},
		expiration: {
			type: 'boolean',
			default: true
		},
		levels: {
			type: 'array',
			default: []
		},
		highlight: {
			type: 'string',
			default: ''
		},
		layout: {
			type: 'string',
			default: 'div'
		},
		price: {
			type: 'string',
			default: 'short'
		},
		renew_button: {
			type: 'string',
			default: 'Renew'
		},
		template: {
			type: 'string',
			default: 'none'
		},
		compare: {
			type: 'string',
			default: ''
		}
	}
});
