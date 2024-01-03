
import { __ } from '@wordpress/i18n';
import './editor.scss';
import React from 'react';
import Select from 'react-select';

const {
	PanelBody,
	PanelRow,
	SelectControl,
	TextControl,
	ToggleControl
} = wp.components;

	//layout: How to display the levels; accepts “div”, “table”, “2col”, “3col”, “4col” or “compare_table” (default: div).
	const layouts_type = [
		{ value: 'div', label: __( 'Div', 'pmpro-advanced-levels-shortcode' ) },
		{ value: 'table', label: __( 'Table', 'pmpro-advanced-levels-shortcode' ) },
		{ value: '2col', label: __( '2 columns', 'pmpro-advanced-levels-shortcode' ) },
		{ value: '3col', label: __( '3 columns', 'pmpro-advanced-levels-shortcode' ) },
		{ value: '4col', label: __( '4 columns', 'pmpro-advanced-levels-shortcode' ) },
		{ value: 'compare_table', label: __( 'Compare Table', 'pmpro-advanced-levels-shortcode' )}
	]

	//price: How to display the level cost text. accepts “full”, “short” or “hide” (default: short).
	const prices = [
		{ value: 'full', label: __( 'Full', 'pmpro-advanced-levels-shortcode' ) },
		{ value: 'short', label: __( 'Short', 'pmpro-advanced-levels-shortcode' ) },
		{ value: 'hide', label: __( 'Hide', 'pmpro-advanced-levels-shortcode' ) },
	]
	//template: Specify an integrated theme framework to inherit formatting. accepts “bootstrap”, “genesis”, “woo themes”, “gantry”, “pagelines” or “foundation” (default: none).
	const templates = [
		{ value: 'none', label: __( 'None', 'pmpro-advanced-levels-shortcode' ) },
		{ value: 'bootstrap', label: __( 'Bootstrap', 'pmpro-advanced-levels-shortcode' ) },
		{ value: 'genesis', label: __( 'Genesis', 'pmpro-advanced-levels-shortcode' ) },
		{ value: 'woo themes', label: __( 'Woo Themes', 'pmpro-advanced-levels-shortcode' ) },
		{ value: 'gantry', label: __( 'Gantry', 'pmpro-advanced-levels-shortcode' ) },
		{ value: 'pagelines', label: __( 'Pagelines', 'pmpro-advanced-levels-shortcode' ) },
		{ value: 'foundation', label: __( 'Foundation', 'pmpro-advanced-levels-shortcode' ) },
	]

/**
 * 
 * 
 */
