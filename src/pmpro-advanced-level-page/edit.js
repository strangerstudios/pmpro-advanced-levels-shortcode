
import { __ } from '@wordpress/i18n';
import React from 'react';
import Select from 'react-select';
import { TextareaControl, TextControl, ToggleControl, SelectControl, PanelBody, PanelRow } from '@wordpress/components';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';

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
	const { attributes: {back_link, checkout_button, description, more_button, discount_code, expiration, levels, layout, price, renew_button, template, compare}, setAttributes, isSelected } = props;
	const all_levels = pmpro.all_level_values_and_labels;
	const blockProps = useBlockProps({ className: "pmpro-block-element" });

	return [
		!isSelected && <div {...blockProps}>
			<span className="pmpro-block-title"> {__('Advanced Level Page Block', 'pmpro-advanced-levels-shortcode')}</span>
			<span className="pmpro-block-subtitle">{__('Chosen layout: ', 'pmpro-advanced-levels-shortcode') + layout}</span>
		</div>,
		isSelected && <div {...blockProps}>
			<InspectorControls>
				<PanelBody>
					<PanelRow className="select2-multi-row">
					<label for="levels" class="components-truncate components-text components-input-control__label em5sgkm4 css-1imalal e19lxcc00">
						{__('Levels', 'pmpro-advanced-levels-shortcode')}
					</label>
					<Select
						classNamePrefix='filter'
						value={levels}
						onChange={levels => { setAttributes({ levels }) }}
						options={all_levels}
						isMulti='true'
						name='levels'
						id='levels'
						className='components-text-control__input'
					/>
				</PanelRow>
					<PanelRow>
						<TextControl
							id="pmpro-advanced-levels-checkout-button-text"
							label={__('Checkout button', 'pmpro-advanced-levels-shortcode')}
							help={__('Enter the text for the checkout button.', 'pmpro-advanced-levels-shortcode')}
							value={checkout_button}
							onChange={checkout_button => { setAttributes({ checkout_button }) }}
						/>
					</PanelRow>
					<PanelRow>
						<TextControl
							id="pmpro-advanced-levels-discount-code-text"
							label={__('Discount code', 'pmpro-advanced-levels-shortcode')}
							help={__('Enter a discount code to automatically apply the discount when redirected to the checkout page.', 'pmpro-advanced-levels-shortcode')}
							value={discount_code}
							onChange={discount_code => { setAttributes({ discount_code }) }}
						/>
					</PanelRow>
					<PanelRow>
						<SelectControl
							label={__('Page layout', 'pmpro-advanced-levels-shortcode')}
							help={__('Choose the layout of the levels page.', 'pmpro-advanced-levels-shortcode')}
							options={layouts_type}
							value={layout}
							onChange={layout => { setAttributes({ layout }) }}
						/>
					</PanelRow>
					<PanelRow className={layout != 'compare_table' ? 'hidden' : ''}>
						<TextareaControl
							id="pmpro-advanced-levels-discount-code-text"
							label={__('Compare Items', 'pmpro-advanced-levels-shortcode')}
							help={__('Enter compare item datasets and use semi-colon to separate datasets.', 'pmpro-advanced-levels-shortcode' ) }
							onChange={compare => { setAttributes({ compare }) }}
							value={compare}
						/>
					</PanelRow>
					<PanelRow>
						<SelectControl
							label={__('Display Price', 'pmpro-advanced-levels-shortcode')}
							help={__('Choose the output of the levels price.', 'pmpro-advanced-levels-shortcode')}
							options={prices}
							value={price}
							onChange={price => { setAttributes({ price }) }}
						/>
					</PanelRow>
					<PanelRow>
						<TextControl
							id="pmpro-advanced-levels-renew-button-text"
							label={__('Renew button', 'pmpro-advanced-levels-shortcode')}
							help={__('Enter the text for the renew button.', 'pmpro-advanced-levels-shortcode')}
							value={renew_button}
							onChange={renew_button => { setAttributes({ renew_button }) }}
						/>
					</PanelRow>
					<PanelRow>
						<SelectControl
							label={__('Template', 'pmpro-advanced-levels-shortcode')}
							help={__('Choose a style for the levels page.', 'pmpro-advanced-levels-shortcode')}
							options={templates}
							value={template}
							onChange={template => { setAttributes({ template }) }}
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							id="pmpro-advanced-levels-show-description"
							label={__('Show the level description', 'pmpro-advanced-levels-shortcode')}
							checked={description}
							onChange={description => { setAttributes({ description }) }}
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							id="pmpro-advanced-levels-hide-return-links"
							label={__(' Show back link.', 'pmpro-advanced-levels-shortcode')}
							checked={back_link}
							onChange={back_link => { setAttributes({ back_link }) }}
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							id="pmpro-advanced-levels-more-button"
							label={__('Show the read more button', 'pmpro-advanced-levels-shortcode')}
							checked={more_button}
							onChange={more_button => { setAttributes({ more_button }) }}
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							id="pmpro-advanced-levels-show-expiration"
							label={__('Show the level expiration', 'pmpro-advanced-levels-shortcode')}
							checked={expiration}
							onChange={expiration => { setAttributes({ expiration }) }}
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			{/* Inline Settings start */}
			<span className="pmpro-block-title">{__( 'Advanced Level Page Block', 'advanced-level-page' )}</span> 
			<PanelBody>
				<PanelRow className="select2-multi-row">
					<label for="levels" class="components-truncate components-text components-input-control__label em5sgkm4 css-1imalal e19lxcc00">
						{__('Display Levels', 'pmpro-advanced-levels-shortcode')}
					</label>
					<Select
						classNamePrefix='filter'
						value={levels}
						onChange={levels => { setAttributes({ levels }) }}
						options={all_levels}
						isMulti='true'
						name='levels'
						id='levels'
						className='components-text-control__input'
					/>
				</PanelRow>
			</PanelBody>
			<PanelBody>
				<PanelRow className="select2-multi-row">
					<SelectControl
						label={__('Page layout', 'pmpro-advanced-levels-shortcode')}
						help={__('Choose the layout of the levels page.', 'pmpro-advanced-levels-shortcode')}
						options={layouts_type}
						value={layout}
						onChange={layout => { setAttributes({ layout }) }}
					/>
					<TextareaControl
						className={layout != 'compare_table' ? 'hidden' : ''}
						id="pmpro-advanced-levels-discount-code-text"
						label={__('Compare Items', 'pmpro-advanced-levels-shortcode')}
						help={__('Enter compare item datasets and use semi-colon to separate datasets.', 'pmpro-advanced-levels-shortcode')}
						onChange={compare => { setAttributes({ compare }) }}
						value={compare}
					/>
				</PanelRow>
			</PanelBody>
			<p>{__('Please see the sidebar for all controls and settings.', 'pmpro-advanced-levels-shortcode')}</p>
		</div>
	];
}

export default Edit;