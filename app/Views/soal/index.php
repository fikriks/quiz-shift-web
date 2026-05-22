<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
Daftar Soal
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
      <!-- Filter by Level -->
      <div class="mb-3">
        <form method="GET" action="<?= site_url('soal') ?>">
          <input type="hidden" name="tab" id="form-tab-input" value="<?= esc($_GET['tab'] ?? 'ALL') ?>">
          <div class="row">
            <div class="col-md-3">
              <select name="level" class="form-select">
                <option value="">Semua Level</option>
                <?php foreach ($levels ?? [] as $level): ?>
                  <option value="<?= $level['id_level'] ?>" <?= (isset($_GET['level']) && $_GET['level'] == $level['id_level']) ? 'selected' : '' ?>>
                    <?= esc($level['nama_level']) ?> (<?= $level['nilai_min'] ?> - <?= $level['nilai_max'] ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-secondary">Filter</button>
              <a href="<?= site_url('soal') ?>" id="reset-filter-btn" class="btn btn-outline-secondary">Reset</a>
            </div>
          </div>
        </form>
      </div>

      <!-- Nav Tabs -->
      <?php if ($currentUser['hak_akses'] === 'ADMIN'): ?>
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
      <?php endif; ?>

      <!-- Questions Table -->
      <div class="table-responsive">
        <table class="table table-hover">
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
            <?php if (empty($soal ?? [])): ?>
              <tr id="empty-row">
                <td colspan="7" class="text-center text-muted">Belum ada soal</td>
              </tr>
            <?php else: ?>
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
              <tr data-jenjang="<?= esc($s['jenjang']) ?>">
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

function filterJenjang(jenjang) {
    // Check if the tabs container exists (e.g. only ADMIN has it)
    const tabsContainer = document.getElementById('jenjangFilterTabs');
    
    if (tabsContainer) {
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
                tr.innerHTML = `<td colspan="7" class="text-center text-muted">Belum ada soal untuk jenjang ini</td>`;
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

        // Update hidden form input
        const formTabInput = document.getElementById('form-tab-input');
        if (formTabInput) {
            formTabInput.value = jenjang;
        }

        // Update Reset button URL
        const resetBtn = document.getElementById('reset-filter-btn');
        if (resetBtn) {
            resetBtn.href = '<?= site_url('soal') ?>' + '?tab=' + jenjang;
        }
    }
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
