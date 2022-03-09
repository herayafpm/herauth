<?php

namespace Raydragneel\Herauth\Controllers;

use CodeIgniter\Config\Services;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Validation\Exceptions\ValidationException;
use Psr\Log\LoggerInterface;

// Require app/Common.php file if exists.
if (is_file(__DIR__ . '/../Common.php')) {
    require_once __DIR__ . '/../Common.php';
}

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
class BaseHerauthController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['herauth_main'];

    /**
     * Constructor.
     */
    protected $data = [];
    protected $root_view = '';
    protected $_account = null;
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $this->data['__app_name'] = 'Herauth';
        $this->data['_main_path'] = $this->root_view;
        $this->data['__locale'] = $request->getLocale() ?? 'id';
        $this->data['__locale_list'] = config('App')->supportedLocales ?? ['id','en'];
        $this->session = session();
        $url = current_url();
        $this->data['url'] = $url;
        $this->_account = service('herauth')->getAccount();
        $this->data['_account'] = $this->_account;
        $this->data['_session'] = $this->session;

        $this->setModel();

    }

    public function view($view,$data = [])
    {
        $data = array_merge($this->data,$data);
        // return view($this->root_view.$view,$data,['cache' => 3600, 'cache_name' => str_replace('/','_',$this->root_view.$view)]);
        return view($this->root_view.$view,$data);
    }

    protected function validate($rules, array $messages = []): bool
    {
        $this->validator = Services::validation();
        // If you replace the $rules array with the name of the group
        if (is_string($rules)) {
            $validation = config('Validation');

            // If the rule wasn't found in the \Config\Validation, we
            // should throw an exception so the developer can find it.
            if (!isset($validation->$rules)) {
                throw ValidationException::forRuleNotFound($rules);
            }

            // If no error message is defined, use the error message in the Config\Validation file
            if (!$messages) {
                $errorName = $rules . '_errors';
                $messages  = $validation->$errorName ?? [];
            }

            $rules = $validation->$rules;
        }
        $data = $this->getDataRequest();
        return $this->validator->setRules($rules, $messages)->run((array)$data);
    }
    protected function getDataRequest($filtering = true)
    {
        $request = $this->request;
        /** @var IncomingRequest $request */
        if (strpos($request->getHeaderLine('Content-Type'), 'application/json') !== false) {
            $data = $request->getJSON(true);
        }else{
            if (
                in_array($request->getMethod(), ['put', 'patch', 'delete'], true)
                && strpos($request->getHeaderLine('Content-Type'), 'multipart/form-data') === false
            ) {
                $data = $request->getRawInput();
            } else {
                $data = $request->getVar() ?? [];
            }
        }
        $data = (array) array_merge((array)$data, $request->getFiles() ?? []);
        if($filtering){
            return $this->filteringData($data);
        }else{
            return $data;
        }
    }
    protected function filteringData($data)
    {
        foreach ($data as &$value) {
            if (is_string($value)) {
                $value = htmlspecialchars($value, true);
            }
        }
        unset($value);
        return $data;
    }

    public function setModel()
    {
        if(isset($this->modelName)){
            if(!empty($this->modelName)){
                $this->model = model($this->modelName);
            }
        }
    }

}
