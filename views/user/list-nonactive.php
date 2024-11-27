<?php $this->layout('layouts/base') ?>
<?php $this->start('footAdditional') ?>
<script>
    new DataTable('#usertable', {
        layout: {
            topStart: {
                buttons: ['excel']
            }
        },
        order: [[0, 'asc']]
    });

    $(document).on('click', '.btn-actived', function (e) {
        e.preventDefault();
        const url = $(this).data('url');

        Swal.fire({
            title: "Yakin untuk mengaktifkan kembali akun?",
            text: "Data akan dikembalikan menjadi user aktif!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Aktivasi",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
</script>
<?php $this->stop() ?>

<div class="container">
    <div class="card my-2">
    <div class="card-body">
          <div>
            <p>Hello, <b><?= $dataUser->fullname ?></b></p>
          </div>
            <?php if ($alert): ?>
                <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
          <div class="table-responsive">
            <table id="usertable">
              <thead>
                  <tr>
                      <th>ID</th>
                      <th>Fullname</th>
                      <th>Email</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($users as $key => $value) { ?>
                  <tr>
                      <td><?= $value[0] ?></td>
                      <td><?= $value[3] ?></td>
                      <td><?= $value[5] ?></td>
                      <td>
                        <a href="#" data-url="<?= BASE_URL ?>/user/actived/<?= $value[0] ?>" class="btn btn-dark btn-actived">Actived</a>
                      </td>
                  </tr>
                  <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
    </div>
</div>