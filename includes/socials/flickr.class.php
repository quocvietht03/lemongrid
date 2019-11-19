<?php
/**
* LG_Flickr PHP
* @author BEARS Themes <bearsthemes@gmail.com>
* @license http://opensource.org/licenses/mit-license.php The MIT License
*/

class LG_Flickr
{
	public $username 	= '';
	public $key 		= '';
	public $page 		= 1;
	public $slice 		= 9;
	public $uri_flickr 	= 'https://api.flickr.com/services/rest/?';

	function __construct()
	{

	}

	/**
	 * getMedia
	 */
	function getMedia()
	{
		$userInfo = $this->getUserID(); 
        $user_id = $userInfo['user_id'];
        $username = $userInfo['username'];

		$params = array(
			'method' 			=> 'flickr.people.getPhotos',
			'api_key' 			=> $this->key,
			'user_id' 			=> $user_id,
			'format' 			=> 'json',
			'nojsoncallback' 	=> 1,
			'page'				=> $this->page,
			'per_page'			=> $this->slice
			);
		
		$uri_request = $this->uri_flickr . http_build_query( $params );

		$remote = wp_remote_get( $uri_request );

		if ( is_wp_error($remote) )
            return new WP_Error( 'site_down', __( 'Unable to communicate with Flickr.', 'bearsthemes' ) );

        if ( 200 != wp_remote_retrieve_response_code( $remote ) )
            return new WP_Error( 'invalid_response', __( 'Flickr did not return a 200.', 'bearsthemes') );
		
        $flickr_array = json_decode( $remote['body'], TRUE );

        if ( ! $flickr_array )
            return new WP_Error( 'bad_json', __( 'Flickr has returned invalid data.', 'bearsthemes' ) );

        $datas = $flickr_array['photos']['photo'];

        $flickr = array();
        foreach( $datas as $data ):
        	$data['link'] 	= sprintf( 'https://www.flickr.com/photos/%s/%s', $user_id, $data['id'] );
        	$data['photo'] 	= sprintf( 'https://farm%s.staticflickr.com/%s/%s_%s.jpg', $data['farm'], $data['server'], $data['id'], $data['secret'] );

        	$data_item = array(
        			'api_key'		=> $this->key,
        			'username'		=> $username,
                    'link'          => $data['link'],
                    'photo'         => $data['photo'],
                );

        	array_push( $flickr, array_merge( $data, $data_item ) );
        endforeach;

        return array_slice( $flickr, 0, $this->slice );
	}

	/**
	 * 
	 */
	public static function getInfo( $key, $photoID, $secret ) {
		$params = array(
			'method' 			=> 'flickr.photos.getInfo',
			'api_key' 			=> $key,
			'photo_id' 			=> $photoID,
			'secret'			=> $secret,
			'format' 			=> 'json',
			'nojsoncallback' 	=> 1
			);

		$uri_request = 'https://api.flickr.com/services/rest/?' . http_build_query( $params );

		$get = wp_remote_get( $uri_request );

		$data = json_decode( $get['body'] );
		
		$data->photo->isfavorite = lgCustomNumberFormat( $data->photo->isfavorite );
		$data->photo->comments->_content = lgCustomNumberFormat( $data->photo->comments->_content );
		$data->photo->dateuploaded = lgElapsedTimeString( date( 'Y-m-d H:i:s', $data->photo->dateuploaded ) );

		return $data;
	}

	/**
	 * getUserID
	 */
	function getUserID()
	{
		$params = array(
			'method' 			=> 'flickr.people.findByUsername',
			'api_key' 			=> $this->key,
			'username' 			=> $this->username,
			'format' 			=> 'json',
			'nojsoncallback' 	=> 1
			);

		$uri_request = $this->uri_flickr . http_build_query( $params );

		$get = wp_remote_get( $uri_request );
		
		if ( is_wp_error($get) )
            return new WP_Error( 'site_down', __( 'Unable to communicate with Flickr.', 'bearsthemes' ) );

        if ( 200 != wp_remote_retrieve_response_code( $get ) )
            return new WP_Error( 'invalid_response', __( 'Flickr did not return a 200.', 'bearsthemes') );

        $data = json_decode( $get['body'] );

        return ( $data->user->id ) ? array( 'user_id' => $data->user->id, 'username' => $data->user->username->_content ) : array();
	}
}