<?php

namespace App\Controllers;

use App\Models\PasswordResets;
use App\Models\User;
use Inc\Request\Request;
use Inc\Response\Response;
use Inc\Services\MailService;
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
            'email' => 'required | email | maxlength(155)',
            'name' => 'required | maxlength(155)',
            'password' => 'required | minlength(8) | maxlength(155)',
        ];
        $validator->add($rules);
        $email = $this->request->get('email');
        $name = $this->request->get('name');
        $password = $this->request->get('password');
        $user = ['email' => $email, 'name' => $name, 'password' => $password];
        $isValid = $validator->validate($user);
        $data['errors'] = null;
        $data['data'] = null;
        if ($isValid) {
            $isExist =  $this->model->getByEmail($email);
            if ($isExist) {
                $data['ok'] = false;
                $data['message'] = 'Fail';
                $data['errors']['email'][] = 'Email already exist.';
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
    
    public function resetPassword()
    {
        $validator = new Validator();
        $rules = [
            'email' => 'required | email ',
        ];
        $validator->add($rules);
        $email = $this->request->get('email');
        $params = ['email' => $email];
        $isValid = $validator->validate($params);
        $data['errors'] = null;
        $data['data'] = null;
        if ($isValid) {
            $isExist =  $this->model->getByEmail($email);
            if ($isExist) {
                $token = $this->generateResetPassword($email);
                $subject = 'Password Reset';
                $body = 'Hi<br>Use this token to reset your password <br>' . $token;
                $mailService = new MailService();
                $mailSent = $mailService->sendMail($email, $subject, $body);
                if ($mailSent) {
                    $data['ok'] = true;
                    $data['message'] = 'An email sent with token check it.';
                    $statusCode = Response::HTTP_OK;
                } else {
                    $data['ok'] = false;
                    $data['message'] = 'There is issue in sending mail, please try again later.';
                    $statusCode = Response::HTTP_BAD_REQUEST;
                }
            } else {
                $data['ok'] = false;
                $data['message'] = 'Fail';
                $data['errors']['email'][] = 'Email is not exist.';
                $statusCode = Response::HTTP_BAD_REQUEST;
            }
        } else {
            $data['ok'] = false;
            $data['message'] = 'Fail';
            $data['errors'] = $validator->getMessages();
            $statusCode = Response::HTTP_BAD_REQUEST;
        }
        return $this->response->responseJson($data, $statusCode);
    }

    public function setPassword()
    {
        $validator = new Validator();
        $rules = [
            'token' => 'required',
            'password' => 'required | minlength(8) | maxlength(155)',
        ];
        $validator->add($rules);
        $token = $this->request->get('token');
        $password = $this->request->get('password');
        $params = ['token' => $token, 'password' => $password];
        $isValid = $validator->validate($params);
        $data['ok'] = false;
        $data['message'] = 'Fail';
        $data['errors'] = null;
        $data['data'] = null;
        if ($isValid) {
            $PasswordResetsModel = new PasswordResets();
            $tokenObject =  $PasswordResetsModel->getByToken($token);
            if ($tokenObject) {
                $email = null;
                if (isset($tokenObject[0]) && $tokenObject[0]->email) {
                    $email = $tokenObject[0]->email;
                    $this->model->where(["email = '$email'"])->update(['password' => $password]);
                    $PasswordResetsModel = new PasswordResets();
                    $PasswordResetsModel->where(["email = '$email'"])->delete();
                }
                $data['ok'] = true;
                $data['message'] = 'Password was changed successfully.';
                $statusCode = Response::HTTP_OK;
            } else {
                $data['ok'] = false;
                $data['message'] = 'Token is not exist.';
                $statusCode = Response::HTTP_BAD_REQUEST;
            }
        } else {
            $data['ok'] = false;
            $data['message'] = 'Fail';
            $data['errors'] = $validator->getMessages();
            $statusCode = Response::HTTP_BAD_REQUEST;
        }
        return $this->response->responseJson($data, $statusCode);
    }

    protected function generateResetPassword($email = null)
    {
        $token = bin2hex(random_bytes(50));
        $PasswordResetsModel = new PasswordResets();
        $isExist =  $PasswordResetsModel->getByToken($token);
        if (!$isExist) {
            $record = ['email' => $email, 'token' => $token];
            $PasswordResetsModel->insert($record);
            return $token;
        } else {
            return  $this->generateResetPassword($email);
        }
        return null;
    }
}
