<?php

namespace Toadsuck\Core;

use Toadsuck\Core\Template;
use Toadsuck\Core\Config;
use Toadsuck\Core\Input;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;

class Controller
{
	public $plates = null;
	public $template = null;
	public $config = null;
	public $request = null;
	public $ds = DIRECTORY_SEPARATOR;

	public function __construct($opts = [])
	{
		// Where is our app's source code?
		$app_dir = array_key_exists('app_dir', $opts) ? $opts['app_dir'] : null;

		// Set up configs.
		$this->initializeConfig($app_dir);

		// Set up the template engine.
		$this->initializeTemplate();

		// Setup our HTTP Request object.
		$this->initializeRequest();

		// Initialize the Session.
		$this->initializeSession();
	}

	/**
	 * Set up the configuration manager.
	 * @param string $app_dir Filesystem path to the config directory
	 */
	public function initializeConfig($app_dir = null)
	{
		$this->config = new Config($app_dir);
	}

	/**
	 * Set up the template system
	 * @param string $directory Filesystem path to the views directory.
	 */
	public function initializeTemplate($directory = null)
	{
		if (empty($directory)) {
			$directory = $this->config->resolvePath('views');
		}

		$this->template = new Template($directory);
	}

	/**
	 * Get info about the HTTP Request
	 */
	public function initializeRequest($request = null)
	{
		if (empty($request)) {
			$this->request = Request::createFromGlobals();
		} else {
			$this->request = $request;
		}

		// Shortcuts to the request object for cleaner syntax.
		$this->input = new Input($this->request);
	}

	/**
	 * Initialize the session.
	 *
	 * This is something you might want to override in your controller so you can
	 * redirect to a page with a message about being logged out after detecting the session has expired.
	 *
	 */
	protected function initializeSession($session_expiration = null)
	{
		// Setup the session
		$this->session = new Session();

		$this->config->load('config');

		// We need a unique session name for this app. Let's use last 10 characters the file path's sha1 hash.
		try {
			$this->session->setName('TSAPP' . substr(sha1(__FILE__), -10));
			$this->session->start();

			// Default session expiration 1 hour.
			// Can be overridden in method param or by setting session_expiration in config.php
			$session_expiration = !empty($session_expiration)
										? $session_expiration
										: $this->config->get('session_expiration', 3600);

			// Is this session too old?
			if (time() - $this->session->getMetadataBag()->getLastUsed() > $session_expiration) {
				$this->session->invalidate();
			}
		} catch (\LogicException $e) {
			// Session already active, can't change it now!
		}
	}

	/**
	 * Internal or External Redirect to the specified url
	 */
	public function redirect($url, $query = [], $is_query_string = false)
	{
		if (!empty($query)) {
			if ($is_query_string && is_array($query)) {
				# home/people?lastname=smith
				$url = rtrim($url, DIRECTORY_SEPARATOR) . '?' . http_build_query($query);
			} elseif (is_array($query)) {
				# home/people/1,2
				$url = rtrim($url, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . join(',', $query);
			} else {
				# home/people/1
				$url = rtrim($url, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . urlencode($query);
			}
		}

		if (!preg_match('/^http/', $url)) {
			// Internal redirect
			$url = DIRECTORY_SEPARATOR . trim($url, DIRECTORY_SEPARATOR);
			$url = $this->template->uri($url);
		}

		/**
		 * You MUST call session_write_close() before performing a redirect to ensure the session is written,
		 * otherwise it might not happen quickly enough to save your session changes.
		 */
		session_write_close();

		$response = new RedirectResponse($url);
		$response->send();
	}

	public function json($content = [])
	{
		$response = new JsonResponse();
		$response->setData($content);
		$response->send();
	}

	public function jsonp($content = [], $jsonCallback = 'callback')
	{
		$response = new JsonResponse();
		$response->setData($content);
		$response->setCallback($jsonCallback);
		$response->send();
	}

	public function __call($method = null, $args = null)
	{
		// Send a 404 for any methods that don't exist.
		$response = new Response('Not Found', 404, ['Content-Type' => 'text/plain']);
		$response->send();
	}
}
