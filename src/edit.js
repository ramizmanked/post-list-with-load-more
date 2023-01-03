import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	PanelRow,
	TextControl,
	RadioControl,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import ServerSideRender from '@wordpress/server-side-render';

import metadata from './block.json';

export default function Edit({ attributes, setAttributes }) {
	const {
		postType,
		taxonomy,
		term,
		postsPerPage,
		orderBy,
		order,
		layout,
		morePosts,
		gridColumns,
		listLayout,
	} = attributes;
	let postTypeOptions = [
		{
			label: '-- Select PostType --',
			value: '',
		},
	];
	let taxonomyOptions = [
		{
			label: '-- Select Taxonomy --',
			value: '',
		},
	];
	let termOptions = [
		{
			label: '-- Select Term --',
			value: '',
		},
	];
	const orderByOptions = [
		{
			label: 'Post Title',
			value: 'title',
		},
		{
			label: 'Post Published Date',
			value: 'date',
		},
		{
			label: 'Post Author',
			value: 'author',
		},
	];
	const orderOptions = [
		{
			label: 'Ascending',
			value: 'asc',
		},
		{
			label: 'Descending',
			value: 'desc',
		},
	];
	const layoutOptions = [
		{
			label: 'Grid',
			value: 'grid',
		},
		{
			label: 'List',
			value: 'list',
		},
	];
	const morePostsOptions = [
		{
			label: 'On Button Click',
			value: 'click',
		},
		{
			label: 'On Scroll',
			value: 'scroll',
		},
	];
	const listLayoutOptions = [
		{
			label: 'Side By Side',
			value: 'sideBySide',
		},
		{
			label: 'Linear',
			value: 'linear',
		},
	];

	const postTypes = useSelect((select) => select('core').getPostTypes(), []);
	if (Array.isArray(postTypes)) {
		const filteredPostTypes = postTypes
			.filter(
				(filteredPostType) =>
					filteredPostType.viewable === true &&
					filteredPostType.slug !== 'attachment'
			)
			.map((filteredPostType) => ({
				label: filteredPostType.labels.singular_name,
				value: filteredPostType.slug,
			}));
		postTypeOptions = postTypeOptions.concat(filteredPostTypes);
	}

	const taxonomies = useSelect(
		(select) => {
			if (postType !== null && postType !== '') {
				return select('core').getTaxonomies();
			}
		},
		[postType]
	);
	if (Array.isArray(taxonomies)) {
		const filteredTaxonomies = taxonomies
			.filter((filteredTaxonomy) =>
				filteredTaxonomy.types.includes(postType)
			)
			.map((filteredTaxonomy) => ({
				label: filteredTaxonomy.labels.singular_name,
				value: filteredTaxonomy.slug,
			}));
		taxonomyOptions = taxonomyOptions.concat(filteredTaxonomies);
	}

	const terms = useSelect(
		(select) => {
			if (taxonomy !== null && taxonomy !== '') {
				return select('core').getEntityRecords('taxonomy', taxonomy);
			}
		},
		[taxonomy]
	);
	if (Array.isArray(terms)) {
		const filteredTerms = terms.map((filteredTerm) => ({
			label: filteredTerm.name,
			value: filteredTerm.slug,
		}));
		termOptions = termOptions.concat(filteredTerms);
	}

	return (
		<div {...useBlockProps()}>
			<InspectorControls>
				<PanelBody
					title={__('Filter Settings', 'post-list-with-load-more')}
				>
					<PanelRow>
						<SelectControl
							label="Select Post Type"
							help="Filter posts from specific post type."
							value={postType}
							options={postTypeOptions}
							onChange={(newPostType) =>
								setAttributes({ postType: newPostType })
							}
							__nextHasNoMarginBottom
						/>
					</PanelRow>
					<PanelRow>
						<SelectControl
							label="Select Taxonomy"
							help="Filter posts from specific taxonomy."
							value={taxonomy}
							options={taxonomyOptions}
							onChange={(newTaxonomy) =>
								setAttributes({ taxonomy: newTaxonomy })
							}
							__nextHasNoMarginBottom
						/>
					</PanelRow>
					<PanelRow>
						<SelectControl
							label="Select Term"
							help="Filter posts from specific taxonomy term."
							value={term}
							options={termOptions}
							onChange={(newterm) =>
								setAttributes({ term: newterm })
							}
							__nextHasNoMarginBottom
						/>
					</PanelRow>
					<PanelRow>
						<TextControl
							label="Posts Per Page"
							type="number"
							help="Number of posts to show and then Load More button appears."
							onChange={(newPostsPerPage) =>
								setAttributes({
									postsPerPage: newPostsPerPage,
								})
							}
							min={1}
							max={100}
							value={postsPerPage}
						/>
					</PanelRow>
					<PanelRow>
						<SelectControl
							label="Order By"
							help="Order posts by several attributes."
							value={orderBy}
							options={orderByOptions}
							onChange={(newOrderBy) =>
								setAttributes({ orderBy: newOrderBy })
							}
						/>
					</PanelRow>
					<PanelRow>
						<RadioControl
							label="Order"
							help="Order of posts, Ascending or Descending."
							selected={order}
							options={orderOptions}
							onChange={(newOrder) =>
								setAttributes({ order: newOrder })
							}
						/>
					</PanelRow>
				</PanelBody>
				<PanelBody
					title={__('Template Settings', 'post-list-with-load-more')}
				>
					<PanelRow>
						<RadioControl
							label="Layout"
							help="Layout of listing posts."
							selected={layout}
							options={layoutOptions}
							onChange={(newLayout) =>
								setAttributes({ layout: newLayout })
							}
						/>
					</PanelRow>
					{layout === 'grid' && (
						<PanelRow>
							<TextControl
								label="Grid Columns"
								type="number"
								help="Number of columns per each grid row."
								onChange={(newColumns) =>
									setAttributes({
										gridColumns: newColumns,
									})
								}
								min={1}
								max={12}
								value={gridColumns}
							/>
						</PanelRow>
					)}
					{layout === 'list' && (
						<PanelRow>
							<RadioControl
								label="List Layout"
								help="Layout for listing posts."
								selected={listLayout}
								options={listLayoutOptions}
								onChange={(newListLayout) =>
									setAttributes({ listLayout: newListLayout })
								}
							/>
						</PanelRow>
					)}
					<PanelRow>
						<RadioControl
							label="More Posts"
							help="How would you like to load more posts."
							selected={morePosts}
							options={morePostsOptions}
							onChange={(newMorePosts) =>
								setAttributes({ morePosts: newMorePosts })
							}
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<ServerSideRender block={metadata.name} attributes={attributes} />
		</div>
	);
}
