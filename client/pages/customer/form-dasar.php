<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Informasi Dasar</h5>
            </div>
            <div class="card-header card-header-tabs-basic nav" role="tablist">
                <a href="#info-dasar-1" class="active" data-toggle="tab" role="tab" aria-controls="asesmen-kerja" aria-selected="true">Umum</a>
                <a href="#info-dasar-2" data-toggle="tab" role="tab" aria-selected="false">Kependudukan</a>
                <a href="#info-dasar-3" data-toggle="tab" role="tab" aria-selected="false">Ahli Waris</a>
            </div>
            <div class="card-body tab-content">
                <div class="tab-pane show fade active" id="info-dasar-1">
                    <div class="row">
                        <div class="col-md-4">
                            <div id="image-uploader"></div>
                            <h6 class="text-center">
								<span class="custom-upload btn btn-info">
									<input type="file" name="" id="upload-image" />
									<i class="fa fa-upload"></i> Upload
								</span>
                            </h6>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="txt_nik">NIK:</label>
                                        <input type="text" class="form-control uppercase" id="txt_nik" placeholder="Nomor KTP" required />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_jenis_customer">Jenis Customer:</label>
                                        <select class="form-control" id="txt_jenis_customer">
                                            <option value="M">Member</option>
                                            <option value="S">Stokis</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="txt_nama">Nama:</label>
                                        <input type="text" class="form-control uppercase" id="txt_nama" placeholder="Nama Customer Sesuai KTP" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="txt_email">Email:</label>
                                        <input type="text" class="form-control" id="txt_email" placeholder="Email Customer" required />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="txt_tempat_lahir">Tempat Lahir:</label>
                                        <input type="text" class="form-control" id="txt_tempat_lahir" placeholder="Tempat Lahir" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="txt_tanggal_lahir">Tanggal Lahir:</label>
                                        <input type="text" class="form-control" id="txt_tanggal_lahir" placeholder="Tanggal Lahir" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="txt_wa">Nomor WhatsApp:</label>
                                        <input type="text" class="form-control" id="txt_wa" placeholder="Nomor WhatsApp Customer" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="txt_telp">Nomor Telpon:</label>
                                        <input type="text" class="form-control" id="txt_telp" placeholder="Nomor Telepon Customer" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="txt_domisili_provinsi">Provinsi Domisili:</label>
                                        <select class="form-control" id="txt_domisili_provinsi"></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="txt_domisili_kabupaten">Kabupaten Domisili:</label>
                                        <select class="form-control" id="txt_domisili_kabupaten"></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="txt_domisili_kecamatan">Kecamatan Domisili:</label>
                                        <select class="form-control" id="txt_domisili_kecamatan"></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="txt_domisili_kelurahan">Kelurahan Domisili:</label>
                                        <select class="form-control" id="txt_domisili_kelurahan"></select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="txt_kode">Alamat Domisili:</label>
                                        <textarea type="text" style="height: 200px;" class="form-control uppercase" id="txt_alamat_domisili" placeholder="Alamat Domisili Customer"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_rt">RT:</label>
                                        <input type="text" class="form-control" id="txt_rt" placeholder="RT" required />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_rw">RW:</label>
                                        <input type="text" class="form-control" id="txt_rw" placeholder="RW" required />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_domisili_kodepos">Kode Pos:</label>
                                        <input type="text" class="form-control" id="txt_domisili_kodepos" placeholder="Kode Pos Domisili" />
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="txt_mentor">Mentor:</label>
                                        <select class="form-control" id="txt_mentor"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="info-dasar-2">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="txt_ktp_provinsi">Provinsi (KTP):</label>
                                <select class="form-control" id="txt_ktp_provinsi"></select>
                            </div>
                            <div class="form-group">
                                <label for="txt_ktp_kabupaten">Kabupaten (KTP):</label>
                                <select class="form-control" id="txt_ktp_kabupaten"></select>
                            </div>
                            <div class="form-group">
                                <label for="txt_ktp_kecamatan">Kecamatan (KTP):</label>
                                <select class="form-control" id="txt_ktp_kecamatan"></select>
                            </div>
                            <div class="form-group">
                                <label for="txt_ktp_kelurahan">Kelurahan (KTP):</label>
                                <select class="form-control" id="txt_ktp_kelurahan"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="txt_kode">Alamat (KTP):</label>
                                <textarea type="text" style="height: 200px;" class="form-control uppercase" id="txt_ktp_alamat" placeholder="Alamat KTP Customer"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="txt_domisili_kodepos">Kode Pos (KTP):</label>
                                <input type="text" class="form-control" id="txt_ktp_kodepos" placeholder="Kode Pos KTP" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show fade" id="info-dasar-3">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="txt_ahli_waris_nama">Nama Ahli Waris:</label>
                                <input type="text" class="form-control uppercase" id="txt_ahli_waris_nama" placeholder="Nama Ahli Waris" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="txt_ahli_waris_hubungan">Hubungan:</label>
                                <input type="text" class="form-control uppercase" id="txt_ahli_waris_hubungan" placeholder="Hubungan" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="txt_ahli_waris_wa">Nomor WhatsApp:</label>
                                <input type="text" class="form-control" id="txt_ahli_waris_wa" placeholder="Nomor WhatsApp Ahli Waris" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="txt_ahli_waris_telp">Nomor Telpon:</label>
                                <input type="text" class="form-control" id="txt_ahli_waris_telp" placeholder="Nomor Telepon Ahli Waris" required />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>