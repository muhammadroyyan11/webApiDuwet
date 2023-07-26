<div class="col-md-12 col-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Edit Data User</h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <?= $this->session->flashdata('pesan'); ?>
                <?= form_open('admin/user/prosesEdit', [], ['id_user' => $user['id_user']]); ?>
                <div class="form-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <span>Username</span>
                                </div>
                                <div class="col-md-8">
                                    <div class="position-relative has-icon-left">
                                        <input type="text" id="username" class="form-control" name="username" placeholder="Masukkan username" value="<?= set_value('username', $user['username']); ?>">
                                        <?= form_error('username', '<span class="text-danger small">', '</span>'); ?>
                                        <div class="form-control-position">
                                            <i class="feather icon-user"></i>
                                        </div>
                                        <input type="hidden" id="id_user" class="form-control" name="id_user" placeholder="Masukkan username" value="<?= set_value('id_user', $user['id_user']); ?>">
                                        <?= form_error('id_user', '<span class="text-danger small">', '</span>'); ?>
                                        <div class="form-control-position">
                                            <i class="feather icon-user"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <span>Nama Lengkap</span>
                                </div>
                                <div class="col-md-8">
                                    <div class="position-relative has-icon-left">
                                        <input type="text" id="nama" class="form-control" name="nama" placeholder="Masukkan nama" value="<?= set_value('name', $user['name']); ?>">
                                        <?= form_error('nama', '<span class="text-danger small">', '</span>'); ?>
                                        <div class="form-control-position">
                                            <i class="feather icon-user"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <span>Email</span>
                                </div>
                                <div class="col-md-8">
                                    <div class="position-relative has-icon-left">
                                        <input type="email" id="email" class="form-control" name="email" placeholder="Masukkan email" value="<?= set_value('email', $user['email']); ?>">
                                        <?= form_error('email', '<span class="text-danger small">', '</span>'); ?>
                                        <div class="form-control-position">
                                            <i class="feather icon-mail"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <span>No Tlp</span>
                                </div>
                                <div class="col-md-8">
                                    <div class="position-relative has-icon-left">
                                        <input type="no_telp" id="no_telp" class="form-control" name="no_telp" placeholder="Masukkan No Telp" value="<?= set_value('no_telp', $user['no_telp']); ?>">
                                        <?= form_error('no_telp', '<span class="text-danger small">', '</span>'); ?>
                                        <div class="form-control-position">
                                            <i class="feather icon-mail"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <span>Role</span>
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-control custom-radio">
                                        <input <?= $user['role'] == 'admin' ? 'checked' : ''; ?> <?= set_radio('role', 'admin'); ?> value="admin" type="radio" id="admin" name="role" class="custom-control-input">
                                        <label class="custom-control-label" for="admin">Admin</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input <?= $user['role'] == 'gudang' ? 'checked' : ''; ?> <?= set_radio('role', 'gudang'); ?> value="gudang" type="radio" id="gudang" name="role" class="custom-control-input">
                                        <label class="custom-control-label" for="gudang">Gudang</label>
                                    </div>
                                    <?= form_error('role', '<span class="text-danger small">', '</span>'); ?>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary mr-1 mb-1">Submit</button>
                            <button type="reset" class="btn btn-outline-warning mr-1 mb-1">Reset</button>
                        </div>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>