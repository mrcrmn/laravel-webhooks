<?php

namespace mrcrmn\Webhook\Adapter;

use mrcrmn\Webhook\Adapter\WebhookService;

class GithubAdapter extends WebhookService
{
    /**
     * Verifies the headers signature.
     * 
     * Github supplies us with a hashed string in its header.
     * We use the request body and our secret to try to recreate the hash
     * and verify the requests integrity.
     *
     * @return bool
     */
    public function verifySignature()
    {
        if (is_null(config('webhook.secret', null)) && is_null($this->request->header('x-hub-signature', true))) {
            return true;
        }

        list($algo, $hash) = explode('=', $this->request->header('x-hub-signature'), 2);
        return $hash === hash_hmac($algo, $this->request->getContent(), config('webhook.secret'));
    }

    /**
     * Checks if the webhooks targets the correct repository and name.
     *
     * @return bool
     */
    public function verifyRepository()
    {
        $payload = $this->getPayload();

        // Check if the repository name matches the given name in the config file,
        // as well as the branch name which defaults to 'master'.
        return $payload['repository']['full_name'] === config('webhook.repository')
               && str_replace('refs/heads/', '', $payload['ref']) === config('webhook.branch');
    }

    /**
     * Gets the event name from the header.
     *
     * @return string
     */
    public function getEvent()
    {
        return $this->request->header('x-github-event');
    }

    /**
     * Gets the payload.
     *
     * @return array
     */
    public function getPayload()
    {
        if ($this->request->header('Content-Type') === 'application/json') {
            return json_decode($this->request->getContent(), true);
        }

        return $this->request->input('payload');
    }
}