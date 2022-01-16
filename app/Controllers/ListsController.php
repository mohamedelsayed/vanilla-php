<?php

namespace App\Controllers;

use App\Models\Item;
use App\Models\ListModel;
use Inc\Request\Request;
use Inc\Response\Response;
use Inc\Validation\CustomValidator as Validator;

class ListsController extends BaseController
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
        $this->model = new ListModel();
        $this->request = new Request();
        $this->response = new Response();
    }

    /**
     * Store a new list in the database.
     */
    public function store()
    {
        $validator = new Validator();
        $rules = [
            'name' => 'required | maxlength(155)',
        ];
        $validator->add($rules);
        $name = $this->request->get('name');
        $params = ['name' => $name];
        $isValid = $validator->validate($params);
        $data['errors'] = null;
        $data['data'] = null;
        if ($isValid) {
            $this->model->insert($params);
            $data['ok'] = true;
            $data['data']['list'] =  $params;
            $data['message'] = 'Success';
            $statusCode = Response::HTTP_OK;
        } else {
            $data['ok'] = false;
            $data['message'] = 'Fail';
            $data['errors'] = $validator->getMessages();
            $statusCode = Response::HTTP_BAD_REQUEST;
        }
        return $this->response->responseJson($data, $statusCode);
    }

    /**
     * update a list in the database.
     */
    public function update()
    {
        $validator = new Validator();
        $rules = [
            'name' => 'required | maxlength(155)',
            'id' => 'required | Integer',
        ];
        $validator->add($rules);
        $name = $this->request->get('name');
        $id = $this->request->get('id');
        $params = ['name' => $name, 'id' => $id];
        $isValid = $validator->validate($params);
        $data['errors'] = null;
        $data['data'] = null;
        if ($isValid) {
            $list = $this->model->getById($id);
            if ($list) {
                $listModel = new ListModel();
                $listModel->where(["id = $id"])->update(['name' => '"' . $name . '"']);
                $data['ok'] = true;
                $data['data']['list'] =  $params;
                $data['message'] = 'Success';
                $statusCode = Response::HTTP_OK;
            } else {
                $data['ok'] = false;
                $data['message'] = 'List is not exist.';
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

    /**
     * delete a list from the database.
     */
    public function destory()
    {
        $validator = new Validator();
        $rules = [
            'id' => 'required | Integer',
        ];
        $validator->add($rules);
        $id = $this->request->get('id');
        $params = ['id' => $id];
        $isValid = $validator->validate($params);
        $data['errors'] = null;
        $data['data'] = null;
        if ($isValid) {
            $list = $this->model->getById($id);
            if ($list) {
                $listModel = new ListModel();
                $listModel->where(["id = $id"])->delete();
                $data['ok'] = true;
                $data['message'] = 'Success';
                $statusCode = Response::HTTP_OK;
            } else {
                $data['ok'] = false;
                $data['message'] = 'List is not exist.';
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

    /**
     * add item to  a list in the database.
     */
    public function addItem()
    {
        $validator = new Validator();
        $rules = [
            'list_id' => 'required | Integer',
            'name' => 'required | maxlength(155)',
        ];
        $validator->add($rules);
        $list_id = $this->request->get('list_id');
        $name = $this->request->get('name');
        $params = ['list_id' => $list_id, 'name'=>$name];
        $isValid = $validator->validate($params);
        $data['errors'] = null;
        $data['data'] = null;
        if ($isValid) {
            $list = $this->model->getById($list_id);
            if ($list) {
                $item = new Item();
                $item->insert($params);
                $data['ok'] = true;
                $data['message'] = 'Success';
                $statusCode = Response::HTTP_OK;
            } else {
                $data['ok'] = false;
                $data['message'] = 'List is not exist.';
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
}
