<?php

namespace LightFCM;

/**
 * Simple Light and fast modular FMC(Firebase Cloud Messaging) notification for PHP.
 *
 * @license MIT License
 * @author Zulkifli Mahardhika
 * @link github.com/mahardhika21/LightFCM
 */

class LightFcm
{

    /**
     * FCM server key
     * @var string
     */
    private $serverKey;




    /**
     * Constructor
     * @param string $serverKey FCM server key
     */
    public function __construct($serverKey)
    {
        $this->serverKey = $serverKey;

    }

    /**
     * Send a message to the specified tokens.
     * @param \FCMSimple\Message $message Message object
     * @param array $tokens Array of the tokens of the devices to send to.
     * @return \FCMSimple\Response A response object regarding the send operation.
     */
    public function sendFcm($message, array $tokens)
    {

        $size = strlen(serialize($message));
        
        if($size > 4096){
            throw new \RuntimeException("Message size exceeds FCM limit. Maximum size is 4096 bytes.");
        }

        if ($message == null) {
            throw new \InvalidArgumentException("The message cannot be null.");
        }

        if ($tokens == null) {
            throw new \InvalidArgumentException("Tokens array cannot be null.");
        } else if (count($tokens) == 0) {
            throw new \InvalidArgumentException("Tokens array cannot be empty.");
        }

        // chunk tokens array to avoid arrays exceeding 1000 which is the limit defined by FCM
        $isSuccessful = true;
        $results = [];
        $chunks = array_chunk($tokens, 1000, false);
        foreach ($chunks as $chunk) {
            $httpResponse =  $this->lightCurl($this->serverKey, $message, $chunk);
            if($httpResponse[0] == 401) {
                return ['status' => false, 'message' => 'invalid server key'];
                break;
            } else {
                $isSuccessful = true;
                array_push($results, $httpResponse[1]);
            }
        }

        return ['status' => $isSuccessful, 'results' => $results];
    }

    /**
     * A utility function to execute post calls to FCM's send endpoint.
     *
     * @param string $serverKey FCM server key
     * @param \FCMSimple\Message $message The message
     * @param mixed $target A topic name or an array of device tokens
     * @return array array[0]: response code, array[1]: response body
     */
    private static function lightCurl($serverKey, $message, $target)
    {
        $ch = curl_init();

        // setup connection
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // https certification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //  header
        $headers = [
            "Authorization: key={$serverKey}",
            "Content-Type: application/json"
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // request body

        $messageBody = [
            "data"             => $message,
            "registration_ids" => $target,
        ];

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageBody));

        // execute call
        $httpResponse[1] = curl_exec($ch);
        $httpResponse[0] = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        curl_close($ch);

        return $httpResponse;
    }

}
