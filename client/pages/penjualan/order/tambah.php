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
                                        <label for="order_customer">Customer:</label>
                                        <select class="form-control" id="order_customer"></select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="order_customer">Penerima a/n:</label>
                                        <input class="form-control" id="order_receiver" placeholder="Nama Penerima" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="order_customer">Kurir:</label>
                                        <input class="form-control" id="order_kurir" placeholder="Kurir" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered largeDataType" id="auto_produk">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th rowspan="2" class="wrap_content">No</th>
                                                <th rowspan="2" style="width: 300px;">Produk</th>
                                                <th rowspan="2" style="width: 100px;">Qty</th>
                                                <th rowspan="2" class="wrap_content">Harga Jual</th>
                                                <th colspan="4">Bonus</th>
                                                <th rowspan="2" class="wrap_content">Aksi</th>
                                            </tr>
                                            <tr>
                                                <th class="wrap_content">Cashback</th>
                                                <th class="wrap_content">Royalti</th>
                                                <th class="wrap_content">Reward</th>
                                                <th class="wrap_content">Insentif</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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
                                    <label for="order_customer">Provinsi:</label>
                                    <select class="form-control" id="order_provinsi"></select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="order_customer">Kabupaten:</label>
                                    <select class="form-control" id="order_kabupaten"></select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="order_customer">Kecamatan:</label>
                                    <select class="form-control" id="order_kecamatan"></select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="order_customer">Kelurahan:</label>
                                    <select class="form-control" id="order_kelurahan"></select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_customer">Alamat Antar:</label>
                                    <textarea class="form-control" id="order_address" placeholder="Alamat Antar"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_customer">Alamat Tagih:</label>
                                    <textarea class="form-control" id="order_address" placeholder="Alamat Antar"></textarea>
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
                        <button class="btn btn-success pull-right">
                            <i class="fa fa-plus"></i> Tambah
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