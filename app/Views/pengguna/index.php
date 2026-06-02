<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
Daftar Pengguna
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h5>Daftar Instruktur</h5>
        <a href="<?= site_url('pengguna/create') ?>" class="btn btn-primary">
          <i class="ti ti-plus"></i> Tambah Instruktur
        </a>
      </div>
    </div>
    <div class="card-body">
      <!-- Nav Tabs -->
      <ul class="nav nav-pills mb-3" id="jenjangFilterTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="tab-all" type="button" onclick="filterJenjang('ALL')">
            <i class="ti ti-list"></i> Semua Jenjang
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="tab-elementary" type="button" onclick="filterJenjang('ELEMENTARY')">
            <i class="ti ti-mood-smile"></i> Elementary
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="tab-highschool" type="button" onclick="filterJenjang('HIGH_SCHOOL')">
            <i class="ti ti-school"></i> High School
          </button>
        </li>
      </ul>

      <div class="table-responsive">
        <table class="table table-hover" id="penggunaTable">
          <thead>
            <tr>
              <th width="50">No</th>
              <th>Username</th>
              <th>Nama Lengkap</th>
              <th>Jenjang</th>
              <th>Status</th>
              <th width="350">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach ($pengguna ?? [] as $p): ?>
            <tr data-jenjang="<?= esc($p['jenjang']) ?>">
              <td><?= $no++ ?></td>
              <td><?= esc($p['nama_pengguna']) ?></td>
              <td><?= esc($p['nama_lengkap']) ?></td>
              <td>
                <span class="badge <?= $p['jenjang'] === 'ELEMENTARY' ? 'bg-info' : 'bg-secondary' ?>">
                  <?= $p['jenjang'] === 'ELEMENTARY' ? 'ELEMENTARY' : 'HIGH SCHOOL' ?>
                </span>
              </td>
              <td>
                <span class="badge <?= $p['status'] === 'AKTIF' ? 'bg-success' : 'bg-danger' ?>">
                  <?= esc($p['status']) ?>
                </span>
              </td>
              <td>
                <a href="<?= site_url('pengguna/edit/' . $p['id_pengguna']) ?>" class="btn btn-sm btn-outline-primary">
                  <i class="ti ti-pencil"></i> Edit
                </a>
                <button type="button" class="btn btn-sm btn-outline-danger"
                        onclick="confirmDeletePengguna(<?= $p['id_pengguna'] ?>)">
                  <i class="ti ti-trash"></i> Hapus
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let table;

$(document).ready(function() {
    table = $('#penggunaTable').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "columnDefs": [
            { "orderable": false, "targets": [5] } // disable sorting on aksi
        ],
        "drawCallback": function(settings) {
            let api = this.api();
            api.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }
    });

    if (document.getElementById('jenjangFilterTabs')) {
        const urlParams = new URLSearchParams(window.location.search);
        const initialTab = urlParams.get('tab') || 'ALL';
        filterJenjang(initialTab);
    }
});

function confirmDeletePengguna(id) {
    toast.confirm(
        'Apakah Anda yakin ingin menghapus instruktur ini? Data yang dihapus tidak dapat dikembalikan.',
        function() {
            const form = document.createElement('form');
            form.method = 'POST';
            
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab') || 'ALL';
            form.action = '<?= site_url('pengguna/delete/') ?>' + id + '?tab=' + activeTab;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= csrf_token() ?>';
            csrfInput.value = '<?= csrf_hash() ?>';
            form.appendChild(csrfInput);

            document.body.appendChild(form);
            form.submit();
        },
        null,
        {
            title: 'Hapus Instruktur',
            confirmText: 'Hapus',
            confirmClass: 'btn-danger'
        }
    );
}

function filterJenjang(jenjang) {
    const tabsContainer = document.getElementById('jenjangFilterTabs');
    if (!tabsContainer || !table) return;

    // Update active class on tab buttons
    tabsContainer.querySelectorAll('.nav-link').forEach(btn => {
        btn.classList.remove('active');
    });
    
    if (jenjang === 'ALL') {
        const btn = document.getElementById('tab-all');
        if (btn) btn.classList.add('active');
        table.column(3).search('').draw();
    } else if (jenjang === 'ELEMENTARY') {
        const btn = document.getElementById('tab-elementary');
        if (btn) btn.classList.add('active');
        table.column(3).search('ELEMENTARY').draw();
    } else if (jenjang === 'HIGH_SCHOOL') {
        const btn = document.getElementById('tab-highschool');
        if (btn) btn.classList.add('active');
        table.column(3).search('HIGH SCHOOL').draw();
    }
    
    // Persist in URL query param
    const url = new URL(window.location.href);
    url.searchParams.set('tab', jenjang);
    window.history.replaceState(null, '', url);
}
</script>
<?= $this->endSection() ?>
