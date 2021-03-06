<?php

namespace BeSimple\SsoAuthBundle\Tests\Controller\Server;

use Symfony\Component\HttpFoundation\Request;
use BeSimple\SsoAuthBundle\Tests\Form\Login;

class CasController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $credentials
     * @return string
     */
    protected function getLoginRedirectUrl(Request $request, Login $login)
    {
        $url = urldecode($request->query->get('service'));

        return sprintf('%s?ticket=%s', $url, $login->getCredentials());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return string
     */
    protected function getLogoutRedirectUrl(Request $request)
    {
        return urldecode($request->query->get('service'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return string
     */
    protected function getCredentials(Request $request)
    {
        return $request->query->get('ticket');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $view
     * @return string
     */
    protected function getValidationView(Request $request, $name)
    {
        return $request->attributes->get('version') === 2
            ? sprintf('cas/%s_v2.xml.twig', $name)
            : sprintf('cas/%s_v1.txt.twig', $name);
    }
}