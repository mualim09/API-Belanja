<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Order</li>
                </ol>
            </nav>
            <h1 class="m-0">Order</h1>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card card-group-row__card card-body">
                <div class="card">
                    <div class="card-header card-header-large bg-white">
                        <h5 class="card-header__title flex m-0">Order</h5>
                    </div>
                    <div class="card-header">
                        <a href="<?php echo __HOSTNAME__; ?>/penjualan/order/tambah" class="btn btn-info ml-3 pull-right">
                            <i class="fa fa-plus-circle"></i> Tambah Order
                        </a>
                    </div>
                    <div class="card-body tab-content">
                        <div class="tab-pane active show fade" id="customer-modul">
                            <div class="row">
                                <div class="col-9 text-right">

                                </div>
                                <div class="col-3">

                                </div>
                                <div class="col-12" style="padding-top: 10px">
                                    <table class="table table-bordered table-striped largeDataType" id="table-order">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th>Nomor</th>
                                            <th>Customer</th>
                                            <th>Total</th>
                                            <th>Tanggal</th>
                                            <th class="wrap_content">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>