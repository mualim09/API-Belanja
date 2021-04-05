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
            case 'detail':
                return self::order_detail($parameter[2]);
                break;
            default:
                return array();
        }
    }

    public function __POST__($parameter = array())
    {
        switch ($parameter['request']) {
            case 'get_order_backend':
                return self::get_order_backend($parameter);
                break;
            case 'tambah_order':
                return self::tambah_order($parameter);
                break;
            case 'keranjang_proceed_orders':
                return self::keranjang_proceed_orders($parameter);
                break;
            default:
                return array();
        }
    }

    private function keranjang_proceed_orders($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $Inventori = new Inventori(self::$pdo);
        $Customer = new Membership(self::$pdo);
        $CustomerInfo = $Customer->customer_detail($UserData['data']->uid)['response_data'][0];


        $check = $Inventori->check_keranjang($UserData['data']->uid);
        if(count($check['response_data']) > 0) {
            $uid_keranjang = $check['response_data'][0]['uid'];
            $itemDetailParsed = array();

            $total_pre_discount = 0;

            $data = self::$query->select('keranjang_detail', array(
                'id',
                'produk',
                'jumlah'
            ))
                ->where(array(
                    'keranjang_detail.keranjang' => '= ?',
                    'AND',
                    'keranjang_detail.deleted_at' => 'IS NULL'
                ), array(
                    $uid_keranjang
                ))
                ->execute();

            foreach ($data['response_data'] as $key => $value) {
                $ItemDetail = $Inventori->get_item_detail($value['produk'])['response_data'];

                array_push($itemDetailParsed, array(
                    'produk' => $value['produk'],
                    'qty' => $value['jumlah'],
                    'cashback' => (($CustomerInfo['jenis_member'] === 'M') ? $ItemDetail['harga']['member_cashback'] : $ItemDetail['harga']['stokis_cashback']),
                    'royalti' => (($CustomerInfo['jenis_member'] === 'M') ? $ItemDetail['harga']['member_royalti'] : $ItemDetail['harga']['stokis_royalti']),
                    'reward' => (($CustomerInfo['jenis_member'] === 'M') ? $ItemDetail['harga']['member_reward'] : $ItemDetail['harga']['stokis_reward']),
                    'insentif' => (($CustomerInfo['jenis_member'] === 'M') ? $ItemDetail['harga']['member_insentif_personal'] : $ItemDetail['harga']['stokis_insentif_personal'])
                ));

                $total_pre_discount += floatval($value['jumlah'] * (($CustomerInfo['jenis_member'] === 'M') ? $ItemDetail['harga']['harga_akhir_member'] : $ItemDetail['harga']['harga_akhir_stokis']));
            }

            $prepare = array(
                'penerima' => $CustomerInfo['nama'],
                'customer' => $UserData['data']->uid,
                'kurir' => '',
                'alamat_billing' => $CustomerInfo['alamat_domisili'],
                'alamat_antar' => $CustomerInfo['alamat_domisili'],
                'provinsi' => intval($CustomerInfo['provinsi_domisili']),
                'kabupaten' => intval($parameter['kabupaten_domisili']),
                'kecamatan' => intval($parameter['kecamatan_domisili']),
                'kelurahan' => intval($parameter['kelurahan_domisili']),
                'total_pre_disc' => floatval($total_pre_discount),
                'total_after_disc' => floatval($total_pre_discount),
                'disc_type' => 'N',
                'disc' => 0,
                'via' => 'A',
                'remark' => '',
                'itemDetail' => $itemDetailParsed
            );
            $newOrder = self::tambah_order($prepare);
            if($newOrder['response_result'] > 0) {
                //Update Keranjang Status
                $keranjang = self::$query->update('keranjang', array(
                    'status' => 'D',
                    'deleted_at' => parent::format_date()
                ))
                    ->where(array(
                        'keranjang.uid' => '= ?',
                        'AND',
                        'keranjang.deleted_at' => 'IS NULL'
                    ), array(
                        $uid_keranjang
                    ))
                    ->execute();
            }
            
            /*unset($newOrder['response_query']);
            unset($newOrder['response_values']);
            unset($newOrder['detail']);*/

            return $newOrder;

            //return $CustomerInfo;
        } else {
            return array(
                'response_result' => 0,
                'response_message' => 'Gagal proses order'
            );
        }
    }

    public function order_detail($parameter) {
        $Inventori = new Inventori(self::$pdo);
        $Customer = new Membership(self::$pdo);
        $data = self::$query->select('orders', array(
            'uid',
            'nomor_invoice',
            'status',
            'penerima',
            'customer',
            'tanggal_order',
            'kurir',
            'alamat_billing',
            'alamat_antar',
            'provinsi',
            'kabupaten',
            'kecamatan',
            'kelurahan',
            'total_pre_disc',
            'total_after_disc',
            'keranjang',
            'remark',
            'disc',
            'disc_type',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'orders.deleted_at' => 'IS NULL',
                'AND',
                'orders.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        foreach ($data['response_data'] as $key => $value) {
            $detail = self::$query->select('orders_detail', array(
                'id',
                'barang',
                'qty',
                'satuan',
                'harga',
                'bonus_type',
                'cashback',
                'royalti',
                'reward',
                'insentif_personal',
                'total'
            ))
                ->where(array(
                    'orders_detail.deleted_at' => 'IS NULL',
                    'AND',
                    'orders_detail.orders' => '= ?'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($detail['response_data'] as $dKey => $dValue) {
                $detail['response_data'][$dKey]['barang'] = $Inventori->get_item_detail($dValue['barang'])['response_data'];
                $detail['response_data'][$dKey]['cashback'] = floatval($dValue['cashback']);
                $detail['response_data'][$dKey]['royalti'] = floatval($dValue['royalti']);
                $detail['response_data'][$dKey]['reward'] = floatval($dValue['reward']);
                $detail['response_data'][$dKey]['insentif_personal'] = floatval($dValue['insentif_personal']);
                $detail['response_data'][$dKey]['harga'] = floatval($dValue['harga']);
                $detail['response_data'][$dKey]['total'] = floatval($dValue['total']);
            }

            $data['response_data'][$key]['detail'] = $detail['response_data'];


            $data['response_data'][$key]['tanggal_order'] = date('d F Y [H:i]', strtotime($value['tanggal_order']));
            $data['response_data'][$key]['total_after_disc'] = floatval($value['total_after_disc']);
            $data['response_data'][$key]['total_pre_disc'] = floatval($value['total_pre_disc']);

            $data['response_data'][$key]['customer'] = $Customer->customer_detail($value['customer'])['response_data'][0];
        }

        return $data;
    }

    private function tambah_order($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $latestOrder = self::$query->select('orders', array(
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
        $proceed = self::$query->insert('orders', array(
            'uid' => $uid,
            'tanggal_order' => parent::format_date(),
            'nomor_invoice' => $set_code,
            'status' => 'N',
            'penerima' => $parameter['penerima'],
            'diproses_oleh' => $UserData['data']->uid,
            'customer' => $parameter['customer'],
            'kurir' => $parameter['kurir'],
            'alamat_billing' => $parameter['alamat_billing'],
            'alamat_antar' => $parameter['alamat_antar'],
            'provinsi' => intval($parameter['provinsi']),
            'kabupaten' => intval($parameter['kabupaten']),
            'kecamatan' => intval($parameter['kecamatan']),
            'kelurahan' => intval($parameter['kelurahan']),
            'total_pre_disc' => floatval($parameter['total_pre_discount']),
            'total_after_disc' => floatval($parameter['total_after_discount']),
            'disc_type' => $parameter['disc_type'],
            'disc' => floatval($parameter['disc']),
            'remark' => $parameter['remark'],
            'created_on' => isset($parameter['via']) ? 'A' : 'W',
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()

        ))
            ->execute();

        $Inventori = new Inventori(self::$pdo);
        $Customer = new Membership(self::$pdo);
        $CustomerInfo = $Customer->customer_detail($parameter['customer'])['response_data'][0];
        $detail_proceed = array();
        if($proceed['response_result'] > 0) {
            foreach ($parameter['itemDetail'] as $key => $value) {
                $ItemDetail = $Inventori->get_item_detail($value['produk'])['response_data'];

                $detail = self::$query->insert('orders_detail', array(
                    'orders' => $uid,
                    'barang' => $value['produk'],
                    'qty' => floatval($value['qty']),
                    'satuan' => $ItemDetail['satuan_terkecil'],
                    'harga' => floatval(($CustomerInfo['jenis_member'] === 'M') ? $ItemDetail['harga']['harga_jual_member'] : $ItemDetail['harga']['harga_jual_stokis']),
                    'total' => floatval($value['qty'] * (($CustomerInfo['jenis_member'] === 'M') ? $ItemDetail['harga']['harga_jual_member'] : $ItemDetail['harga']['harga_jual_stokis'])),
                    'bonus_type' => $CustomerInfo['jenis_member'],
                    'cashback' => floatval($value['cashback']),
                    'royalti' => floatval($value['royalti']),
                    'reward' => floatval($value['reward']),
                    'insentif_personal' => floatval($value['insentif']),
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()

                ))
                    ->execute();
                array_push($detail_proceed, $detail);
            }

            $log = parent::log(array(
                'type' => 'activity',
                'column' => array(
                    'unique_target',
                    'user_uid',
                    'table_name',
                    'action',
                    'new_value',
                    'logged_at',
                    'status',
                    'login_id'
                ),
                'value' => array(
                    $uid,
                    $UserData['data']->uid,
                    'orders',
                    'I',
                    json_encode($parameter),
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class' => __CLASS__
            ));
        }
        $proceed['detail'] = $detail_proceed;

        return $proceed;
    }

    private function get_order_backend($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'orders.deleted_at' => 'IS NULL',
                'AND',
                'orders.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'orders.status' => '= ?',
                'AND',
                'orders.nomor_invoice' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array(
                $parameter['from'], $parameter['to'], $parameter['status']
            );
        } else {
            $paramData = array(
                'orders.deleted_at' => 'IS NULL',
                'AND',
                'orders.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'orders.status' => '= ?'
            );

            $paramValue = array(
                $parameter['from'], $parameter['to'], $parameter['status']
            );
        }

        if ($parameter['length'] < 0) {
            $data = self::$query->select('orders', array(
                'uid',
                'nomor_invoice',
                'status',
                'penerima',
                'customer',
                'tanggal_order',
                'kurir',
                'alamat_billing',
                'alamat_antar',
                'provinsi',
                'kabupaten',
                'kecamatan',
                'kelurahan',
                'total_pre_disc',
                'total_after_disc',
                'created_on',
                'keranjang',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('orders', array(
                'uid',
                'nomor_invoice',
                'status',
                'penerima',
                'customer',
                'tanggal_order',
                'kurir',
                'alamat_billing',
                'alamat_antar',
                'provinsi',
                'kabupaten',
                'kecamatan',
                'kelurahan',
                'total_pre_disc',
                'total_after_disc',
                'created_on',
                'keranjang',
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
        $Membership = new Membership(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['tanggal_order'] = date('d F Y (H:i)', strtotime($value['tanggal_order']));
            $data['response_data'][$key]['source'] = ($value['keranjang'] === null) ? 'W' : 'A';
            $data['response_data'][$key]['customer'] = $Membership->customer_detail($value['customer'])['response_data'][0];
            $autonum++;
        }

        $itemTotal = self::$query->select('orders', array(
            'id'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
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