<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/customer">Customer</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="mode_item">Edit</li>
                </ol>
            </nav>
            <h4><span id="nama-departemen"></span>Edit Customer</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="z-0">
                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                    <li class="nav-item">
                        <a href="#tab-informasi" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-informasi" >
							<span class="nav-link__count">
								<i class="fa fa-info-circle"></i>
							</span>
                            Informasi Customer
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-rekening" class="nav-link" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-rekening" >
							<span class="nav-link__count">
								<i class="fa fa-credit-card"></i>
							</span>
                            Informasi Rekening
                        </a>
                    </li>
                </ul>
                <div class="card card-body tab-content">
                    <div class="tab-pane active show fade" id="tab-informasi">
                        <?php require 'form-dasar.php'; ?>
                    </div>
                    <div class="tab-pane show fade" id="tab-rekening">
                        <?php require 'form-rekening.php'; ?>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" id="btn_save_data" class="btn btn-success"><i class="fa fa-save"></i> Simpan - Keluar</button>
                        <button type="submit" id="btn_save_data_stay" class="btn btn-info"><i class="fa fa-save"></i> Simpan - Tetap disini</button>
                        <a href="<?php echo __HOSTNAME__; ?>/customer" class="btn btn-danger"><i class="fa fa-ban"></i> Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
