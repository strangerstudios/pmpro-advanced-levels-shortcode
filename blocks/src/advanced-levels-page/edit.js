/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * WordPress dependencies
 */
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextareaControl, TextControl, ToggleControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Render the Advanced Levels Page block in the editor.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	const blockProps = useBlockProps();
	const {
		back_link,
		checkout_button,
		compare,
		description,
		discount_code,
		expiration,
		layout,
		levels,
		price,
		renew_button
	} = attributes;

	const layout_types = [
		{ value: 'div', label: __( 'Div', 'pmpro-advanced-levels-shortcode' ) },
		{ value: 'table', label: __( 'Table', 'pmpro-advanced-levels-shortcode' ) },
		{ value: '2col', label: __( '2 Columns', 'pmpro-advanced-levels-shortcode' ) },
		{ value: '3col', label: __( '3 Columns', 'pmpro-advanced-levels-shortcode' ) },
		{ value: '4col', label: __( '4 Columns', 'pmpro-advanced-levels-shortcode' ) },
		{ value: 'compare_table', label: __( 'Compare Table', 'pmpro-advanced-levels-shortcode' )}
	];

	const price_types = [
		{ value: 'full', label: __( 'Full', 'pmpro-advanced-levels-shortcode' ) },
		{ value: 'short', label: __( 'Short', 'pmpro-advanced-levels-shortcode' ) },
		{ value: 'hide', label: __( 'Hide', 'pmpro-advanced-levels-shortcode' ) },
	];

	return (
		<>
		<InspectorControls>
			<PanelBody>
				<TextControl
					label={ __( 'Levels', 'pmpro-advanced-levels-shortcode' ) }
					help={__('Enter a comma-separate list of level IDs in the order you would like them to display.', 'pmpro-advanced-levels-shortcode')}
					value={levels}
					onChange={(value) => {
						setAttributes({
							levels: value,
						});
					}}
				/>
				<SelectControl
					label={ __( 'Layout', 'pmpro-advanced-levels-shortcode' ) }
					value={layout}
					options={layout_types}
					onChange={(value) => {
						setAttributes({
							layout: value,
						});
					}}
				/>
				{ layout=='compare_table' &&
					<TextareaControl
						label={__('Compare Table Items', 'pmpro-advanced-levels-shortcode')}
						help={__('Enter groups of comparison rows separated by a semi-colon. For each comparison row, separate the label and each value with a comma (e.g. "Feature 1,No,Yes,Yes;Feature 2,No,No,Yes").', 'pmpro-advanced-levels-shortcode')}
						value={compare}
						onChange={compare => {
							setAttributes({
								compare
							})
						}}
					/>
				}
				<SelectControl
					label={ __( 'Price', 'pmpro-advanced-levels-shortcode' ) }
					help={ __( 'Display the level price in your chosen format.', 'pmpro-advanced-levels-shortcode' ) }
					value={price}
					options={price_types}
					onChange={(value) => {
						setAttributes({
							price: value,
						});
					}}
				/>
				<TextControl
					label={__('Discount Code', 'pmpro-advanced-levels-shortcode')}
					help={__('Enter a discount code to apply to all applicable levels.', 'pmpro-advanced-levels-shortcode')}
					value={discount_code}
					onChange={discount_code => {
						setAttributes({
							discount_code
						})
					}}
				/>
				<ToggleControl
					label={ __( 'Level Description', 'pmpro-advanced-levels-shortcode' ) }
					help={ __( 'Display the level description, if defined.', 'pmpro-advanced-levels-shortcode' ) }
					checked={description}
					onChange={(value) => {
						setAttributes({
							description: value,
						});
					}}
				/>
				<ToggleControl
					label={ __( 'Level Expiration', 'pmpro-advanced-levels-shortcode' ) }
					help={ __( 'Display the level expiration, if applicable.', 'pmpro-advanced-levels-shortcode' ) }
					checked={expiration}
					onChange={(value) => {
						setAttributes({
							expiration: value,
						});
					}}
				/>
				<TextControl
					label={__('Checkout Button Label', 'pmpro-advanced-levels-shortcode')}
					help={__('Enter custom text to change the label of the checkout button.', 'pmpro-advanced-levels-shortcode')}
					value={checkout_button}
					onChange={checkout_button => {
						setAttributes({
							checkout_button
						})
					}}
				/>
				<TextControl
					label={__('Renew Button', 'pmpro-advanced-levels-shortcode')}
					help={__('Enter custom text to change the label of the renew button.', 'pmpro-advanced-levels-shortcode')}
					value={renew_button}
					onChange={renew_button => {
						setAttributes({
							renew_button
						})
					}}
				/>
				<ToggleControl
					label={__('Back Link', 'pmpro-advanced-levels-shortcode')}
					help={__('Display a link to the membership account page for current members and the home page for everyone else.', 'pmpro-advanced-levels-shortcode')}
					checked={back_link}
					onChange={(value) => {
						setAttributes({
							back_link: value,
						});
					}}
				/>
			</PanelBody>
		</InspectorControls>
		<div { ...blockProps }>
			<ServerSideRender 
				block="pmpro-advanced-levels/advanced-levels-page"
				attributes={ attributes }
			/>	
		</div>
		</>
	);
}