const Edit = props  => {
	const { attributes: {back_link, checkout_button, description, more_button, discount_code, expiration, levels, highlight, layout, price, renew_button,template,compare}, setAttributes } = props;
	const all_levels = pmpro.all_level_values_and_labels;
	return (
		<div>
			<p>
				{ __(
					'Advanced Level Page Block',
					'advanced-level-page'
				) }
			</p>
			<PanelBody className="pmproal-gut-body">
				<PanelRow className='pmproalgut-panel-row'>
				<label for='pmpro-advanced-levels-hide-return-links' className='pmproalgut-label'>{__('Back Link', 'pmpro-advanced-level-shortcode')}</label>
					<ToggleControl 	
						id="pmpro-advanced-levels-hide-return-links"
						label={ __( ' Optionally hide “Return to Home” or “Return to Your Account” links below levels layout.', 'pmpro-advanced-level-shortcode' ) }
						checked={ back_link }
						onChange={ back_link => { setAttributes( { back_link } ) } }
					/>
				</PanelRow>
				<PanelRow className='pmproalgut-panel-row'>
					<label for='pmpro-advanced-levels-show-expiration' className='pmproalgut-label'>{__('Show Description', 'pmpro-advanced-level-shortcode')}</label>
					<ToggleControl 	
						id="pmpro-advanced-levels-show-description"
						label={ __( 'Either show the level description or not', 'pmpro-advanced-level-shortcode' ) }
						checked={ description }
						onChange={ description => { setAttributes( { description } ) } }
					/>
				</PanelRow>
				<PanelRow className='pmproalgut-panel-row'>
					<label for='pmpro-advanced-levels-more-button' className='pmproalgut-label'>{__('Show read more button', 'pmpro-advanced-level-shortcode')}</label>
					<ToggleControl 	
						id="pmpro-advanced-levels-more-button"
						label={ __( 'Either show the read more button or not', 'pmpro-advanced-level-shortcode' ) }
						checked={ more_button }
						onChange={ more_button => { setAttributes( { more_button } ) } }
					/>
				</PanelRow>
				<PanelRow className="pmproalgut-panel-row">
					<label for='pmpro-advanced-levels-show-expiration' className='pmproalgut-label'>{__('Show Level Expiration', 'pmpro-advanced-level-shortcode')}</label>
					<ToggleControl 	
						id="pmpro-advanced-levels-show-expiration"
						label={ __( 'Either show the level expiration or not', 'pmpro-advanced-level-shortcode' ) }
						checked={ expiration }
						onChange={ expiration => { setAttributes( { expiration } ) } }
					/>
				</PanelRow>

				<PanelRow className="pmproalgut-panel-row">
						<label for="levels" className="pmproalgut-label">
						{ __( 'Levels', 'pmpro-advanced-level-shortcode' ) }
						</label>
						<Select
						  	classNamePrefix='filter'
							value={ levels }
							onChange={ levels => { setAttributes( { levels } ) } }
							options={ all_levels }
							isMulti='true'
							name='levels'
							id='levels'
							className='components-text-control__input'
						/>
				</PanelRow>
				{ layout == 'compare_table' &&
				  <PanelRow className="pmproalgut-panel-row">
					<SelectControl
						classNamePrefix='filter'
						value={ highlight }
						label={ __( 'Highlight', 'pmpro-advanced-level-shortcode' ) }
						options={ levels }
						name="highlight"
						id="highlight"
						onChange={ highlight => { setAttributes( { highlight } ) } }
					/>
				</PanelRow> }
				<PanelRow className="pmproalgut-panel-row">
					<SelectControl 
						label={ __( 'Layouts', 'pmpro-advanced-level-shortcode' ) }
						options={layouts_type}
						value={layout}
						onChange={ layout => { setAttributes( { layout } ) } }
					/>
				</PanelRow>
				{ layout == 'compare_table' && <PanelRow className="pmproalgut-panel-row">
					<TextControl
						id="pmpro-advanced-levels-discount-code-text"
						label={ __( 'Discount code', 'pmpro-advanced-level-shortcode' ) }
						value={ discount_code }
						onChange={ discount_code => { setAttributes( { discount_code } ) } }
					/>
				</PanelRow> }
				<PanelRow className="pmproalgut-panel-row">
					<SelectControl
						label={ __( 'Price', 'pmpro-advanced-level-shortcode' ) }
						options={prices}
						value={price}
						onChange={ price => { setAttributes( { price } ) } }
					/>
				</PanelRow>
				<PanelRow className="pmproalgut-panel-row">
						<SelectControl
						label={ __( 'Template', 'pmpro-advanced-level-shortcode' ) }
						options={templates}
						value={template}
						onChange={ template => { setAttributes( { template } ) } }
						/>
				</PanelRow>

				<PanelRow className="pmproalgut-panel-row">
					<TextControl
						id="pmpro-advanced-levels-checkout-button-text"
						label={ __( 'Checkout button', 'pmpro-advanced-level-shortcode' ) }
						value={ checkout_button || 'Select'  }
						onChange={ checkout_button => { setAttributes( { checkout_button } ) } }
					/>
				</PanelRow>
				<PanelRow className="pmproalgut-panel-row">
					<TextControl
						id="pmpro-advanced-levels-discount-code-text"
						label={ __( 'Compare Attribute', 'pmpro-advanced-level-shortcode' ) }
						onChange={ compare => { setAttributes( { compare } ) } }
						value={ compare }
					/>
				</PanelRow>

				<PanelRow className="pmproalgut-panel-row">
					<TextControl
						id="pmpro-advanced-levels-renew-button-text"
						label={ __( 'Renew button', 'pmpro-advanced-level-shortcode' ) }
						value={ renew_button || 'Renew' }
						onChange={ renew_button => { setAttributes( { renew_button } ) } }
					/>
				</PanelRow>
			</PanelBody>
		</div>
	);
}

export default Edit;