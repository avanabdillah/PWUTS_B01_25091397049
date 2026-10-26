$(function () {
  const $content = $('#app-content');
  const defaultSchedules = [
    {hari:'Senin',jam_mulai:'10:20',jam_selesai:'12:00',nama_mk:'Pemrograman WEB',sks:2,dosen:'Ari Kurniawan, S.Kom., M.T.',ruangan:'K10.02.02',kelas:'2025B'},
    {hari:'Senin',jam_mulai:'13:00',jam_selesai:'14:40',nama_mk:'Prak. Pemrograman WEB',sks:2,dosen:'Ari Kurniawan, S.Kom., M.T.',ruangan:'K10.02.02',kelas:'2025B'},
    {hari:'Senin',jam_mulai:'15:30',jam_selesai:'17:10',nama_mk:'Analisis dan Disain Perangkat Lunak',sks:2,dosen:'M. Adamu Islam Mashuri, S.Tr.T., M.Tr.Kom.',ruangan:'K02.01.03',kelas:'2025B'},
    {hari:'Senin',jam_mulai:'17:10',jam_selesai:'18:00',nama_mk:'Prak. Analisis dan Disain Perangkat Lunak',sks:1,dosen:'M. Adamu Islam Mashuri, S.Tr.T., M.Tr.Kom.',ruangan:'K02.01.03',kelas:'2025B'},
    {hari:'Selasa',jam_mulai:'07:00',jam_selesai:'08:40',nama_mk:'Kewarganegaraan',sks:2,dosen:'Dr. Agus Machfud Fauzi, M.Si.',ruangan:'VIRTUAL',kelas:'PK001'},
    {hari:'Selasa',jam_mulai:'14:40',jam_selesai:'16:20',nama_mk:'Pemrograman Game',sks:2,dosen:'Moch. Badrus Sholeh, S.Kom., M.Kom.',ruangan:'K10.02.06',kelas:'2025B'},
    {hari:'Selasa',jam_mulai:'16:20',jam_selesai:'18:00',nama_mk:'Prak. Pemrograman Game',sks:2,dosen:'Moch. Badrus Sholeh, S.Kom., M.Kom.',ruangan:'K10.02.06',kelas:'2025B'},
    {hari:'Rabu',jam_mulai:'09:30',jam_selesai:'12:00',nama_mk:'Kecerdasan Buatan',sks:3,dosen:'Salamun Rohman Nudin, S.Kom., M.Kom.',ruangan:'K02.01.03',kelas:'2025B'},
    {hari:'Kamis',jam_mulai:'10:20',jam_selesai:'12:00',nama_mk:'Basis Data Lanjut',sks:2,dosen:'Asmunin, S.Kom., M.Kom.',ruangan:'K10.02.06',kelas:'2025B'},
    {hari:'Kamis',jam_mulai:'13:00',jam_selesai:'14:40',nama_mk:'Prak. Basis Data Lanjut',sks:2,dosen:'Asmunin, S.Kom., M.Kom.',ruangan:'K10.02.06',kelas:'2025B'},
    {hari:'Jumat',jam_mulai:'10:20',jam_selesai:'12:00',nama_mk:'Pemrograman Berorientasi Objek',sks:2,dosen:'Faris Abdi El Hakim, S.Kom., M.Tr.Kom.',ruangan:'K10.02.09',kelas:'2025B'},
    {hari:'Jumat',jam_mulai:'13:00',jam_selesai:'14:40',nama_mk:'Prak. Pemrograman Berorientasi Objek',sks:2,dosen:'Faris Abdi El Hakim, S.Kom., M.Tr.Kom.',ruangan:'K10.02.09',kelas:'2025B'}
  ];
  const defaultTasks = [
    {id:1,judul:'Praktikum HTML dan CSS',mataKuliah:'Prak. Pemrograman WEB',deskripsi:'Membuat halaman web responsif sesuai instruksi praktikum.',deadline:'2026-11-02T23:59',link:'',status:'Belum dikerjakan'},
    {id:2,judul:'Latihan Query SQL',mataKuliah:'Basis Data Lanjut',deskripsi:'Menyelesaikan latihan query dan dokumentasi hasil.',deadline:'2026-10-30T20:00',link:'',status:'Sedang dikerjakan'},
    {id:3,judul:'Analisis Kebutuhan Sistem',mataKuliah:'Analisis dan Disain Perangkat Lunak',deskripsi:'Menyusun kebutuhan fungsional dan nonfungsional.',deadline:'2026-11-05T23:59',link:'',status:'Belum dikerjakan'}
  ];
  let tasks = JSON.parse(localStorage.getItem('classplan_tasks') || 'null') || defaultTasks;
  const days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
  function esc(v){return String(v ?? '').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));}
  function saveTasks(){localStorage.setItem('classplan_tasks',JSON.stringify(tasks));}
  function loadPage(url, push=true){
    if(!url || !url.endsWith('.html')) return;
    $content.html('<main class="container inner-page"><div class="loading-state">Memuat halaman...</div></main>');
    $.ajax({url:url,method:'GET',dataType:'html'})
      .done(html=>{$content.html(html);if(push)history.pushState({url},'', '#'+url);$('.nav-links a').removeClass('active').filter(`[href="${url}"]`).addClass('active');$('#navLinks').removeClass('is-open');$('#navToggle').attr('aria-expanded','false');window.scrollTo({top:0,behavior:'smooth'});initPage();})
      .fail(()=>{$content.html('<main class="container inner-page"><div class="empty-state"><h3>Halaman tidak dapat dimuat</h3><p>Jalankan project melalui server lokal atau hosting agar AJAX dapat membaca file HTML.</p></div></main>');});
  }
  $(document).on('click','a.ajax-link',function(e){const href=$(this).attr('href');if(href&&href.endsWith('.html')){e.preventDefault();loadPage(href,true);}});
  $('#navToggle').on('click',function(){const open=$('#navLinks').toggleClass('is-open').hasClass('is-open');$(this).attr('aria-expanded',open?'true':'false');});
  window.addEventListener('popstate',function(){const url=location.hash.slice(1);if(url.endsWith('.html'))loadPage(url,false);});
  function renderSchedules(){
    const q=($('#searchSchedule').val()||'').toLowerCase(), day=$('#filterDay').val()||'Semua Hari', sort=$('#sortSchedule').val()||'day';
    let rows=defaultSchedules.filter(r=>(day==='Semua Hari'||r.hari===day)&&[r.nama_mk,r.dosen,r.ruangan,r.hari].join(' ').toLowerCase().includes(q));
    rows.sort((a,b)=>sort==='name'?a.nama_mk.localeCompare(b.nama_mk):sort==='time'?a.jam_mulai.localeCompare(b.jam_mulai):days.indexOf(a.hari)-days.indexOf(b.hari)||a.jam_mulai.localeCompare(b.jam_mulai));
    $('#resultsCount').text(rows.length+' jadwal ditemukan');
    if(!rows.length){$('#scheduleResults').html('<div class="empty-state"><span class="empty-symbol">⌕</span><h3>Jadwal tidak ditemukan</h3><p>Coba kata kunci atau filter hari yang berbeda.</p></div>');return;}
    let html='<div class="table-scroll"><table class="schedule-table"><thead><tr><th>Hari & Waktu</th><th>Mata Kuliah</th><th>SKS</th><th>Dosen Pengampu</th><th>Ruangan</th><th>Kelas</th></tr></thead><tbody>';
    rows.forEach(r=>html+=`<tr><td><span class="day-pill">${esc(r.hari)}</span><span class="time-range">${esc(r.jam_mulai)} – ${esc(r.jam_selesai)}</span></td><td><strong class="subject-name">${esc(r.nama_mk)}</strong></td><td>${esc(r.sks)}</td><td>${esc(r.dosen)}</td><td><span class="room-label">⌖ ${esc(r.ruangan)}</span></td><td><span class="class-chip">${esc(r.kelas)}</span></td></tr>`);
    $('#scheduleResults').html(html+'</tbody></table></div>');
  }
  function renderTasks(){
    const q=($('#searchTask').val()||'').toLowerCase(), status=$('#filterStatus').val()||'Semua';
    const rows=tasks.filter(t=>(status==='Semua'||t.status===status)&&[t.judul,t.mataKuliah,t.deskripsi].join(' ').toLowerCase().includes(q)).sort((a,b)=>new Date(a.deadline)-new Date(b.deadline));
    $('#taskCount').text(rows.length+' tugas ditemukan');
    if(!rows.length){$('#taskResults').html('<div class="empty-state"><h3>Tugas tidak ditemukan</h3><p>Coba kata kunci atau status yang berbeda.</p></div>');return;}
    $('#taskResults').html(rows.map(t=>{const overdue=new Date(t.deadline)<new Date()&&t.status!=='Selesai';const cls=t.status==='Selesai'?'done':t.status==='Sedang dikerjakan'?'progress':'todo';return `<article class="task-card"><div class="task-card-top"><span class="task-course">${esc(t.mataKuliah)}</span><span class="task-status status-${cls}">${esc(t.status)}</span></div><h2>${esc(t.judul)}</h2><p>${esc(t.deskripsi||'Tidak ada deskripsi tambahan.')}</p><div class="deadline-row"><span>◷ Deadline</span><strong class="${overdue?'overdue':''}">${new Date(t.deadline).toLocaleString('id-ID',{dateStyle:'medium',timeStyle:'short'})}</strong></div><div class="task-actions">${t.link?`<a class="button button-ghost" href="${esc(t.link)}" target="_blank" rel="noopener noreferrer">Buka tautan ↗</a>`:'<span class="muted-small">Tautan belum tersedia</span>'}<label class="muted-small">Status <select class="task-status-select" data-id="${t.id}" aria-label="Status tugas"><option ${t.status==='Belum dikerjakan'?'selected':''}>Belum dikerjakan</option><option ${t.status==='Sedang dikerjakan'?'selected':''}>Sedang dikerjakan</option><option ${t.status==='Selesai'?'selected':''}>Selesai</option></select></label></div></article>`;}).join(''));
  }
  function initPage(){
    if($('#scheduleResults').length){$('#searchSchedule,#filterDay,#sortSchedule').off('.schedule');$('#searchSchedule').on('input.schedule',renderSchedules);$('#filterDay,#sortSchedule').on('change.schedule',renderSchedules);renderSchedules();}
    if($('#taskResults').length){$('#searchTask,#filterStatus').off('.tasks');$('#searchTask').on('input.tasks',renderTasks);$('#filterStatus').on('change.tasks',renderTasks);renderTasks();}
    if($('#taskForm').length){
      $('#taskForm').off('submit').on('submit',function(e){
        e.preventDefault();$('.field-error').text('');$('#formMessage').prop('hidden',true).removeClass('success error').text('');
        let valid=true;
        const judul=$('#judul').val().trim(), mataKuliah=$('#mataKuliah').val(), deadline=$('#deadline').val(), link=$('#link').val().trim();
        if(!judul){$('#judul').closest('.form-field').find('.field-error').text('Judul tugas wajib diisi.');valid=false;}
        if(!mataKuliah){$('#mataKuliah').closest('.form-field').find('.field-error').text('Pilih mata kuliah.');valid=false;}
        if(!deadline||Number.isNaN(new Date(deadline).getTime())){$('#deadline').closest('.form-field').find('.field-error').text('Deadline wajib diisi dengan benar.');valid=false;}
        if(link){try{const u=new URL(link);if(!['http:','https:'].includes(u.protocol))throw new Error();}catch(_){$('#link').closest('.form-field').find('.field-error').text('Masukkan URL yang diawali http:// atau https://.');valid=false;}}
        if(!valid){$('#formMessage').prop('hidden',false).addClass('error').text('Periksa kembali kolom yang ditandai.');return;}
        tasks.push({id:Date.now(),judul,mataKuliah,deadline,link,deskripsi:$('#deskripsi').val().trim(),status:'Belum dikerjakan'});saveTasks();
        $('#formMessage').prop('hidden',false).addClass('success').text('Tugas berhasil ditambahkan ke daftar simulasi pada browser ini.');
        this.reset();
      });
    }
  }
  $(document).on('change','.task-status-select',function(){const id=Number($(this).data('id'));const task=tasks.find(t=>t.id===id);if(task){task.status=$(this).val();saveTasks();renderTasks();}});
  initPage();
});
