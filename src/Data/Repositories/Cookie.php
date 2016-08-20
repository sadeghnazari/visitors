<?php

namespace Blanfordia\Visitors\Data\Repositories;

use PragmaRX\Support\Config;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid as UUID;
use Illuminate\Cookie\CookieJar;

class Cookie extends Repository {

	private $config;

	private $request;

	private $cookieJar;

    public function __construct($model, Config $config, Request $request, CookieJar $cookieJar)
    {
        $this->config = $config;

        $this->request = $request;

        $this->cookieJar = $cookieJar;

        parent::__construct($model);
    }

    public function getId()
    {
        if ( ! $this->config->get('store_cookie_visitors'))
        {
            return null;
        }

        if ( ! $cookie = $this->request->cookie($this->config->get('visitors_cookie_name')))
        {
            $cookie = (string) UUID::uuid4();

            $this->cookieJar->queue($this->config->get('visitors_cookie_name'), $cookie, 0);
        }

        return $this->findOrCreate(array('uuid' => $cookie));
    }

}
