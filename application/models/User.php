<?php

/**
 * Class     UserModel
 *
 * @author   jiaxuan
 */
class UserModel extends Base_Models {

    /**
     * Method  createUser
     *
     * @author jiaxuan
     *
     * @param $data
     *
     * @return int|\Zend\Db\Adapter\Driver\ResultInterface
     */
    public function createUser($data) {
        return $this->insert($this->getTableName(), $data);
    }

    public function updateData($data, $where) {

        return $this->update($this->getTableName(), $data, $where);
    }

    public function showUser($data){

        $select = $this->select()->where($data);

        $resut = $this->fetchRow($select);
        dump($resut);exit;
        return $resut;
    }
}