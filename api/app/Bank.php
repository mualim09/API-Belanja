<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Bank extends Utility
{
    static $pdo;
    static $query;

    protected static function getConn()
    {
        return self::$pdo;
    }

    public function __construct($connection)
    {
        self::$pdo = $connection;
        self::$query = new Query(self::$pdo);
    }

    public function __POST__($parameter = array())
    {
        //
    }

    public function __GET__($parameter = array())
    {
        try {
            switch ($parameter[1]) {
                case 'get_bank_select2':
                    return self::get_bank_select2();
                    break;
                default:
                    return array();
                    break;
            }
        } catch (QueryException $e) {
            return 'Error => ' . $e;
        }
    }

    public function bank_detail($parameter){
        $data = self::$query->select('master_bank', array(
            'uid',
            'nama',
            'kode_transaksi'
        ))
            ->where(array(
                'master_bank.deleted_at' => 'IS NULL',
                'AND',
                'master_bank.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        return $data;
    }

    private function get_bank_select2(){
        $data = self::$query->select('master_bank', array(
            'uid',
            'nama',
            'kode_transaksi'
        ))
            ->where(array(
                'master_bank.deleted_at' => 'IS NULL',
                'AND',
                '(master_bank.nama' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\'',
                'OR',
                'master_bank.kode_transaksi' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\')'
            ))
            ->execute();
        return $data;
    }
}
?>