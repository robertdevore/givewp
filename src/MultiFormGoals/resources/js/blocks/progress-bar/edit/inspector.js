/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, TextControl, SelectControl } = wp.components;

/**
 * Internal dependencies
 */

import MultiSelectControl from '../../../components/multi-select-control';
import ColorControl from '../../../components/color-control';
import { useFormOptions, useTagOptions, useCategoryOptions } from '../data/utils';

/**
 * Render Inspector Controls
*/

const Inspector = ( { attributes, setAttributes } ) => {
	const { ids, categories, tags, metric, goal, deadline, color } = attributes;
	const formOptions = useFormOptions();
	const tagOptions = useTagOptions();
	const categoryOptions = useCategoryOptions();
	const saveSetting = ( name, value ) => {
		setAttributes( {
			[ name ]: value,
		} );
	};
	return (
		<InspectorControls key="inspector">
			<PanelBody title={ __( 'Goal', 'give' ) } initialOpen={ true }>
				<TextControl
					name="goal"
					label={ __( 'Goal', 'give' ) }
					type="number"
					onChange={ ( value ) => saveSetting( 'goal', value ) }
					value={ goal }
				/>
				<SelectControl
					label={ __( 'Goal Format', 'give' ) }
					value={ metric }
					options={ [
						{ label: __( 'Revenue', 'give' ), value: 'revenue' },
						{ label: __( 'Number of Donors', 'give' ), value: 'donor-count' },
						{ label: __( 'Number of Donations', 'give' ), value: 'donation-count' },
					] }
					onChange={ ( value ) => saveSetting( 'metric', value ) }
				/>
				<TextControl
					name="deadline"
					label={ __( 'Deadline', 'give' ) }
					type="date"
					value={ deadline }
					onChange={ ( value ) => saveSetting( 'deadline', value ) }
				/>
				<ColorControl
					name="color"
					label={ __( 'Progress Bar Color', 'give' ) }
					onChange={ ( value ) => saveSetting( 'color', value ) }
					value={ color }
				/>
			</PanelBody>
			<PanelBody title={ __( 'Filters', 'give' ) } initialOpen={ false }>
				<MultiSelectControl
					name="ids"
					label={ __( 'Filter by Forms', 'give' ) }
					value={ formOptions.filter( option => ids.includes( option.value ) ) }
					options={ formOptions }
					onChange={ ( value ) => saveSetting( 'ids', value ? value.map( ( option ) => option.value ) : [] ) } />
				<MultiSelectControl
					name="tags"
					label={ __( 'Filter by Tags', 'give' ) }
					value={ tagOptions.filter( option => tags.includes( option.value ) ) }
					options={ tagOptions }
					onChange={ ( value ) => saveSetting( 'tags', value ? value.map( ( option ) => option.value ) : [] ) } />
				<MultiSelectControl
					name="categories"
					label={ __( 'Filter by Categories', 'give' ) }
					value={ categoryOptions.filter( option => categories.includes( option.value ) ) }
					options={ categoryOptions }
					onChange={ ( value ) => saveSetting( 'categories', value ? value.map( ( option ) => option.value ) : [] ) } />
			</PanelBody>
		</InspectorControls>
	);
};

export default Inspector;