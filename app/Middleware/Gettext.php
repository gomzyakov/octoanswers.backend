<?php

/**
 * Middleware invokable class (dont use closure), who prepare locale for gettext.
 *
 * @var string
 */
class Gettext_Middleware
{
    /**
     * Get, check and prepare locale for gettext.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        // Prepare domain (used as .po filename)
        $domain = 'main';

        // Get language from URL
        $routeParams = $request->getAttribute('routeInfo')[2];
        $lang = $routeParams['lang'];

        // Get locale for lang
        $locale = self::getLocaleForLanguage($lang);

        // Set language
        $results = putenv("LC_ALL=$locale");
        if (!$results) {
            exit ('putenv failed');
        }

        // Set locale
        $results = setlocale(LC_ALL, $locale);
        if (!$results) {
            exit ('setlocale failed: locale function is not available on this platform, or the given local does not exist in this environment');
        }

        // Specify location of translation tables
        $full_domain_path = bindtextdomain($domain, ROOT_PATH.'/app/Lang');
        if (!$results) {
            exit ('new text domain is set: ' . $full_domain_path);
        }

        bind_textdomain_codeset($domain, 'UTF-8');

        // Choose domain
        $results = textdomain($domain);
        if (!$results) {
            exit ('textdomain failed: domain is set to: '. $domain);
        }

        $response = $next($request, $response);

        return $response;
    }

    /**
     * Get locale for language
     *
     * Example: en => en_US.utf8, ru => ru_RU.utf8
     *
     * @param string $lang
     * @return return string
     */
    public static function getLocaleForLanguage(string $lang): string
    {
        if ($lang == 'ru') {
            return 'ru_RU.utf8';
        }

        return 'en_US.utf8';
    }
}