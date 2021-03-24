<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Customer extends Utility
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
        switch ($parameter['request']) {
            case 'get_customer':
                return self::get_customer($parameter);
                break;
        }
    }

    private function get_customer($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'membership.deleted_at' => 'IS NULL',
                'AND',
                '(membership.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'membership.nik' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
            );

            $paramValue = array();
        } else {
            $paramData = array(
                'membership.deleted_at' => 'IS NULL'
            );

            $paramValue = array();
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('membership', array(
                'uid',
                'kode_barang',
                'nama',
                'kategori',
                'het',
                'satuan_terkecil',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('master_inv', array(
                'uid',
                'kode_barang',
                'nama',
                'kategori',
                'het',
                'satuan_terkecil',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {


            $data['response_data'][$key]['autonum'] = $autonum;
            if(file_exists('../images/produk/' . $value['uid'] . '.png')) {
                $data['response_data'][$key]['image'] = 'images/produk/' . $value['uid'] . '.png';
            } else {
                $data['response_data'][$key]['image'] = 'images/product.png';
            }

            /*$kategori_obat = self::get_kategori_obat_item($value['uid']);
            foreach ($kategori_obat as $KOKey => $KOValue) {
                $kategori_obat[$KOKey]['kategori'] = self::get_kategori_obat_detail($KOValue['kategori'])['response_data'][0]['nama'];
            }*/

            //$data['response_data'][$key]['kategori_obat'] = $kategori_obat;
            $data['response_data'][$key]['satuan_terkecil'] = self::get_satuan_detail($value['satuan_terkecil'])['response_data'][0];
            $data['response_data'][$key]['kategori'] = self::get_kategori_detail($value['kategori'])['response_data'][0];
            //$data['response_data'][$key]['manufacture'] = self::get_manufacture_detail($value['manufacture'])['response_data'][0];

            //Data Penjamin
            /*$PenjaminObat = new Penjamin(self::$pdo);
            $ListPenjaminObat = $PenjaminObat::get_penjamin_obat($value['uid'])['response_data'];
            foreach ($ListPenjaminObat as $PenjaminKey => $PenjaminValue) {
                $ListPenjaminObat[$PenjaminKey]['profit'] = floatval($PenjaminValue['profit']);
            }
            $data['response_data'][$key]['penjamin'] = $ListPenjaminObat;*/

            //Cek Ketersediaan Stok
            /*$TotalStock = 0;
            $InventoriStockPopulator = self::get_item_batch($value['uid']);
            if (count($InventoriStockPopulator['response_data']) > 0) {
                foreach ($InventoriStockPopulator['response_data'] as $TotalKey => $TotalValue) {
                    $TotalStock += floatval($TotalValue['stok_terkini']);
                }
                $data['response_data'][$key]['stok'] = $TotalStock;
                $data['response_data'][$key]['batch'] = $InventoriStockPopulator['response_data'];
            } else {
                $data['response_data'][$key]['stok'] = 0;
            }*/

            $autonum++;
        }

        $itemTotal = self::$query->select('master_inv', array(
            'uid'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }
}
?>