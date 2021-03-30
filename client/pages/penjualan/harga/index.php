<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Harga</li>
                </ol>
            </nav>
            <h4 class="m-0">Harga</h4>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    <div class="row">
        <div class="col-lg">
            <div class="card">
                <div class="card-header card-header-large bg-white align-items-center">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5 class="card-header__title flex m-0">Manajemen Harga</h5>
                        </div>
                        <div class="col-lg-3 text-right">
                            Tanggal
                        </div>
                        <div class="col-lg-3">
                            <input type="text" id="txt_tanggal" class="form-control pull-right" />
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive border-bottom">
                        <table class="table table-bordered largeDataType" id="tableHarga">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th>Produk</th>
                                <th>Harga HET</th>
                                <th style="width: 100px;">Harga Jual</th>
                                <th style="width: 100px;">Tipe Diskon</th>
                                <th style="width: 100px;">Diskon</th>
                                <th style="width: 100px;">Harga Akhir</th>
                                <th style="width: 100px;">Bonus Cashback</th>
                                <th style="width: 100px;">Bonus Royalti</th>
                                <th style="width: 100px;">Bonus Reward</th>
                                <th style="width: 100px;">Insentif Personal</th>
                                <th class="wrap_content">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>