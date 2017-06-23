<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 22.06.17
 * Time: 12:12
 */

namespace App\Http\Library;
use Illuminate\Http\Request;

class Facebook
{
    private $fb;
    private $helper;


    public function __construct()
    {
        $this->fb = new \Facebook\Facebook(config('facebook'));

        $this->helper = $this->fb->getRedirectLoginHelper();
    }


    public function getUri()
    {

        $permissions = ['email', 'user_friends'];
        $loginUrl = $this->helper->getLoginUrl('http://test.loc/fb-callback', $permissions);

        return $loginUrl;

    }

    public function fb_callback(Request $request)
    {
        if ($request->input('state'))
            $this->helper->getPersistentDataHandler()->set('state', $request->input('state'));

        try {

            $accessToken = $this->helper->getAccessToken();

        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (!isset($accessToken)) {
            if ($this->helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $this->helper->getError() . "\n";
                echo "Error Code: " . $this->helper->getErrorCode() . "\n";
                echo "Error Reason: " . $this->helper->getErrorReason() . "\n";
                echo "Error Description: " . $this->helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        // Logged in

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $this->fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);

        //dd($tokenMetadata);

        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId('142662252966652'); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (!$accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $this->helper->getMessage() . "</p>\n\n";
                exit;
            }
        }

        $request->session()->put('fb_access_token', (string)$accessToken);

    }

    public function getUser($accessToken)
    {
        try {
            // Get the \Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.
            $response = $this->fb->get('/me?fields=id,name,email', $accessToken);
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $me = $response->getGraphUser();
        return $me;
    }

    public function postOnWall($title, $image, $accessToken)
    {


        $data = [
            'message' => $title,
            'source' => $this->fb->fileToUpload($image),
        ];

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $this->fb->post('/me/photos', $data, $accessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $graphNode = $response->getGraphNode();
        echo($graphNode);

    }

    public function postLinkOnWall($title, $link, $accessToken)
    {


        $data = [
            'message' => $title,
            'link' => $link,
        ];

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $this->fb->post('/me/feed', $data, $accessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $graphNode = $response->getGraphNode();
        echo($graphNode);

    }

}