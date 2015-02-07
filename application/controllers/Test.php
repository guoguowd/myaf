<?php

class TestController extends Base_Controllers {

    public function testAction() {
        $user_model = new UserModel();

        $data = array(
            'user_id'   => 20,
            'user_name' => 'guoguowd1999',
            'email'     => 'guoguowd@163.com'
        );

        $result = $user_model->showUser($data);
        dump($result);

        return false;
    }
}