<?php

/**
 * Class     Vendor_String
 * 字符串操作类
 *
 * @author   jiaxuan
 */
class Vendor_String {

    /**
     * Method  camelToUnderline
     * 驼峰转下划线
     *
     * @author jiaxuan
     * @static
     *
     * @param $string
     *
     * @return string
     */
    public static function camelToUnderline($string) {
        return strtolower(preg_replace('/(?!^)(?=[A-Z])/', '_', $string));
    }
}