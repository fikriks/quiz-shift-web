<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
Daftar Soal
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  .dataTables_wrapper .dataTables_paginate .paginate_button {
      padding: 0 !important;
      margin: 0 !important;
  }
  .dataTables_filter {
      margin-bottom: 15px;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h5>Daftar Soal</h5>
        <?php if (in_array($currentUser['hak_akses'] ?? '', ['ADMIN', 'INSTRUKTUR'])): ?>
        <a href="<?= site_url('soal/create') ?>" class="btn btn-primary">
          <i class="ti ti-plus"></i> Tambah Soal
        </a>
        <?php endif; ?>
      </div>
    </div>
    <div class="card-body">
      <!-- Nav Tabs and Filter Level -->
      <div class="row align-items-center mb-3">
        <div class="col">
          <?php if ($currentUser['hak_akses'] === 'ADMIN'): ?>
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

      <!-- Questions Table -->
      <div class="table-responsive">
        <table class="table table-hover" id="soalTable">
          <thead>
            <tr>
              <th width="50">No</th>
              <th>Pertanyaan</th>
              <th width="120">Jenjang</th>
              <th width="100">Level</th>
              <th width="100">Jawaban</th>
              <th width="100">Status</th>
              <th width="250">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $no = 1;
              $badgeColors = [
                  'BEGINNER'     => 'bg-primary',
                  'INTERMEDIATE' => 'bg-warning',
                  'ADVANCED'     => 'bg-danger'
              ];
              foreach ($soal ?? [] as $s):
                $badgeClass = $badgeColors[$s['nama_level']] ?? 'bg-secondary';
            ?>
            <tr data-jenjang="<?= esc($s['jenjang']) ?>" data-level="<?= esc($s['nama_level']) ?>">
              <td><?= $no++ ?></td>
              <td>
                <div class="question-text"><?= character_limiter(strip_tags($s['pertanyaan']), 100) ?></div>
              </td>
              <td>
                <span class="badge <?= $s['jenjang'] === 'ELEMENTARY' ? 'bg-info' : 'bg-secondary' ?>">
                  <?= $s['jenjang'] === 'ELEMENTARY' ? 'ELEMENTARY' : 'HIGH SCHOOL' ?>
                </span>
              </td>
              <td><span class="badge <?= $badgeClass ?>"><?= esc($s['nama_level']) ?></span></td>
              <td><span class="badge bg-primary"><?= esc($s['jawaban_benar']) ?></span></td>
              <td>
                <span class="badge <?= $s['status'] === 'AKTIF' ? 'bg-success' : 'bg-danger' ?>">
                  <?= esc($s['status']) ?>
                </span>
              </td>
              <td>
                <?php if (in_array($currentUser['hak_akses'] ?? '', ['ADMIN', 'INSTRUKTUR'])): ?>
                <a href="<?= site_url('soal/edit/' . $s['id_soal']) ?>" class="btn btn-sm btn-outline-primary">
                  <i class="ti ti-pencil"></i> Edit
                </a>
                <button type="button" class="btn btn-sm btn-outline-danger"
                        onclick="confirmDeleteSoal(<?= $s['id_soal'] ?>)">
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

$(document).ready(function() {
    table = $('#soalTable').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "columnDefs": [
            { "orderable": false, "targets": [1, 6] } // disable sorting on pertanyaan & aksi
        ],
        "drawCallback": function(settings) {
            // Apply dynamic index numbers
            table.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
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

function confirmDeleteSoal(id) {
    toast.confirm(
        'Apakah Anda yakin ingin menghapus soal ini? Data yang dihapus tidak dapat dikembalikan.',
        function() {
            const form = document.createElement('form');
            form.method = 'POST';
            
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab') || 'ALL';
            form.action = '<?= site_url('soal/delete/') ?>' + id + '?tab=' + activeTab;

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
            title: 'Hapus Soal',
            confirmText: 'Hapus',
            confirmClass: 'btn-danger'
        }
    );
}

let currentJenjang = 'ALL';
let currentLevel = 'ALL';

function filterJenjang(jenjang) {
    currentJenjang = jenjang;
    
    const tabsContainer = document.getElementById('jenjangFilterTabs');
    if (tabsContainer) {
        // Update active class on tab buttons
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
    
    // Filter Jenjang on column index 2
    if (currentJenjang === 'ALL') {
        table.column(2).search('');
    } else if (currentJenjang === 'ELEMENTARY') {
        table.column(2).search('ELEMENTARY');
    } else if (currentJenjang === 'HIGH_SCHOOL') {
        table.column(2).search('HIGH SCHOOL');
    }
    
    // Filter Level on column index 3 (which holds level badge)
    if (currentLevel === 'ALL') {
        table.column(3).search('');
    } else {
        table.column(3).search(currentLevel);
    }
    
    table.draw();
}
</script>
<?= $this->endSection() ?>
