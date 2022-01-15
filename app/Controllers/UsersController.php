<?php

namespace App\Controllers;

use App\Models\User;
use Inc\Request\Request;
use Inc\Response\Response;
use Inc\Validation\CustomValidator as Validator;

class UsersController extends BaseController
{
    /**
     * @var User
     */
    protected $model;
    protected $request;
    protected $response;
    /**
     * UsersController constructor.
     *
     * @throws \ReflectionException
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new User();
        $this->request = new Request();
        $this->response = new Response();
    }
    /**
     * Store a new user in the database.
     */
    public function store()
    {
        $validator = new Validator();
        $rules = [
            'mail' => 'required | email | maxlength(155)',
            'name' => 'required | maxlength(155)',
            'password' => 'required | minlength(8) | maxlength(155) ',
        ];
        $validator->add($rules);
        $mail = $this->request->get('mail');
        $name = $this->request->get('name');
        $password = $this->request->get('password');
        $user = ['mail' => $mail, 'name' => $name, 'password' => $password];
        $isValid = $validator->validate($user);
        $data['errors'] = null;
        $data['data'] = null;
        if ($isValid) {
            $isExist =  $this->model->getByEmail($mail);
            if ($isExist) {
                $data['ok'] = false;
                $data['message'] = 'Fail';
                $data['errors']['mail'][] = 'Email already exist.';
                $statusCode = Response::HTTP_BAD_REQUEST;
            } else {
                $this->model->insert($user);
                $data['ok'] = true;
                $data['data']['user'] =  $user;
                $data['message'] = 'Success';
                $statusCode = Response::HTTP_OK;
            }
        } else {
            $data['ok'] = false;
            $data['message'] = 'Fail';
            $data['errors'] = $validator->getMessages();
            $statusCode = Response::HTTP_BAD_REQUEST;
        }
        return $this->response->responseJson($data, $statusCode);
    }
}
