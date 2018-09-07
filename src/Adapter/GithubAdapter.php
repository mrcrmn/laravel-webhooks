<?php

namespace mrcrmn\Webhook\Adapter;

use mrcrmn\Webhook\Adapter\WebhookService;

class GithubAdapter extends WebhookService
{
    public function verifySignature()
    {
        list($algo, $hash) = explode('=', $this->request->header('x-hub-signature'), 2);

        return $hash === hash_hmac($algo, $this->request->getContent(), config('webhook.secret'));
    }

    public function getEvent()
    {
        return $this->request->header('x-github-event');
    }

    public function getPayload()
    {
        return $this->request->input('payload');
    }
}