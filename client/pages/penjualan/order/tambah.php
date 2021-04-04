<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/penjualan/order">Order</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Baru</li>
                </ol>
            </nav>
            <h1 class="m-0">Order Baru</h1>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card card-group-row__card card-body">
                <div class="card">
                    <div class="card-header card-header-large bg-white">
                        <h5 class="card-header__title flex m-0">Tambah Order</h5>
                    </div>
                    <div class="card-body tab-content">
                        <div class="tab-pane active show fade" id="order-modul">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="order_customer">Customer: <b id="warn_customer"></b></label>
                                        <select class="form-control" id="order_customer"></select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="order_customer">Penerima a/n: <button id="btn-samakan-customer" class="btn btn-info pull-right" style="margin-left: 50px;"><i class="fa fa-copy"></i></button></label>
                                        <input class="form-control" id="order_receiver" placeholder="Nama Penerima" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="order_customer">Kurir: <b id="warn_kurir"></b></label>
                                        <input class="form-control" id="order_kurir" placeholder="Kurir" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h6>Detail Pesanan: <b id="warn_item"></b></h6>
                                    <table class="table table-bordered largeDataType" id="auto_produk">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th rowspan="2" class="wrap_content">No</th>
                                                <th rowspan="2" style="width: 250px;">Produk</th>
                                                <th rowspan="2" style="width: 80px;">Qty</th>
                                                <th rowspan="2" style="width: 100px;">Harga Jual</th>
                                                <th colspan="4">Bonus</th>
                                                <th rowspan="2" class="wrap_content">Aksi</th>
                                            </tr>
                                            <tr>
                                                <th style="width: 100px;">Cashback</th>
                                                <th style="width: 100px;">Royalti</th>
                                                <th style="width: 100px;">Reward</th>
                                                <th style="width: 100px;">Insentif</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot style="background: #fafafa">
                                            <tr>
                                                <td colspan="3" class="text-right">
                                                    <b>TOTAL (Pre Disc.)</b>
                                                </td>
                                                <td class="number_style" id="order_total_belanja">0.00</td>
                                                <td class="number_style" id="order_total_cashback">0.00</td>
                                                <td class="number_style" id="order_total_royalti">0.00</td>
                                                <td class="number_style" id="order_total_reward">0.00</td>
                                                <td class="number_style" id="order_total_insentif">0.00</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-right">
                                                    <b>DISCOUNT</b>
                                                </td>
                                                <td>
                                                    <select class="form-control" id="order_disc_type">
                                                        <option value="N">Tidak Ada</option>
                                                        <option value="P">Percentage</option>
                                                        <option value="A">Amount</option>
                                                    </select>
                                                    <input type="text" class="form-control" id="order_disc" />
                                                </td>
                                                <td colspan="5" rowspan="2">
                                                    <b>Remark:</b>
                                                    <textarea id="order_remark" class="form-control" placeholder="Exp: Keterangan diskon, Catatan pesanan" style="min-height: 200px;"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-right">
                                                    <b>Grand TOTAL</b>
                                                </td>
                                                <td class="number_style" id="order_grand_total" style="font-size: 14pt">0.00</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header card-header-large bg-white">
                        <h5 class="card-header__title flex m-0">Delivery</h5>
                    </div>
                    <div class="card-body tab-content">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="order_customer">Provinsi: <b id="warn_provinsi"></b></label>
                                    <select class="form-control" id="order_provinsi"></select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="order_customer">Kabupaten: <b id="warn_kabupaten"></b></label>
                                    <select class="form-control" id="order_kabupaten"></select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="order_customer">Kecamatan: <b id="warn_kecamatan"></b></label>
                                    <select class="form-control" id="order_kecamatan"></select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="order_customer">Kelurahan: <b id="warn_kelurahan"></b></label>
                                    <select class="form-control" id="order_kelurahan"></select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_customer">Alamat Antar: <b id="warn_delivery"></b></label>
                                    <textarea class="form-control" id="order_delivery" placeholder="Alamat Antar"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_customer">Alamat Tagih: <b id="warn_billing"></b></label>
                                    <textarea class="form-control" id="order_charge" placeholder="Alamat Antar"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <a href="<?php echo __HOSTNAME__; ?>/penjualan/order" class="btn btn-danger">
                            <i class="fa fa-ban"></i> Batal
                        </a>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-success pull-right" id="btnOrder">
                            <i class="fa fa-plus"></i> Tambah Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    import Table from "../../../src/views/base/Table";
    export default {
        components: {Table}
    }
</script>