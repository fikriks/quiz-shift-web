<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
Hasil Kuis
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <h5>Daftar Hasil Kuis</h5>
    </div>
    <div class="card-body">
      <div class="row align-items-center mb-3">
        <div class="col">
          <?php if ($currentUser['hak_akses'] === 'ADMIN'): ?>
          <!-- Nav Tabs -->
          <ul class="nav nav-pills" id="jenjangFilterTabs" role="tablist">
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
          <?php endif; ?>
        </div>
        <div class="col-sm-auto mt-2 mt-sm-0">
          <div class="d-flex align-items-center">
            <div class="input-group input-group-sm" style="width: 200px;">
              <span class="input-group-text bg-light text-muted border-end-0">
                <i class="ti ti-filter"></i>
              </span>
              <select id="levelFilter" class="form-select border-start-0" onchange="applyFilters()">
                <option value="ALL">Semua Level</option>
                <option value="BEGINNER">BEGINNER</option>
                <option value="INTERMEDIATE">INTERMEDIATE</option>
                <option value="ADVANCED">ADVANCED</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover" id="hasilTable">
          <thead>
            <tr>
              <th width="50">No</th>
              <th>Nama Kuis</th>
              <th>Peserta</th>
              <th>Jenjang</th>
              <th>Waktu Mulai</th>
              <th>Waktu Selesai</th>
              <th>Nilai</th>
              <th>Level</th>
              <th width="200">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach ($hasil ?? [] as $h): ?>
            <tr data-jenjang="<?= esc($h['jenjang']) ?>" data-level="<?= esc($h['level_ditetapkan'] ?? '') ?>">
              <td><?= $no++ ?></td>
              <td><?= esc($h['nama_kuis']) ?></td>
              <td><?= esc($h['nama_lengkap']) ?></td>
              <td>
                <span class="badge <?= $h['jenjang'] === 'ELEMENTARY' ? 'bg-info' : 'bg-secondary' ?>">
                  <?= $h['jenjang'] === 'ELEMENTARY' ? 'ELEMENTARY' : 'HIGH SCHOOL' ?>
                </span>
              </td>
              <td><?= esc($h['waktu_mulai']) ?></td>
              <td><?= esc($h['waktu_selesai'] ?? '-') ?></td>
              <td>
                <span class="badge bg-primary"><?= esc($h['total_nilai']) ?></span>
              </td>
              <td>
                <?php if ($h['level_ditetapkan']): ?>
                  <?php
                  $levelClass = match($h['level_ditetapkan']) {
                    'BEGINNER' => 'bg-primary',
                    'INTERMEDIATE' => 'bg-warning text-dark',
                    'ADVANCED' => 'bg-danger',
                    default => 'bg-secondary'
                  };
                  ?>
                  <span class="badge <?= $levelClass ?>"><?= esc($h['level_ditetapkan']) ?></span>
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
              <td>
                <a href="<?= site_url('hasil/' . $h['id_kuis']) ?>" class="btn btn-sm btn-outline-primary">
                  <i class="ti ti-eye"></i> Detail
                </a>
                <?php if ($currentUser['hak_akses'] === 'ADMIN'): ?>
                <button type="button" class="btn btn-sm btn-outline-danger"
                        onclick="confirmDeleteHasil(<?= $h['id_kuis'] ?>)">
                  <i class="ti ti-trash"></i> Hapus
                </button>
                <?php endif; ?>
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
let currentJenjang = 'ALL';
let currentLevel = 'ALL';

$(document).ready(function() {
    table = $('#hasilTable').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "columnDefs": [
            { "orderable": false, "targets": [8] } // disable sorting on aksi
        ],
        "drawCallback": function(settings) {
            let api = this.api();
            api.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }
    });

    const urlParams = new URLSearchParams(window.location.search);
    const initialTab = urlParams.get('tab') || 'ALL';
    filterJenjang(initialTab);
});

function confirmDeleteHasil(id) {
    toast.confirm(
        'Apakah Anda yakin ingin menghapus hasil kuis ini? Data yang dihapus tidak dapat dikembalikan.',
        function() {
            const form = document.createElement('form');
            form.method = 'POST';
            
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab') || 'ALL';
            form.action = '<?= site_url('hasil/delete/') ?>' + id + '?tab=' + activeTab;

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
            title: 'Hapus Hasil',
            confirmText: 'Hapus',
            confirmClass: 'btn-danger'
        }
    );
}

function filterJenjang(jenjang) {
    currentJenjang = jenjang;
    
    const tabsContainer = document.getElementById('jenjangFilterTabs');
    if (tabsContainer) {
        tabsContainer.querySelectorAll('.nav-link').forEach(btn => {
            btn.classList.remove('active');
        });
        
        if (jenjang === 'ALL') {
            const btn = document.getElementById('tab-all');
            if (btn) btn.classList.add('active');
        } else if (jenjang === 'ELEMENTARY') {
            const btn = document.getElementById('tab-elementary');
            if (btn) btn.classList.add('active');
        } else if (jenjang === 'HIGH_SCHOOL') {
            const btn = document.getElementById('tab-highschool');
            if (btn) btn.classList.add('active');
        }
    }
    
    // Persist in URL query param
    const url = new URL(window.location.href);
    url.searchParams.set('tab', jenjang);
    window.history.replaceState(null, '', url);

    applyFilters();
}

function applyFilters() {
    if (!table) return;

    const levelSelect = document.getElementById('levelFilter');
    currentLevel = levelSelect ? levelSelect.value : 'ALL';
    
    // Filter Jenjang on column index 3
    if (currentJenjang === 'ALL') {
        table.column(3).search('');
    } else if (currentJenjang === 'ELEMENTARY') {
        table.column(3).search('ELEMENTARY');
    } else if (currentJenjang === 'HIGH_SCHOOL') {
        table.column(3).search('HIGH SCHOOL');
    }
    
    // Filter Level on column index 7
    if (currentLevel === 'ALL') {
        table.column(7).search('');
    } else {
        table.column(7).search(currentLevel);
    }
    
    table.draw();
}
</script>
<?= $this->endSection() ?>
