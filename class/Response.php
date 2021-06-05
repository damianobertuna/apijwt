<?php


class Response
{
    public function __construct()
    {
        $this->status   = "";
        $this->message  = "";
        $this->token    = "";
        $this->resource = "";
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }

    /**
     * @param string $resource
     */
    public function setResource(string $resource)
    {
        $this->resource = $resource;
    }

    public function getStructure() {
        return array(
            'response' => array(
                'status'    => $this->status,
                'message'   => $this->message,
                'token'     => $this->token,
                'resource'  => $this->resource
            )
        );
    }

    public function toJson()
    {
        return json_encode($this->getStructure());
    }
}