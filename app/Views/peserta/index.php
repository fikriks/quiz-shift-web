<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
Daftar Peserta
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h5>Daftar Peserta</h5>
        <a href="<?= site_url('peserta/create') ?>" class="btn btn-primary">
          <i class="ti ti-plus"></i> Tambah Peserta
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
        <table class="table table-hover">
          <thead>
            <tr>
              <th width="50">No</th>
              <th>Username</th>
              <th>Nama Lengkap</th>
              <th>Email</th>
              <th>No. HP</th>
              <th>Jenjang</th>
              <th>Status</th>
              <th width="350">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($peserta ?? [])): ?>
              <tr id="empty-row">
                <td colspan="8" class="text-center text-muted">Belum ada peserta</td>
              </tr>
            <?php else: ?>
              <?php $no = 1; foreach ($peserta ?? [] as $p): ?>
              <tr data-jenjang="<?= esc($p['jenjang']) ?>">
                <td><?= $no++ ?></td>
                <td><?= esc($p['username']) ?></td>
                <td><?= esc($p['nama_lengkap']) ?></td>
                <td><?= esc($p['email']) ?></td>
                <td><?= esc($p['no_hp'] ?? '-') ?></td>
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
                  <a href="<?= site_url('peserta/edit/' . $p['id_peserta']) ?>" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-pencil"></i> Edit
                  </a>
                  <button type="button" class="btn btn-sm btn-outline-danger"
                          onclick="confirmDeletePeserta(<?= $p['id_peserta'] ?>)">
                    <i class="ti ti-trash"></i> Hapus
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmDeletePeserta(id) {
    toast.confirm(
        'Apakah Anda yakin ingin menghapus peserta ini? Data yang dihapus tidak dapat dikembalikan.',
        function() {
            const form = document.createElement('form');
            form.method = 'POST';
            
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab') || 'ALL';
            form.action = '<?= site_url('peserta/delete/') ?>' + id + '?tab=' + activeTab;

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
            title: 'Hapus Peserta',
            confirmText: 'Hapus',
            confirmClass: 'btn-danger'
        }
    );
}

function filterJenjang(jenjang) {
    const tabsContainer = document.getElementById('jenjangFilterTabs');
    if (!tabsContainer) return;

    // Update active class on tab buttons
    tabsContainer.querySelectorAll('.nav-link').forEach(btn => {
        btn.classList.remove('active');
    });
    
    if (jenjang === 'ALL') {
        const btn = document.getElementById('tab-all');
        if (btn) btn.classList.add('active');
        document.querySelectorAll('tbody tr[data-jenjang]').forEach(tr => {
            tr.style.display = '';
        });
    } else if (jenjang === 'ELEMENTARY') {
        const btn = document.getElementById('tab-elementary');
        if (btn) btn.classList.add('active');
        document.querySelectorAll('tbody tr[data-jenjang]').forEach(tr => {
            if (tr.getAttribute('data-jenjang') === 'ELEMENTARY') {
                tr.style.display = '';
            } else {
                tr.style.display = 'none';
            }
        });
    } else if (jenjang === 'HIGH_SCHOOL') {
        const btn = document.getElementById('tab-highschool');
        if (btn) btn.classList.add('active');
        document.querySelectorAll('tbody tr[data-jenjang]').forEach(tr => {
            if (tr.getAttribute('data-jenjang') === 'HIGH_SCHOOL') {
                tr.style.display = '';
            } else {
                tr.style.display = 'none';
            }
        });
    }
    
    // Update row numbers dynamically
    let visibleIndex = 1;
    document.querySelectorAll('tbody tr[data-jenjang]').forEach(tr => {
        if (tr.style.display !== 'none') {
            tr.querySelector('td:first-child').textContent = visibleIndex++;
        }
    });
    
    // Manage empty row
    const emptyRow = document.getElementById('empty-row');
    const totalVisible = Array.from(document.querySelectorAll('tbody tr[data-jenjang]')).filter(tr => tr.style.display !== 'none').length;
    if (totalVisible === 0) {
        if (!emptyRow) {
            const tr = document.createElement('tr');
            tr.id = 'empty-row';
            tr.innerHTML = `<td colspan="8" class="text-center text-muted">Belum ada peserta untuk jenjang ini</td>`;
            document.querySelector('tbody').appendChild(tr);
        } else {
            emptyRow.style.display = '';
        }
    } else {
        if (emptyRow) {
            emptyRow.style.display = 'none';
        }
    }

    // Persist in URL query param
    const url = new URL(window.location.href);
    url.searchParams.set('tab', jenjang);
    window.history.replaceState(null, '', url);
}

// On page load, check URL parameter
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('jenjangFilterTabs')) {
        const urlParams = new URLSearchParams(window.location.search);
        const initialTab = urlParams.get('tab') || 'ALL';
        filterJenjang(initialTab);
    }
});
</script>
<?= $this->endSection() ?>
