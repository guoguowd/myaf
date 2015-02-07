<?php

class Base_Models {

    /**
     * Variable  _table_name
     * 当前实例对应的表名
     *
     * @author   jiaxuan
     * @var      null
     */
    private $_table_name = null;

    /**
     * Variable  _sql_string
     *
     * @author   jiaxuan
     * @var      array
     */
    private $_sql_string = array();

    /**
     * Variable  _database_config
     * 数据库配置信息
     *
     * @author   jiaxuan
     * @var      array
     */
    private $_database_config = array();

    /**
     * Variable  _master_connection
     * 主库连接
     *
     * @author   jiaxuan
     * @static
     * @var      null
     */
    private static $_master_connection = null;

    /**
     * Variable  _slave_connection
     * 从库连接
     *
     * @author   jiaxuan
     * @static
     * @var      null
     */
    private static $_slave_connection = null;

    /**
     * Variable  _sql
     *
     * @author   jiaxuan
     * @static
     * @var      null
     */
    private static $_sql = null;

    /**
     * Variable  _is_begin_transaction
     * 是否开启事务
     *
     * @author   jiaxuan
     * @static
     * @var      bool
     */
    private static $_is_begin_transaction = false;

    /**
     * Method __construct
     * 构造方法
     *
     * @param null $table_name
     */
    public function __construct($table_name = null) {
        //验证table_name
        if (null === $table_name) {
            $table_name = str_replace('Model', '', get_called_class());

        } elseif (false !== stripos($table_name, 'Model')) {
            $table_name = str_replace('Model', '', $table_name);
        }

        //驼峰转下划线
        $table_name = strtolower(trim(Vendor_String::camelToUnderline($table_name)));

        //设置表名
        $this->setTableName($table_name);

        //设置数据库配置信息
        $this->_database_config = Yaf_Registry::get('config')->database->toArray();

    }

    /**
     * Method  setTableName
     * 设置表名
     *
     * @author jiaxuan
     *
     * @param $table_name
     */
    protected function setTableName($table_name) {
        $this->_table_name = $table_name;
    }

    /**
     * Method  getTableName
     * 获取表名
     *
     * @author jiaxuan
     * @return null
     */
    protected function getTableName() {
        return $this->_table_name;
    }

    /**
     * Method  _getMasterConnection
     * 获取主库连接
     *
     * @author jiaxuan
     * @return null|\Zend\Db\Adapter\Adapter
     */
    private function _getMasterConnection() {
        if (null === self::$_master_connection) {
            self::$_master_connection = new Zend\Db\Adapter\Adapter($this->_database_config['master']['params']);
        }

        return self::$_master_connection;
    }

    /**
     * Method  _getSlaveConnection
     * 获取从库连接
     *
     * @author jiaxuan
     */
    private function _getSlaveConnection() {
        if (null === self::$_slave_connection) {

            if (empty($this->_database_config['slave']['enable'])) {

                self::$_slave_connection = new Zend\Db\Adapter\Adapter($this->_database_config['master']['params']);

            } else {

                self::$_slave_connection = new Zend\Db\Adapter\Adapter($this->_database_config['slave']['params']);

            }
        }

        return self::$_slave_connection;
    }

    /**
     * Method  select
     * 获取select实例
     *
     * @author jiaxuan
     *
     * @param null  $table_name
     * @param array $columns
     * @param bool  $is_connect_master
     *
     * @return \Zend\Db\Sql\Select
     */
    public function select($table_name = null, array $columns = null, $is_connect_master = false) {
        //验证表名
        if (null === $table_name) {
            $table_name = $this->getTableName();
        }

        //实例化一个Zend\Db\Sql\Sql对象
        if (false === $is_connect_master) {
            self::$_sql = new Zend\Db\Sql\Sql($this->_getSlaveConnection());
        } else {
            self::$_sql = new Zend\Db\Sql\Sql($this->_getMasterConnection());
        }

        //实例化一个Zend\Db\Sql\Select对象
        $select = self::$_sql->select($table_name);

        //设置查询字段
        if (null !== $columns) {
            $select->columns($columns);
        }

        return $select;
    }

    /**
     * Method  fetchResult
     *
     * @author jiaxuan
     *
     * @param $select
     *
     * @return mixed
     */
    public function fetchResult($select) {
        $statement = self::$_sql->prepareStatementForSqlObject($select);

        return $statement->execute();
    }

    /**
     * Method  fetchRow
     *
     * @author jiaxuan
     *
     * @param $select
     *
     * @return array
     */
    public function fetchRow($select) {
        if ($result = $this->fetchResult($select)->current()) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * Method  fetchAll
     *
     * @author jiaxuan
     *
     * @param        $select
     * @param string $key
     *
     * @return array
     */
    public function fetchAll($select, $key = '') {
        $result = $this->fetchResult($select);

        $data = array();

        while ($row = $result->current()) {
            if ($key && isset($row[$key])) {
                $data[$row[$key]] = $row;
            } else {
                $data[] = $row;
            }
        }

        return $data;
    }

    /**
     * Method  insert
     * 插入数据
     *
     * @author jiaxuan
     *
     * @param null  $table_name
     * @param array $data
     * @param       $is_connect_master
     *
     * @return int|\Zend\Db\Adapter\Driver\ResultInterface
     */
    public function insert($table_name = null, array $data, $is_connect_master = true) {
        //插入数据为空
        if (empty($data)) {
            return 0;
        }

        if (false === $is_connect_master) {
            self::$_sql = new Zend\Db\Sql\Sql($this->_getSlaveConnection());
        } else {
            self::$_sql = new Zend\Db\Sql\Sql($this->_getMasterConnection());
        }

        //实例化一个Zend\Db\Sql\Insert对象
        $insert = self::$_sql->insert($table_name);
        $insert->values($data);
        $statment = self::$_sql->prepareStatementForSqlObject($insert);
        $result   = $statment->execute();

        //返回最后插入的id
        return $result->getGeneratedValue();
    }

    /**
     * Method  update
     * 更新数据
     *
     * @author jiaxuan
     *
     * @param null  $table_name
     * @param array $data
     * @param array $where
     * @param       $is_connect_master
     *
     * @return int
     */
    public function update($table_name = null, array $data, array $where = array(), $is_connect_master = true) {
        if (empty($data)) {
            return 0;
        }

        //验证表名
        if (null === $table_name) {
            $table_name = $this->getTableName();
        }

        if (false === $is_connect_master) {
            self::$_sql = new Zend\Db\Sql\Sql($this->_getSlaveConnection());
        } else {
            self::$_sql = new Zend\Db\Sql\Sql($this->_getMasterConnection());
        }

        //实例化一个Zend\Db\Sql\Update对象
        $update = self::$_sql->update($table_name);

        //设置更新的数据
        $update->set($data);

        //设置更新的条件
        $update->where($where);

        $statment = self::$_sql->prepareStatementForSqlObject($update);
        $result   = $statment->execute();

        //返回最后插入的id
        return $result->getGeneratedValue();
    }

}