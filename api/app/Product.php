<?php
namespace PondokCoder;
use PondokCoder\Query as Query;
use PondokCoder\Utility as Utility;

class Product extends Utility
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

    public function __GET__($parameter = array())
    {
        switch ($parameter[1]) {
            case 'detail':
                return self::detail_produk($parameter);
                break;
            default:
                return 'Tidak tau';
        }
    }

    public function __POST__($parameter = array())
    {
        switch ($parameter['request']) {
            case 'tambah_produk':
                return self::tambah_produk($parameter);
                break;
            case 'edit_produk':
                return self::edit_produk($parameter);
                break;
            case 'tambah_kategori_produk':
                return self::tambah_kategori_produk($parameter);
                break;
            default:
                return 'Tidak tau';
        }
    }






    private function tambah_produk($parameter) {

    }

    private function edit_produk($parameter) {
        //
    }

    private function tambah_kategori_produk($parameter) {
        //
    }

    private function detail_produk($parameter) {
        $data = self::$query->select('membership', array(
            'uid', 'nama', 'email'
        ))
            ->where(array(
                'membership.uid' => '= ?'
            ), array(
                $parameter[2]
            ))
            ->execute();
        return $data;
    }
}

?>