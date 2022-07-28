
function ocdi_register_plugins( $plugins ) {
	$theme_plugins = [
	  [ // A WordPress.org plugin repository example.
		'name'     => 'Advanced Custom Fields', // Name of the plugin.
		'slug'     => 'advanced-custom-fields', // Plugin slug - the same as on WordPress.org plugin repository.
		'required' => true,                     // If the plugin is required or not.
	  ],
	  [ // A locally theme bundled plugin example.
		'name'     => 'Some Bundled Plugin',
		'slug'     => 'bundled-plugin',         // The slug has to match the extracted folder from the zip.
		'source'   => get_template_directory_uri() . '/bundled-plugins/bundled-plugin.zip',
		'required' => false,
	  ],
	  [
		'name'        => 'Self Hosted Plugin',
		'description' => 'This is the plugin description',
		'slug'        => 'self-hosted-plugin',  // The slug has to match the extracted folder from the zip.
		'source'      => 'https://example.com/my-site/self-hosted-plugin.zip',
		'preselected' => true,
	  ],
	];
   
	return array_merge( $plugins, $theme_plugins );
  }
  add_filter( 'ocdi/register_plugins', 'ocdi_register_plugins' );

  function ocdi_import_files() {
	return [
	  [
		'import_file_name'           => 'Demo Import 1',
		'categories'                 => [ 'Category 1' ],
		'import_file_url'            => 'https://raw.githubusercontent.com/sknetking/social/main/demodata.xml',
		//'import_widget_file_url'     => 'http://www.your_domain.com/ocdi/widgets.json',
		//'import_customizer_file_url' => 'http://www.your_domain.com/ocdi/customizer.dat',
		// 'import_redux'               => [
		//   [
		// 	'file_url'    => 'http://www.your_domain.com/ocdi/redux.json',
		// 	'option_name' => 'redux_option_name',
		//   ],
		//],
		'import_preview_image_url'   => 'https://i.ibb.co/QYs350L/screenshot.png',
		'preview_url'                => 'https://v2websolutions.com/',
	  ]
	  
	];
  }
  add_filter( 'ocdi/import_files', 'ocdi_import_files' );

 
	 


  function wpb_admin_notice_warn() {
	if(!is_plugin_active("one-click-demo-import/one-click-demo-import.php")):
	echo '<div class="notice notice-warning is-dismissible">
		  <p>Download The Plugin  <a href="http://localhost/bizix/wp-admin/plugins.php?_wpnonce=349f760fdc&action=activate&plugin=one-click-demo-import/one-click-demo-import.php">One Click Import Demo install </a></p>
		  </div>'; 
	endif;
		}

	add_action( 'admin_notices', 'wpb_admin_notice_warn' );


add_filter( 'plugin_row_meta', 'finegap_get_plugin_string', 99, 4); 

function finegap_get_plugin_string($plugin_meta, $plugin_file, $plugin_data, $status ) {

 echo '<b>' . $plugin_file . '</b>';
  return $plugin_meta;

}
