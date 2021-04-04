<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Customer</li>
                </ol>
            </nav>
            <h1 class="m-0">Customer</h1>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="card card-group-row__card card-body">
                <div class="card">
                    <div class="card-header card-header-large bg-white">
                        <h5 class="card-header__title flex m-0">Customer</h5>
                    </div>
                    <div class="card-header">
                        <a href="<?php echo __HOSTNAME__; ?>/customer/tambah" class="btn btn-info ml-3 pull-right"><i class="fa fa-plus-circle"></i> Tambah Customer</a>
                        <a style="width: 200px;">
                            <button class="btn btn-info" id="btn-import">
                                <i class="fa fa-download"></i> Import
                            </button>
                        </a>
                    </div>
                    <div class="card-body tab-content">
                        <div class="tab-pane active show fade" id="customer-modul">
                            <div class="row">
                                <div class="col-9 text-right">

                                </div>
                                <div class="col-3">
                                    Jenis Customer
                                    <select class="form-control" id="jenis_customer">
                                        <option value="A">Semua</option>
                                        <option value="M">Member</option>
                                        <option value="S">Stokis</option>
                                    </select>
                                </div>
                                <div class="col-12" style="padding-top: 20px">
                                    <table class="table table-bordered table-striped largeDataType" id="table-customer">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th>Customer</th>
                                            <th>Jenis</th>
                                            <th>Tanggal Daftar</th>
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