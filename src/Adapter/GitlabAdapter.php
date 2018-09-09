<?php

namespace mrcrmn\Webhook\Adapter;

use mrcrmn\Webhook\Adapter\WebhookService;

class GitlabAdapter extends WebhookService
{
    /**
     * Verifies the headers signature.
     * 
     * Gitlab simply lets us set a secret token which it passes through a header.
     * It is unencrypted and unhashed, so we simply need to check if it is equal
     * to the string we set in the config file.
     *
     * @return bool
     */
    public function verifySignature()
    {
        return $this->request->header('x-gitlab-token', null) === config('webhook.secret', null);
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
        return $payload['project']['path_with_namespace'] === config('webhook.repository')
            && str_replace('refs/heads/', '', $payload['ref']) === config('webhook.branch');
    }

    /**
     * Gets the event name from the header.
     *
     * @return string
     */
    public function getEvent()
    {
        return $this->getPayload()['object_kind'];
    }

    /**
     * Gets the payload.
     *
     * @return array
     */
    public function getPayload()
    {
        return json_decode($this->request->getContent(), true);
    }
}