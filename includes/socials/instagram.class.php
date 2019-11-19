<?php
/**
* LG_Instagram PHP
* @author BEARS Themes <bearsthemes@gmail.com>
* @license http://opensource.org/licenses/mit-license.php The MIT License
*/

class LG_Instagram
{
	public $username 	= '';
	public $client_id 	= '';
	public $slice 		= 9;

	function __construct(  )
	{
		
	}
	
	/**
	 * scrape_instagram
	 *
	 * @param string $username
	 * @param string $api
	 * @param int $slice
	 */
	function getMedia() 
	{
		$userInfo = $this->getInstaID(); 
		if( isset( $userInfo->errors ) ) return;
        $id = $userInfo['id'];
        $full_name = $userInfo['full_name'];
		
		$remote = wp_remote_get( "https://api.instagram.com/v1/users/".$id."/media/recent/?client_id=".$this->client_id."&count=".$this->slice, true );

		if (is_wp_error($remote))
            return new WP_Error( 'site_down', __( 'Unable to communicate with Instagram.', 'bearsthemes' ) );

        if ( 200 != wp_remote_retrieve_response_code( $remote ) )
            return new WP_Error( 'invalid_response', __( 'Instagram did not return a 200.', 'bearsthemes' ) );
        
        $insta_array = json_decode( $remote['body'], TRUE );

        if ( ! $insta_array )
            return new WP_Error( 'bad_json', __( 'Instagram has returned invalid data.', 'bearsthemes' ) );

        $datas = $insta_array['data'];
        
        $instagram = array();
        foreach ( $datas as $data ) {
            if ( $data['user']['username'] == $this->username ) {

                $data['link']                          = preg_replace( "/^http:/i", "", $data['link'] );
                $data['images']['standard_resolution'] = preg_replace( "/^http:/i", "", $data['images']['standard_resolution'] );

                $data_item = array(
                    'author_id'     => $id,
                    'full_name'     => $full_name,
                    'description'   => $data['caption']['text'],
                    'link'          => $data['link'],
                    'time'          => lgElapsedTimeString( date( 'Y-m-d H:i:s', $data['created_time'] ) ),
                    'comments'      => lgCustomNumberFormat( $data['comments']['count'] ),
                    'likes'         => lgCustomNumberFormat( $data['likes']['count'] ),
                    'photo'         => $data['images']['standard_resolution']['url'],
                    'type'          => $data['type']
                );

                if( $data['type'] == 'video' )
                	$data_item['video'] = $data['videos']['standard_resolution']['url'];

            	array_push( $instagram, $data_item );
            }
        }

        return array_slice( $instagram, 0, $this->slice );
	}
	
	/**
	 * getInstaID
	 *
	 * @param string $username
	 * @param int $client_id
	 */
	function getInstaID()
	{
		$username = strtolower( $this->username ); // sanitization
        $url = "https://api.instagram.com/v1/users/search?q=".$username."&client_id=".$this->client_id;
        $get = wp_remote_get( $url );
        if ( is_wp_error($get) )
            return new WP_Error( 'site_down', __( 'Unable to communicate with Instagram.', 'bearsthemes' ) );

        if ( 200 != wp_remote_retrieve_response_code( $get ) )
            return new WP_Error( 'invalid_response', __( 'Instagram did not return a 200.', 'bearsthemes') );
        $json = json_decode( $get['body'] );
        
        foreach($json->data as $user)
        {
            if($user->username == $username)
            {
                return array( 'id' => trim( $user->id ), 'full_name' => trim( $user->full_name ) );
            }
        }

        return '00000000'; // return this if nothing is found
	}
}