<?php

/**
 * Integrate a profile with various social networks.
 *
 * LICENSE:
 *
 * This source file is subject to the licensing terms that
 * is available through the world-wide-web at the following URI:
 * http://codecanyon.net/wiki/support/legal-terms/licensing-terms/.
 *
 * @copyright    Copyright ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â© 2009-2015 Jigowatt Ltd.
 * @license      http://codecanyon.net/wiki/support/legal-terms/licensing-terms/
 * @link         http://codecanyon.net/item/php-login-user-management/49008
 * @author       Jigowatt <info@jigowatt.co.uk>
 * @author       Matt Gates <info@mgates.me>
 * @package
 */


include_once 'generic.class.php';

class Jigowatt_integration extends Generic {

	public $enabledMethods;
	public static $socialLogin = array(
		'twitter',
		'facebook',
		'google',
		'yahoo'
	);

	private $result;
	private $num;


	/**
	 *
	 *
	 * @return unknown
	 */
	function __construct() {

		$this->enabledMethods = $this->findEnabledMethods();

		/** If the user is logged out, we'll treat them as a guest. */
		if ( empty( $_SESSION['intranet_clean']['user_id'] ) ) {
			//$this->guestLogin();
			return false;
		}

		$this->retrieveInfo();

		/** User wants to unlink his account from a social method. */
		if ( !empty( $_GET['unlink'] ) ) {
			$this->unlink( $_GET['unlink'] );
			$this->retrieveInfo();
		}

		if ( !empty( $_GET['link'] ) ) {
			$this->link_account( $_GET['link'] );
			$this->retrieveInfo();
		}

	}

	/**
	 * Check if this method is already in linked or not.
	 * @param unknown $method
	 * @return unknown
	 */
	public function isUsed( $method ) {

		return !empty( $this->result[$method] );

	}

	/**
	 *
	 */
	private function retrieveInfo() {

		$params = array( ':user_id' => $_SESSION['intranet_clean']['user_id'] );
		$sql = "SELECT * FROM `login_integration` WHERE `user_id` = :user_id;";
		$stmt = parent::query( $sql, $params );

		$this->num = $stmt->rowCount();
		$this->result = $stmt->fetch( PDO::FETCH_ASSOC );

	}

	/**
	 *
	 * @return unknown
	 */
	private function findEnabledMethods() {

		$methods = array();

		foreach ( self::$socialLogin as $method )
			if ( parent::getOption( 'integration-'.$method.'-enable' ) )
				$methods[] = $method;

			return $methods;

	}

