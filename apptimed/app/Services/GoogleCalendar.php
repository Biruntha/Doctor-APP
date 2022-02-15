<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_ConferenceData;
use Google_Service_Calendar_CreateConferenceRequest;
use Google_Service_Calendar_EventDateTime;

use App\Models\Setting;
use App\Models\Appointment;
use stdClass;
use Log;

class GoogleCalendar {
    protected $client;
    
    public function getClient()
    {
        try {            
            $access_token = Setting::where('s_key', 'calender_api_access_token')->first();
            $refresh_token = Setting::where('s_key', 'calender_api_refresh_token')->first();
            $expires_in = Setting::where('s_key', 'calender_api_expires_in')->first();
            $client_id = Setting::where('s_key', 'calender_api_client_id')->first();
            $client_secret = Setting::where('s_key', 'calender_api_client_secret')->first();
            $redirect_uri = Setting::where('s_key', 'calender_api_redirect_uri')->first();
            $accessToken = null;
            $refreshToken = null;
            $expiresIn = null;
            $clientId = null;
            $clientSecret = null;
            $redirectUri = null;
            if (is_null($access_token) or empty($access_token) or is_null($refresh_token) or empty($refresh_token) or is_null($expires_in) or empty($expires_in)
                or is_null($client_id) or empty($client_id) or is_null($client_secret) or empty($client_secret) or is_null($redirect_uri) or empty($redirect_uri)) {
                $response["status"] = "failed";
                $response["msg"] = "Invalid Accesstoken/Refreshtoken/expires_in/client_id/client_secret/redirect_uri value";
                return $response;
            } else {
                $accessToken = $access_token->s_value;
                $refreshToken = $refresh_token->s_value;
                $expiresIn = $expires_in->s_value;
                $clientId = $client_id->s_value;
                $clientSecret = $client_secret->s_value;
                $redirectUri = $redirect_uri->s_value;
            }
            
            $client = new Google_Client();
            $client->setApplicationName('Aptimed Google Calendar');
            $client->setAccessType("offline");
            $client->setClientId($clientId);
            $client->setClientSecret($clientSecret);
            $client->setRedirectUri($redirectUri);
            $client->setIncludeGrantedScopes(true); 
            $client->addScope(Google_Service_Calendar::CALENDAR);
        
            $token_str = json_decode('{"access_token":"'.$accessToken.'","expires_in":'.$expiresIn.',"scope":"https:\/\/www.googleapis.com\/auth\/calendar.events https:\/\/www.googleapis.com\/auth\/calendar","token_type":"Bearer","refresh_token":"'.$refreshToken.'"}', true);
            $client->setAccessToken($token_str);

            if ($client->isAccessTokenExpired()) {
                $refresh__token = $client->getRefreshToken();
                if(!empty($refresh__token) && !is_null($refresh__token)){
                    $client->fetchAccessTokenWithRefreshToken($refresh__token);    
                    $token = $client->getAccessToken();
                    file_put_contents("token.json", json_encode($token));
                    $access_token->s_value = $token['access_token'];
                    $access_token->save();
                    $refresh_token->s_value = $token['refresh_token'];
                    $refresh_token->save();
                    $expires_in->s_value = $token['expires_in'];
                    $expires_in->save();
                }
            }
            
            $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
            $client->setHttpClient($guzzleClient);
            $this->client = $client;

            $response["status"] = "success";
            $response["msg"] = "Successfully configured";
            return $response;
        } catch(\Exception $e) {
            $response["status"] = "failed";
            $response["msg"] = "Error while scheduling the meeting";
            return $response;
        } catch(\Throwable $e) {
            $response["status"] = "failed";
            $response["msg"] = "Error while scheduling the meeting";
            return $response;
        }
    }

    public function createEvent($appointment_id) {
        try {
            $client = $this->client;
            $service = new Google_Service_Calendar($client);
            $calendarId = 'primary';
            
            $event = new Google_Service_Calendar_Event([
                'summary' => 'Test Event',
                'description' => 'A chance to hear more about Google\'s developer products.',
                'start' => ['dateTime' => '2021-10-04T09:00:00-07:00'],
                'end' => ['dateTime' => '2021-10-04T09:30:00-07:00'],
                'reminders' => ['useDefault' => true],
            ]);
            //The most important part about creating hangout
            $conferenceData = new Google_Service_Calendar_ConferenceData(); //defining we have conference
            $req = new Google_Service_Calendar_CreateConferenceRequest(); //requesting google to create video meeting
            $req->setRequestId("req_".time()); //some random string/number changed every call. according to docs subsequent calls with the same request id are ignored
            $conferenceData->setCreateRequest($req); //set request for videoconference in conference data
            $event->setConferenceData($conferenceData); //set conference data in out event


            $results = $service->events->insert($calendarId, $event, ['conferenceDataVersion' => 1]);
            if (is_null($results) or empty($results)) {
                $response["status"] = "failed";
                $response["msg"] = "Error while scheduling the meeting";
                return $response;
            }
            
            $appointment = Appointment::find($appointment_id);
            $appointment->hangout_link = $results->hangoutLink;
            $appointment->save();

            $response["status"] = "success";
            $response["msg"] = "Successfully created";
            return $response;
        } catch(\Exception $e) {
            $response["status"] = "failed";
            $response["msg"] = "Error while scheduling the meeting";
            return $response;
        } catch(\Throwable $e) {
            $response["status"] = "failed";
            $response["msg"] = "Error while scheduling the meeting";
            return $response;
        }
    }
}