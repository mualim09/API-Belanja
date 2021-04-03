<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Orders extends Utility
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
            case 'get_all':
                return array();
                break;
        }
    }

    public function __POST__($parameter = array())
    {
        //
    }

    private function order_baru($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $latestOrder = self::$query->select('inventori_po', array(
            'uid'
        ))
            ->where(array(
                'EXTRACT(MONTH FROM created_at)' => '= ?'
            ), array(
                intval(date('m'))
            ))
            ->execute();

        $set_code = 'BSO/' . date('Y') . '/' . str_pad(date('m'), 2, '0', STR_PAD_LEFT) . '/'. str_pad(count($latestOrder['response_data']) + 1, 4, '0', STR_PAD_LEFT);
        $uid = parent::gen_uuid();
        $total_pre = 0;
        $total_after = 0;
        //Detail
        foreach ($parameter['detail'] as $key => $value) {
            //get bonus
            $bonus = self::$query->select('strategi_penjualan', array(
                'id',
                'produk',
                'tanggal',
                'member_cashback',
                'member_royalti',
                'member_reward',
                'member_insentif_personal',

                'stokis_cashback',
                'stokis_royalti',
                'stokis_reward',
                'stokis_insentif_personal'
            ))
                ->where(array(
                    'strategi_penjualan.produk' => '= ?',
                    'AND',
                    'strategi_penjualan.tanggal' => '= ?',
                    'AND',
                    'strategi_penjualan.deleted_at' => 'IS NULL'
                ), array(
                    $value['barang'],
                    date('Y-m-d')
                ))
                ->execute();

            //get batch

            $detail_order = self::$query->insert('orders_detail', array(
                'barang' => $value['barang'],
                'batch' => '',
                'qty' => floatval($value['qty']),
                'satuan' => '',
                'harga' => 0,
                'bonus_type' => '',
                'cashback' => 0,
                'royalti' => 0,
                'reward' => 0,
                'insentif_personal' => 0,
                'orders' => $uid,
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();
        }

        $proceed = self::$query->insert('orders', array(
            'uid' => $uid,
            'nomor_invoice' => $set_code,
            'diproses_oleh' => $UserData['data']->uid,
            'status' => 'N',
            'penerima' => '',
            'customer' => $parameter['customer'],
            'tanggal_order' => parent::format_date(),
            'kurir' => $parameter['kurir'],
            'alamat_billing' => $parameter['alamat_billing'],
            'alamat_antar' => $parameter['alamat_antar'],
            'provinsi' => $parameter['provinsi'],
            'kabupaten' => $parameter['kabupaten'],
            'kecamatan' => $parameter['kecamatan'],
            'kelurahan' => $parameter['kelurahan'],
            'total_pre_disc' => $total_pre,
            'total_after_disc' => $total_after
        ))
            ->execute();
    }
}

?>