	/**
	 *
	 * @param unknown $link
	 * @param unknown $login (optional)
	 * @return unknown
	 */
	public function link_account( $link, $login = false ) {

		/** Make sure we only allow specific social links. */
		if ( !in_array( $link, self::$socialLogin ) )
			return false;

		/** Check if user is already linked. */
		if ( !empty( $this->result[$link] ) ) {
			if ( ! $login ) {
				parent::displayMessage( sprintf( '<div class="alert alert-warning">' . _( 'Your account is already linked with %s!' ) . '</div>', ucwords( $link ) ), false );
			}
			return false;
		}

		require_once dirname(__FILE__) . '/integration/hybridauth/Hybrid/Auth.php';

		$config = array(
			"base_url" => SITE_PATH . "classes/integration/hybridauth/",

			"providers" => array (
				"OpenID" => array (
					"enabled" => true
				),
				"Facebook" => array (
					"enabled" => in_array( $link, self::$socialLogin ),
					"keys"    => array ( "id" => parent::getOption( 'facebook-app-id' ), "secret" => parent::getOption( 'facebook-app-secret' ) ),
					"trustForwarded" => false
				),
				"Twitter" => array (
					"enabled" => in_array( $link, self::$socialLogin ),
					"keys"    => array ( "key" => parent::getOption( 'twitter-key' ), "secret" => parent::getOption( 'twitter-secret' ) )
				),
			),

			"debug_mode" => false,
		);

		try{
			// create an instance for Hybridauth with the configuration file path as parameter
			$hybridauth = new Hybrid_Auth( $config );

			switch ( $link ) {

			case 'facebook' :
				$provider = $hybridauth->authenticate( "Facebook" );
				break;

			case 'twitter' :
				$provider = $hybridauth->authenticate( "Twitter" );
				break;

			case 'yahoo' :
				$provider = $hybridauth->authenticate( "OpenID", array( "openid_identifier" => "https://me.yahoo.com/" ) );
				break;

			case 'google' :
				$provider = $hybridauth->authenticate( "OpenID", array( "openid_identifier" => "https://www.google.com/accounts/o8/id" ) );
				break;

			}

			// get the user profile
			$provider_user_profile = $provider->getUserProfile();
			if ( !empty( $provider_user_profile ) ) {
				$_SESSION['intranet_clean'][$link . 'Misc'] = (array) $provider_user_profile;
				$_SESSION['intranet_clean'][$link] = $provider_user_profile->identifier;
			}

			/** See if the link was successful. */
			if ( !empty( $_SESSION['intranet_clean'][$link] ) ) {
				if ( !$login ) $this->connect( $link );
			}

			$provider->logout();
		}
		catch( Exception $e ) {
			// Display the recived error,
			// to know more please refer to Exceptions handling section on the userguide
			switch ( $e->getCode() ) {
			case 0 : parent::displayMessage( sprintf( '<div class="alert alert-warning">' . _( 'Unspecified error with: %s' ) . '</div>', ucwords( $link ) ), false ); break;
			case 1 : parent::displayMessage( sprintf( '<div class="alert alert-warning">' . _( 'Hybriauth configuration error: %s' ) . '</div>', ucwords( $link ) ), false ); break;
			case 2 : parent::displayMessage( sprintf( '<div class="alert alert-warning">' . _( 'Provider not properly configured: %s' ) . '</div>', ucwords( $link ) ), false ); break;
			case 3 : parent::displayMessage( sprintf( '<div class="alert alert-warning">' . _( 'Unknown or disabled provider: %s' ) . '</div>', ucwords( $link ) ), false ); break;
			case 4 : parent::displayMessage( sprintf( '<div class="alert alert-warning">' . _( 'Missing provider application credentials for: %s' ) . '</div>', ucwords( $link ) ), false ); break;
			case 5 : parent::displayMessage( sprintf( '<div class="alert alert-warning">' . _( 'Authentification failed. The user has canceled the authentication or the provider refused the connection. %s' ) . '</div>', ucwords( $link ) ), false ); break;
			case 6 : parent::displayMessage( sprintf( '<div class="alert alert-warning">' . _( 'User profile request failed. Most likely the user is not connected to the provider and he should authenticate again. %s' ) . '</div>', ucwords( $link ) ), false );
				$provider->logout();
				break;
			case 7 : parent::displayMessage( sprintf( '<div class="alert alert-warning">' . _( 'User not connected to the provider.: %s' ) . '</div>', ucwords( $link ) ), false );
				$provider->logout();
				break;
			case 8 : parent::displayMessage( sprintf( '<div class="alert alert-warning">' . _( 'Provider does not support this feature. %s' ) . '</div>', ucwords( $link ) ), false );
				break;
			}

			// well, basically your should not display this to the end user, just give him a hint and move on..
			// echo "<br /><br /><b>Original error message:</b> " . $e->getMessage();
		}

	}

	/**
	 *
	 * @param unknown $link
	 * @return unknown
	 */
	private function connect( $link ) {

		if ( empty( $_SESSION['intranet_clean']['user_id'] ) )
			return false;

		$params = array(
			':user_id'      => $_SESSION['intranet_clean']['user_id'],
			':session_link' => $_SESSION['intranet_clean'][$link]
		);

		if ( $this->num < 1 )
			$sql = "INSERT INTO `login_integration` (`user_id`, `$link`) VALUES (:user_id, :session_link);";
		else
			$sql = "UPDATE `login_integration` SET `$link` = :session_link WHERE `user_id` = :user_id;";

		parent::query( $sql, $params );
		parent::displayMessage( sprintf( '<div class="alert alert-success">%s</div>', _( 'Successfully linked with ' . ucwords( $link ) ) ), false );

	}

	/**
	 *
	 * @param unknown $provider
	 * @return unknown
	 */
	private function unlink( $provider ) {

		if ( !in_array( $provider, self::$socialLogin ) )
			return false;

		if ( empty( $this->result[$provider] ) ) {
			parent::displayMessage( sprintf( '<div class="alert alert-warning">' . _( 'You are not yet linked with %s' ) . '</div>', ucwords( $provider ) ), false );
			return false;
		}

		$params = array( ':user_id'  => $_SESSION['intranet_clean']['user_id'] );
		$sql = "UPDATE `login_integration` SET $provider = null WHERE `user_id` = :user_id;";
		parent::query( $sql, $params );

		unset( $_SESSION['intranet_clean'][$provider] );

		parent::displayMessage( sprintf( '<div class="alert alert-success">' . _( 'Successfully unlinked from %s' ) . '</div>', ucwords( $provider ) ), false );

	}

}

$jigowatt_integration = new Jigowatt_integration();
