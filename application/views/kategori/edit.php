<div class="col-md-12 col-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><?= $title ?></h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <span>Nama Kategori Artikel</span>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="hidden" name="id_kproduk" value="<?= $row->id_kproduk ?>">
                                        <input type="text" id="first-name" class="form-control" name="namaKategori" value="<?= $this->input->post('namaKategori') ?? $row->nama ?>" placeholder="Nama Barang">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary mr-1 mb-1">Submit</button>
                                <button type="reset" class="btn btn-outline-warning mr-1 mb-1">Reset</button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>