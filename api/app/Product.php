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
                return self::all_product();
        }
    }

    public function __POST__($parameter = array())
    {
        switch ($parameter['request']) {
            case 'tambah_produk':
                return array();
                //return self::tambah_produk($parameter);
                break;
            case 'edit_produk':
                return array();
                //return self::edit_produk($parameter);
                break;
            case 'tambah_kategori_produk':
                return array();
                //return self::tambah_kategori_produk($parameter);
                break;
            default:
                return 'Tidak tau';
        }
    }

    private function all_product() {
        $data = self::$query->select('master_inv', array())
            ->where(array(
                'master_inv.deleted_at' => 'IS NULL'
            ), array())
            ->execute();
        $data['response_message'] = (count($data['response_data']) > 0) ? 'Data Tersedia' : 'Data Tidak Tersedia';
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['nama_produk'] = $value['nama'];
            $data['response_data'][$key]['harga'] = $value['het'];
            $data['response_data'][$key]['rating'] = 5.0;
            if(file_exists('../images/produk/' . $value['uid'] . '.png')) {
                $data['response_data'][$key]['url_gambar'] = 'images/produk/' . $value['uid'] . '.png';
            } else {
                $data['response_data'][$key]['url_gambar'] = 'images/product.png';
            }

        }
        return $data;
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