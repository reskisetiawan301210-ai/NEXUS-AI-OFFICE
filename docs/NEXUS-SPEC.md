## CATATAN PRIORITAS TERTINGGI (menimpa bagian lain jika bertentangan)
- Kamu TIDAK bisa melihat gambar/video dan TIDAK punya browser bawaan.
  Lewati Fase A (bagian 1) sepenuhnya: jangan ekstrak frame, jangan buat
  reference-motion-spec.md atau pinned-measurements.md. Angka di bagian 7
  adalah sumber kebenaran final.
- Bagian 12 diganti: verifikasi hanya lewat angka (skrip/Playwright:
  getBoundingClientRect, getComputedStyle, document.fonts.check,
  scrollWidth vs clientWidth, kontras). Jangan klaim kemiripan visual;
  pemilik yang menilai secara visual.
- Kriteria bagian 14 yang butuh pengamatan visual cukup dipenuhi lewat
  kode sesuai spesifikasi dan ukuran terukur.
- Kerjakan HANYA tahap yang diminta di chat. Jangan lanjut ke tahap lain.

[# NEXUS AI OFFICE: REKONSTRUKSI VISUAL LANDING PAGE (MENIRU WEBSITE REFERENSI SEDEKAT MUNGKIN)

## 0. PERAN DAN ATURAN KERJA

Bertindaklah sebagai Senior Frontend Engineer, Creative Developer, UI/UX Designer, dan Motion Designer profesional.

Ini bukan proyek baru. Lanjutkan landing page NEXUS AI OFFICE yang sudah ada dan tingkatkan kualitasnya secara menyeluruh. Target: sedekat mungkin dengan website referensi ZYWRA (https://zywra.com/) dalam layout, proporsi, tipografi, warna, animasi scroll, dan interaksi. Bukan sekadar terinspirasi, bukan desain SaaS generik, dan jangan membuat desain baru yang berbeda dari referensi.

Aturan keras:
1. Jangan berhenti di rencana dan jangan minta persetujuan rencana. Kerjakan langsung sampai semua kriteria lulus di bagian 14.
2. Build sukses BUKAN bukti desain benar. Yang menentukan adalah perbandingan visual dengan video referensi.
3. Jangan mengarang detail yang tidak terlihat di referensi. Tandai [TIDAK PASTI], pilih keputusan terdekat dengan referensi, lalu laporkan di akhir.
4. Jangan menyatakan "pixel-perfect", "100% identik", atau "sempurna" tanpa bukti screenshot dan ukuran terukur. Laporkan selisih yang tersisa apa adanya.
5. Jangan menyalin teks, logo, gambar, kode, atau animasi (Lottie/video) milik ZYWRA. Semua aset dan animasi dibangun ulang secara orisinal.
6. Jika ada bagian yang buruk, perbaiki sampai bagus. Kualitas visual adalah prioritas nomor satu. Periksa sendiri kekurangan lain yang belum disebut dan perbaiki tanpa menunggu saya menunjuknya satu per satu.

## 1. FASE A: PELAJARI DAN MATANGKAN ISI VIDEO REFERENSI (WAJIB, SEBELUM MENULIS KODE)

Video referensi utama (±45 detik, 1920x972, 30fps):
`C:\Users\ACER\Videos\Screen Recordings\Screen Recording 2026-10-04 184051.mp4`

Video referensi tambahan jika ada (±38 detik, rekaman lain dari situs ZYWRA yang sama, memperlihatkan pergerakan scroll pertama dengan sudut pandang berbeda):
`C:\Users\ACER\Videos\Screen Recordings\Screen Recording 2026-10-04 085608.mp4`

Langkah wajib:
1. Cek apakah file video bisa dibuka. Jika TIDAK bisa, berhenti dan beri tahu saya; jangan berpura-pura sudah melihatnya. Saya akan menyalinnya ke folder project.
2. Ekstrak frame dengan terminal (ffmpeg; jika belum ada: `winget install Gyan.FFmpeg`, atau pakai Python OpenCV):
   `ffmpeg -i "<path video>" -vf fps=3 docs\ref-frames\f_%03d.png`
   Untuk bagian animasi cepat (hero berputar, section pinned, scene dalam kartu) ekstrak ulang dengan fps=6 ke `docs\ref-frames\pinned\`.
3. Boleh membuka zywra.com di browser HANYA untuk mengamati tampilan dan perilaku animasi. Jangan scrape, unduh, atau menyalin aset, font, atau kode. Jika situs menolak akses otomatis, andalkan video.
4. Tulis `docs/reference-motion-spec.md`: urutan section, timeline per frame, ukuran elemen (dalam % lebar dan % tinggi viewport), warna latar tiap tahap, posisi teks, urutan scene, easing yang terlihat.
5. Tulis `docs/pinned-measurements.md`: tabel dengan kolom timestamp, lebar kartu (% viewport), tinggi kartu (% viewport), warna latar (hex perkiraan), scene yang tampil. Dari tabel itu tentukan apakah isi kartu ditentukan oleh scroll atau berjalan dengan waktunya sendiri. Hipotesis saya: ukuran kartu, warna latar, dan posisi headline = scroll-scrubbed; isi kartu = animasi berurutan berjalan sendiri (karena di dua rekaman, kartu berukuran sama menampilkan scene berbeda). Jika buktimu berbeda, ikuti buktimu dan catat perbedaannya.
6. Bandingkan temuanmu dengan spesifikasi di bagian 7 (hasil analisis saya). Jika ada selisih, utamakan ukuran yang kamu ukur sendiri dari frame, dan catat selisihnya.
Setelah kelima dokumen/langkah ini selesai, baru mulai coding.

## 2. PROJECT EXISTING (PERTAHANKAN)

Folder project: `C:\Users\ACER\OneDrive\lifehub-ai-v2`
Teknologi: Laravel 13, Blade, CSS, JavaScript, Vite, authentication existing.
File utama: `resources/views/landing.blade.php`, `resources/css/landing.css`, `resources/js/landing.js`.
Cek `package.json`: jika GSAP, ScrollTrigger, dan Lenis sudah ada, gunakan dan perbaiki implementasinya. Jangan tambah library baru kecuali benar-benar perlu (laporkan alasannya). Tidak ada Three.js; jangan tambahkan.

Pertahankan: login, dashboard, authentication, route existing, controller, model, database, backend, dan semua fungsi yang sudah berjalan.
Jangan: menjalankan `php artisan migrate:fresh`; mengubah Kasira atau backend yang tidak terkait landing; mengubah identifier internal hanya karena brand berubah.

Progres agen sebelumnya (OpenCode) terhenti karena token habis. Ia melaporkan perubahan pada Blade, CSS, JS, font, navbar, dan hero, tetapi laporan itu BUKAN bukti. Langkah wajib:
1. Periksa kode aktual dan catat apa yang benar-benar tersimpan.
2. Identifikasi bagian yang belum selesai, rusak, atau tidak sesuai referensi.
3. Pertahankan yang sudah bagus (termasuk animasi existing yang bagus), perbaiki yang buruk.
4. Jangan mulai dari nol tanpa alasan dan jangan mengulang analisis tanpa menghasilkan perubahan nyata.

## 3. BRAND DAN KONTEN (JANGAN MENGARANG)

Nama lama: LifeHub AI. Nama sekarang: NEXUS AI OFFICE. Semua identitas yang tampil ke pengguna memakai NEXUS AI OFFICE.
NEXUS AI OFFICE adalah ekosistem digital workspace yang mengintegrasikan AI, produktivitas, dan berbagai tools dalam satu tempat.
Produk dan modul: NEXUS AI, Music, Downloader, Kasira (sistem POS untuk UMKM), Alat Produktivitas (Notes, Focus, Finance, QR Tools, Mastery/Learning). AURA GLOBAL = layanan pengembangan website profesional.

Dilarang mengarang statistik, jumlah pengguna, klien, rating bintang, testimoni, atau klaim fitur. Gunakan teks yang sudah ada di halaman; status TERSEDIA/SEGERA tetap dari data asli.
Semua kredit di tempat publik (footer, kartu prinsip, dan di mana pun): "Dibuat oleh Team NEXUS". Hapus semua kredit "Reski Setiawan" yang tampil ke pengguna (cari di seluruh landing).
CTA "Masuk" memakai route login yang sudah ada.

## 4. KONDISI SEKARANG DAN AKAR MASALAH (SEMUA INI HARUS HILANG)

Hasil implementasi sebelumnya masih jauh dari referensi. Masalah yang terlihat:
- Hero: headline tipis seperti Arial (font tidak termuat), terlalu sempit sehingga terbungkus ("DALAM SATU / TEMPAT"), kata berganti oranye di atas oranye dan bayangan kata lama menumpuk, tombol Masuk kosong, tagline menimpa kartu kaca dan ornamen gelombang, visual masih karakter placeholder, layering tidak rapi.
- Pinned: headline terpotong tak proporsional, kartu ungu menutupi teks, teks pill putih di atas putih tak terbaca, ukuran kartu tak seimbang, pergerakan tidak natural.
- Modul: teks putih di krem tak terbaca, tab horizontal bertumpuk dengan daftar vertikal dan deskripsi terlempar ke kiri-bawah, preview kotak kosong bertulisan "MODULE-NEXUS-AI.PNG" dengan teks terpotong, counter "00/05" nyaris tak terlihat.
- Statement: teks terlalu besar, keluar area, reveal belum matang.
- Proyek dan prinsip: kartu kosong/placeholder, kartu terpotong tak sengaja di sisi kanan, judul "EMPAT MODUL" padahal hanya 3 kartu.
- Strip titik oranye masih placeholder dan menempel di bawah navbar.
- Footer masih memuat kredit personal.
- Mobile belum terbukti bagus.

Akar masalah yang WAJIB diperiksa dan diperbaiki di sumbernya (bukan ditambal):
a. Font benar-benar termuat: `document.fonts.check('900 40px Orbitron')` true, dan computed font-family headline diawali Orbitron. Hapus duplikasi link Google Fonts.
b. Tidak ada `color:#fff` atau `var(--white)` di dalam section terang. Teks mengikuti token tema.
c. Tidak ada ancestor dari elemen pinned yang memakai `transform`, `filter`, atau `overflow:hidden` yang memotong atau merusak pin.
d. Lenis dan ScrollTrigger tersinkron (bagian 8).
e. `overflow-x: clip` (bukan `hidden`) pada html dan body.
f. Pill Masuk: temukan aturan yang membuat teksnya putih/transparan di atas putih.
g. Kata berganti bertumpuk: temukan penyebab dua elemen bergantian.

## 5. DESIGN TOKENS DAN TIPOGRAFI

Warna (CSS variables / sistem tema yang ada):
- Hero: gradien `linear-gradient(135deg, #8E0E00 0%, #E8430F 50%, #FF8A1F 100%)`, tanpa overlay gelap tebal (vignette sangat tipis di kiri-atas maksimal opacity .25 hanya agar navbar terbaca).
- Hitam #0A0A0A (pinned awal memakai #000), abu #444 / #5F5F5F / #BBBBBB, krem #F4F2E6, putih #FFFFFF / #FDFDFD, aksen --accent #F0490A, violet kartu #4B0FB8, lavender highlight #C9B6F2, amber kata berganti #F5A623.
- Teks: --text-on-dark #FFFFFF, --text-on-light #111111, --muted-on-light #6B6B6B.
- Setiap `<section>` punya `data-theme="dark|light"`. Warna teks anak-anaknya WAJIB mengikuti tema itu. Kontras minimal WCAG AA (4.5:1 teks biasa, 3:1 teks besar).
- Jangan menambah gradien, glow, atau dekorasi tanpa alasan desain yang jelas.

Font (tepat satu link Google Fonts tanpa duplikasi, atau self-host lewat @fontsource):
- Orbitron 800;900: headline hero.
- Outfit 900: headline besar section pinned.
- Plus Jakarta Sans 500;600;700: heading di section terang, judul kartu proyek.
- Inter 400;500;600: body dan UI.
Verifikasi `document.fonts.check` untuk Orbitron dan Outfit. Jika setelah membandingkan frame hero dengan hasil lebar hurufnya berbeda lebih dari 10%, uji Michroma sebagai pengganti hero dan pilih yang lebih dekat.

Bentuk: radius kartu besar 16-24px, gambar 16px, pill 999px. Kartu kaca: `background rgba(255,255,255,.12); backdrop-filter blur(18px); border 1px rgba(255,255,255,.25)`.

## 6. URUTAN HALAMAN (PERSIS INI)

1) Navbar fixed + Hero oranye. 2) Section pinned hitam dengan kartu ungu (film mini). 3) Strip logo (marquee) putih. 4) "Identitas Merek" (modul sticky, krem). 5) Statement (putih, reveal per kata). 6) Proyek (putih, horizontal pinned, kursor "LIHAT"). 7) Prinsip (putih, kartu oranye sticky + carousel). 8) Footer hitam.

## 7. SPESIFIKASI PER SECTION (UKURAN DALAM PERSEN VIEWPORT DESKTOP, DIUKUR DARI REFERENSI 1280x645)

Catatan: semua angka perkiraan dari frame. Fase A harus memverifikasi dan boleh mengoreksi dengan toleransi ±8%.

### 7.1 Navbar (fixed di semua section)
- Logo (wordmark NEXUS AI OFFICE) kiri di x≈5%.
- Kanan: pill putih (tinggi ≈36px, padding 0 20px, radius penuh, teks "Masuk" #111 16px weight 500, ikon pesawat-kertas terisi di kanan teks), jarak 5vw dari tepi kanan; lalu hamburger 3 garis tebal (3px, lebar 20px, gap 5px).
- Pill SELALU berteks terbaca di semua state dan section. Link ke route login existing.
- Warna wordmark dan hamburger berganti otomatis lewat IntersectionObserver berdasarkan `data-theme` section di bawah navbar: putih di gelap, #111 di terang. Tidak boleh hilang, kosong, atau menimpa konten.
- Pertahankan menu hamburger existing; restyle sesuai tema.

### 7.2 Hero (tinggi 100vh, tidak di-pin)
Referensi: headline putih sangat tebal dan lebar mendominasi sisi kiri; figur besar di tengah yang berputar terus (tampak samping, lalu dari belakang, lalu dari depan, dalam satu siklus ±6 detik); kata berganti amber solid di depan figur; kartu kaca kanan; tagline kapital kiri-bawah.

Headline (3 baris, tetap, bukan 4):
- Markup: baris 1 "MEMBANGUN RUANG", baris 2 "KERJA " + elemen kata berganti, baris 3 "DALAM SATU TEMPAT". Tiap baris elemen blok sendiri.
- CSS: Orbitron weight 900, uppercase, letter-spacing .05em, line-height 1.35, warna putih, `font-size: clamp(2.1rem, 4.5vw, 6rem)`, `max-width: none`, z-index 3. Di ≥1024px tiap baris `white-space: nowrap`; di bawah 1024px `normal`.
- Headline mulai di x≈3vw (padding-left 3vw). Hapus semua max-width wadah yang membuat teks terbungkus.
- Target ukuran (ukur dengan getBoundingClientRect di 1440 dan 1920 dan 1280): baris terpanjang "DALAM SATU TEMPAT" lebarnya 58 sampai 68% lebar viewport dan tidak ada baris yang terbungkus. Jika di luar rentang, setel ulang angka vw.
- Badge pill di atas headline (jarak 20px): bg rgba(255,255,255,.2), radius penuh, teks putih 14px weight 500, ikon kecil di kiri. Teks: "SATU RUANG, BANYAK ALAT".

Kata berganti:
- Warna amber solid #F5A623, ukuran sama dengan headline, sebaris setelah "KERJA ", berada DI DEPAN figur/visual (z-index 4).
- Kata: PRODUKTIF, TERPADU, CEPAT, RINGAN. Siklus ±4,5 detik: scramble huruf masuk 0,5s, tahan 2s, memudar 0,4s, KOSONG ±1,2s [TIDAK PASTI: interval persis; sesuaikan dengan frame]. Satu elemen teks di-scramble di tempat: tanpa elemen kedua, tanpa translateY, tanpa bayangan kata lama. Lebar dikunci (min-width ≈ 9ch) agar baris tidak bergeser.
- Reduced-motion: tampilkan "PRODUKTIF" statis.

Visual utama (saya akan membuat robot final secara manual, jadi wadahnya HARUS mudah diganti):
- Buat `<div class="nx-hero__visual" data-hero-visual>` yang bisa menerima `<img>`, `<video loop muted playsinline autoplay>`, atau SVG tanpa mengubah layout. Letak aset: `public/images/landing/hero-visual.*`. Tulis komentar HTML dan jelaskan cara menggantinya di laporan. Jika aset video/gambar dipasang, placeholder dan animasinya otomatis nonaktif.
- Posisi: tengah-kanan, `position:absolute; left:26vw; width:44vw; top:3vh; bottom:-6vh; z-index:2`, terpotong tepi bawah hero. Headline (z-index 3) menimpa sisi kiri visual.
- Placeholder sementara: siluet kapsul vertikal gelap glossy minimal dengan rim light oranye (SVG), tanpa wajah dan tanpa karakter generik, tampak elegan. Gerak: rotateY bolak-balik ±25° (8s ease-in-out) sebagai tanda gerak sementara + parallax kursor maks 12px (`pointer:fine`). Jangan gunakan gambar asal-asalan dan jangan membuat robot generik.

Kartu kaca (kanan-bawah): `position:absolute; right:3.7vw; bottom:14vh; width:clamp(300px,24vw,340px); z-index:5`. Bingkai luar border 1px rgba(255,255,255,.35), radius 20px, padding 8px. Header: ikon headset + "Tentang NEXUS" (16px weight 400) + ikon panah ↗ di kanan. Panel dalam radius 12px, bg rgba(255,255,255,.12): judul tebal 4 baris (font display 15px/1.5, putih) dari bullet pertama teks yang ada, lalu paragraf 12.5px/1.45 dari gabungan dua bullet lain. Ornamen orb glossy kecil oranye-putih (SVG) terpotong di tepi kanan panel. Hapus garis gelombang yang menimpa teks.

Tagline kapital (kiri-bawah): `left:3vw; bottom:5vh; max-width:34vw`; 14px weight 400, line-height 1.55, rgba(255,255,255,.92), z-index 5. Teks: "MULAI DARI SATU WORKSPACE. AKSES SEMUA TOOLS TANPA PERLU BERALIH APLIKASI." Tidak boleh bersinggungan dengan elemen lain: ukur bounding box terhadap headline dan kartu di 1920x972, 1440x900, 1280x645.

Animasi masuk: baris headline naik bertahap (stagger .08s), badge dan kartu fade + translateY 20px.
Saat scroll: hero TIDAK di-pin; hero ikut tergulung ke atas dan section hitam (#000) masuk langsung di bawahnya tanpa celah.

### 7.3 Section pinned hitam (INTI PERGERAKAN REFERENSI)

Struktur: dua bagian headline raksasa kiri dan kanan + kartu violet di tengah, semuanya terpusat vertikal di viewport.
- Headline: Outfit 900, kapital, letter-spacing -.01em, line-height 1, `clamp(2.4rem, 4.7vw, 6rem)`, putih. Teks: "RUANG KERJA" kiri, "TERPADU" kanan (oranye #F0490A). Margin awal 6vw, jarak ke kartu ≈2vw.
- Kartu: violet #4B0FB8, radius 16px, tekstur grid garis rgba(255,255,255,.08) dengan sel ≈5.6vw. Awal ≈25vw x 29vh; akhir ≈68vw x 67vh; selalu di tengah viewport.

Pergerakan (scrub, mengikuti progres scroll):
- Kartu membesar bertahap dari ukuran awal ke ukuran akhir.
- Latar halaman mengikuti ukuran kartu secara monoton: lebar kartu 25vw = #000; 36vw = #444; 45vw = #5F5F5F; 57vw = #BBBBBB; 68vw = #F5F5F5 menuju #FFFFFF.
- Dua bagian headline bergeser keluar (masing-masing setengah pertambahan lebar kartu) sampai terpotong tepi layar (di referensi terlihat potongan seperti "O ZYWRA" dan "VISUALISAS"), lalu hilang. Warna headline berubah abu muda saat latar terang agar tetap terbaca.
- Pin ±700vh, `scrub: 0.6`, `anticipatePin: 1`. Di akhir pin kartu bergerak naik (translateY menuju -100%) keluar layar, lalu strip logo masuk di latar putih tanpa celah.
- Scroll naik harus membalik ukuran, latar, dan posisi tanpa jitter atau lompatan.

Pembagian tugas default (jika tabel pengukuran mendukung hipotesis): ukuran kartu, warna latar, posisi headline = scrub scroll; isi kartu = GSAP timeline otomatis (bukan scrub) yang dimulai saat section memasuki viewport, tidak berbalik saat scroll naik, dan tidak berhenti saat scroll berhenti.

ISI KARTU = FILM MINI 9 SCENE (semua dibangun dengan GSAP + SVG + CSS; TANPA video atau Lottie dari ZYWRA). Font teks di kartu: Outfit 600. Pill: putih, radius 22px, padding 1.1vw 2vw, teks violet #4B0AA5, bayangan dalam lavender (`inset 0 0 24px rgba(180,150,255,.8)`) + bayangan luar (`0 10px 30px rgba(0,0,0,.25)`). Durasi hanya patokan dari referensi (1 sampai 3 detik per scene); sesuaikan dengan tabel pengukuranmu.
1. (±3 dtk) Kartu violet kecil: riak gelombang konsentris berdenyut; teks "Setiap kerja" muncul kecil putih di tengah; kata "dimulai" masuk dalam pill putih kecil dengan sedikit bounce.
2. (±0,6 dtk) Transisi disolusi piksel: grid 16x9 sel putih dengan delay acak 0 sampai 0,4s menutupi kartu; teks mengecil jadi satu huruf "Q" yang diketik. (±2 dtk) Kartu putih; wordmark "**ruang** kosong" (kata pertama tebal, kedua tipis, violet) masuk dengan blur horizontal lalu menajam.
3. (±1 dtk) Kartu violet bergrid; tombol bulat putih (±9vw, ikon tempat sampah violet) muncul dari bawah tengah dengan efek gooey: SVG filter (feGaussianBlur + feColorMatrix) + 6 tetesan putih memercik radial 0,5s.
4. (±1,5 dtk) Pill "Banyak tab terbuka." jatuh dari atas, miring -3°, jejak blur vertikal, masuk ke tombol; tombol menggembung seperti tetesan (scaleY 1.15, border-radius tetesan) saat "memakan"; pill mengecil dan hilang.
5. (±1,5 dtk) Pill "Data tercecer." dengan perilaku sama, miring +2°.
6. (±1,5 dtk) Pill "Harus ganti aplikasi." lalu pill sebelumnya menumpuk di belakangnya (3 lapisan, miring berbeda, glow); kerucut cahaya putih-lavender (clip-path kerucut + gradien + blur) turun dari atas ke tumpukan pill lalu melebar mengisi kartu.
7. (±3 dtk) Kartu putih dengan tepi berpendar lavender: teks besar "Lalu kejelasan masuk." (violet, Outfit 600); kata "kejelasan" di-highlight kotak lavender #C9B6F2 yang menyapu kiri ke kanan (±0,5s); kursor panah kecil bergerak dari atas menuju kata lalu ke pill mini kecil di kanan-bawah (label "Mulai" [TIDAK PASTI]); kursor menekan (scale .9) lalu scene berganti.
8. (±4 dtk, 4 kata × ±1 dtk) Scene orbit di kartu violet: bola kaca bercahaya (gradien lavender, glow lembut, ikon putih di dalamnya), 3 cincin orbit tipis melebar ke kanan dari bola (stroke rgba(255,255,255,.35)), 4 sampai 5 titik putih ukuran berbeda berjalan di cincin, kilau bintang kecil. Kata putih di kanan bola berganti dengan crossfade + blur:
   - "AI" (ikon bintang berkilau), bola di x≈22% lebar kartu;
   - "Musik" (ikon not), bola bergeser ke kiri sampai separuh terpotong tepi kartu;
   - "Kasira" (ikon struk), bola bergeser lebih jauh hingga hanya sepotong terlihat;
   - "Produktivitas" (ikon centang/rosette), bola kembali ke x≈22% dan lebih besar.
   Selama pin masih aktif, siklus empat kata ini berulang.
9. Kartu naik keluar layar (lihat pergerakan di atas).
Teks scene boleh disesuaikan asal panjangnya mirip. Reduced-motion: tampilkan scene 8 statis, tetap terbaca. Kontras teks ≥4.5:1 di setiap scene dan di setiap ukuran kartu; tidak ada teks putih di atas putih.

Mobile: kartu akhir ≈82vw x 44vh; headline dua bagian di atas dan bawah kartu; panjang pin ±350vh; semua scene tetap tampil dan terbaca.

### 7.4 Strip logo (marquee)
Strip putih setinggi ≈8vh, berjalan horizontal otomatis (±30 detik per loop, berhenti saat hover). Isi: ikon SVG sederhana buatan sendiri + nama modul (NEXUS AI, Music, Downloader, Kasira, Alat Produktivitas, AURA GLOBAL). DILARANG memakai logo klien ZYWRA atau klien fiktif. Tidak boleh menempel atau menimpa heading di bawahnya.

### 7.5 "Identitas Merek" (di NEXUS: section modul; latar krem #F4F2E6)
- Heading tengah, Plus Jakarta Sans 600, `clamp(2rem, 3.2vw, 3.4rem)`, #111, max-width 16ch (2 sampai 4 baris); teks dari heading modul yang ada ("MEMBUATNYA BERBEDA."). Padding atas ≈14vh agar tidak terpotong strip marquee. Heading naik keluar layar saat blok sticky mulai terpin.
- Blok 3 kolom sticky (pin ±500vh untuk 5 modul; di referensi ±1,5 detik scroll per item):
  - Kiri (x≈4,3%): daftar 5 modul, bullet bulat; item aktif #111 weight 700; lain #B5B5B5 weight 400; 20px; jarak vertikal ≈50px; pusat vertikal sejajar dengan gambar. Klik item = melompat ke modul itu. Tanpa counter "00/05".
  - Tengah: gambar potret radius 16px, lebar ≈33vw (x 29% sampai 62%), tinggi ≈77vh maksimal, top ≈11vh saat terpin.
  - Kanan (x≈68%, lebar ≈27vw): satu baris tagline modul (18px weight 500, #111), paragraf deskripsi (14px/1.45, #444), lalu daftar bernomor "/01" sampai "/05" (14px, jarak ≈44px, #333) dengan badge TERSEDIA (bg #DCFCE7, teks #166534) atau SEGERA (bg #E8E6DA, teks #6B6B6B) di kanan tiap baris. Tagline dan deskripsi dari teks modul yang ada; jangan mengarang fitur atau status.
- Pergantian item: gambar dan teks kanan crossfade 0,4s + translateY 16px; item aktif di kiri berganti warna/bobot 0,3s. Setelah item ke-5 blok terlepas dan scroll lanjut normal; scroll naik membalik dengan benar.
- Hapus total markup, CSS, dan JS lama: tab horizontal, counter, tulisan teknis seperti "MODULE-*.PNG". Semua teks section ini WAJIB gelap (tidak ada putih di krem).
- Gambar tiap modul: BUKAN kotak kosong. Buat visual SVG/CSS orisinal berkualitas presentasi (komposisi UI di atas latar bergradien warna merek NEXUS) dengan gerak mengambang halus (parallax 8-12px mengikuti scroll). Bukan screenshot produk asli dan jangan diklaim begitu. Simpan di `public/images/landing/modules/`:
  - NEXUS AI: panel percakapan AI dengan gelembung pesan dan kolom input.
  - Music: player dengan artwork orisinal, progress bar, kontrol.
  - Downloader: daftar unduhan dengan progress bar dan ikon platform generik.
  - Kasira: dashboard POS (grid produk, keranjang, ringkasan penjualan).
  - Alat Produktivitas: kolase notes + timer focus + tools; boleh dua lapisan (panel besar + panel kecil offset di belakang) seperti referensi.
- Mobile: tanpa pin; tiap modul ditumpuk (gambar, tagline, deskripsi, daftar bernomor).

### 7.6 Statement (putih #FDFDFD)
- Wadah: padding-left ≈10vw, max-width ≈78vw. Plus Jakarta Sans 500, `clamp(2rem, 4.1vw, 4.2rem)`, line-height 1.1.
- Reveal per kata mengikuti scroll (scrub): belum terungkap #D9D9D9, aktif #888, selesai #111. Section pinned ±200vh. Section ini masuk dari bawah menimpa section modul. Teks NEXUS yang sudah ada: "Kami tidak hanya membuat alat — kami merancang pengalaman. Setiap detail, setiap interaksi, setiap transisi dibuat dengan tujuan."
- Kartu proyek muncul tepat di bawahnya (tanpa heading di antara).

### 7.7 Proyek (putih; horizontal pinned)
- Tiga kartu: NEXUS AI OFFICE (kategori WORKSPACE DIGITAL), KASIRA (KASIR UMKM), AURA GLOBAL (JASA WEBSITE). Jangan menambah proyek fiktif; hapus kata "EMPAT" dari judul; jika ada judul section, "MODUL YANG SUDAH JADI DAN TERUS KAMI TINGKATKAN."
- Kartu: lebar ≈42,3vw, gap ≈3,9vw, gambar rasio 3:2 (≈541x356 di 1280), radius 16px. Di bawah gambar (jarak ≈36px): indeks "01" (13px, #888), kategori (16px, #222), judul (Plus Jakarta Sans 600, ≈2,2vw, #111). Padding kiri awal ≈3vw.
- Dua kartu terlihat penuh dan kartu ketiga mengintip di tepi kanan (±48px). Track bergeser horizontal mengikuti scroll (pinned); saat berakhir, kartu terakhir berhenti dengan padding kanan ≈3vw (tidak ada kartu terpotong di akhir).
- Kursor kustom (hanya `pointer:fine`): lingkaran oranye ≈90px (#E8590C), teks putih kapital 13px bold "LIHAT", mengikuti mouse dengan lerp (jeda halus); skala dari 0 saat hover kartu, kembali ke 0 saat keluar. Kartu bisa diklik.
- Gambar tiap proyek: visual preview orisinal berkualitas presentasi (komposisi mockup perangkat: laptop, ponsel, atau dashboard dengan UI buatan sendiri). Bukan kotak kosong dan bukan screenshot palsu. Simpan di `public/images/landing/projects/`.
- Mobile: scroll-snap horizontal tanpa pin; kartu ≈82vw.

### 7.8 Prinsip (putih)
- Heading kiri: Plus Jakarta Sans 600, `clamp(1.8rem, 3.3vw, 3.4rem)`, #111, x≈3,9%. Paragraf kanan: 14px/1.45, #222, lebar ≈25vw, x≈68,6%, sejajar heading. Isi dari teks yang sudah ada.
- Baris kartu di bawahnya (jarak ≈56px) dalam kontainer `margin:0 4vw; overflow:hidden`.
- Kartu 1: oranye solid (--accent), lebar ≈26vw, radius 16px, STICKY di kiri kontainer; wordmark NEXUS putih di atas, teks 18px weight 500 putih di tengah ("Ekosistem Workspace Digital", subteks "Dibuat oleh Team NEXUS"). Tanpa bintang rating.
- Kartu prinsip lain (isi yang sudah ada, mis. "Satu tempat, semua alat", "Ringan di semua perangkat"; jangan menambah klaim): bg #FBF6F1, lebar ≈23,4vw, gap ≈0,7vw, radius 16px, padding 28px, tanda kutip oranye-merah #E8452C di kiri-atas, teks italic 16px/1.5 #444, judul kartu #111.
- Kartu lain bergeser horizontal di BAWAH kartu oranye (kartu oranye punya bayangan tipis di sisi kanan, z-index lebih tinggi); gradien fade di tepi kiri area geser. Tombol panah bulat oranye (≈40px) di kanan-bawah baris menggeser kartu; juga drag, swipe, dan keyboard.
- Posisi awal dan akhir: tidak ada kartu terpotong tak sengaja.
- Mobile: carousel scroll-snap; kartu oranye tidak sticky.

### 7.9 Footer (hitam #0A0A0A)
- Kolom: brand + deskripsi pendek ("Ekosistem workspace digital. Satu platform untuk semua kebutuhan produktivitas modern Anda.") di x≈3,9%; NAVIGASI (Beranda, Fitur, Tentang, Masuk) di x≈48%; PRODUK (NEXUS AI, Music, Downloader, Kasira, AURA GLOBAL) di x≈62%. Pertahankan tautan dan rute existing; status redup untuk yang SEGERA.
- "Ikuti Kami" dengan ikon sosial bulat HANYA jika tautan akunnya sudah ada di proyek. Jangan membuat halaman kebijakan atau tautan legal palsu.
- Dekorasi lengkung glossy abstrak buatan sendiri (SVG) di kiri-bawah. Garis pemisah tipis.
- Baris bawah: "© 2026 NEXUS AI OFFICE" di kiri; "Dibuat oleh Team NEXUS"; di kanan tombol bulat putih ikon panah-atas (scroll halus ke atas).
- Mobile: satu kolom rapi, spacing dan alignment konsisten.

## 8. ANIMASI DAN SCROLL (TEKNIS)

- Pertahankan animasi existing yang sudah bagus dan kembangkan. Gunakan GSAP + ScrollTrigger (dan Lenis jika sudah ada).
- Sinkronisasi Lenis dan ScrollTrigger:
  `lenis.on('scroll', ScrollTrigger.update); gsap.ticker.add(t => lenis.raf(t*1000)); gsap.ticker.lagSmoothing(0);`
- Panggil `ScrollTrigger.refresh()` setelah `document.fonts.ready`, setelah `window.load`, dan saat resize (debounce 200ms). Gunakan `anticipatePin: 1`.
- `overflow-x: clip` pada html dan body (bukan hidden). Tidak ada ancestor pinned dengan transform, filter, atau overflow:hidden yang memotong.
- Hanya animasikan transform dan opacity untuk performa 60fps; pakai `will-change` seperlunya.
- Hormati `prefers-reduced-motion`: jangan inisialisasi Lenis dan pin berat; matikan scramble, parallax, orbit, dan gerak berulang; tampilkan layout statis yang tetap rapi dan terbaca.
- Tidak boleh ada: jitter, lompatan posisi, elemen hilang mendadak, pin lepas terlalu cepat/lambat, gap kosong antar section, scroll horizontal halaman. Scroll naik harus rapi.

## 9. MOBILE (DIRANCANG SUNGGUH-SUNGGUH, BUKAN SEKADAR MENGECIL)

Ukuran uji minimal: 390x844, 360x800, 768x1024, 1280x645, 1366x768, 1920x972 (dan 1440x900 untuk pengukuran hero).
- Navbar: usable, target sentuh ≥44px, pill dan hamburger terbaca.
- Hero: satu kolom; headline boleh terbungkus (clamp minimum 2.1rem) tanpa meluber horizontal; visual di belakang/bawah headline skala ≈70%; kartu kaca turun lebar penuh di bawah headline; tagline terbaca dan tidak tertimpa.
- Pinned: lihat 7.3 (kartu akhir ≈82vw x 44vh).
- Modul: tanpa pin, ditumpuk. Proyek: scroll-snap horizontal tanpa pin. Prinsip: carousel scroll-snap. Kursor kustom dimatikan.
- Tidak ada scroll horizontal halaman, teks terpotong, elemen keluar viewport, layout jumping yang buruk, atau kartu terlalu kecil. Animasi tidak mengganggu scrolling. Jika animasi desktop tidak cocok untuk mobile, adaptasikan tanpa menghilangkan karakter visual.

## 10. ASET VISUAL

Semua aset orisinal dan lokal. Jangan memakai gambar remote, logo/gambar ZYWRA, atau gambar berhak cipta. Buat (SVG/CSS) dan simpan di `public/images/landing/`:
- `hero-visual` placeholder (bagian 7.2), mudah diganti dengan aset final saya.
- `modules/module-01..05` (bagian 7.5).
- `projects/project-01..03` (bagian 7.7).
- `footer-curve.svg`, ikon marquee, ikon scene orbit.
Tidak boleh ada nama file teknis (mis. "MODULE-*.PNG", "PROJECT-*.PNG") yang tampil sebagai teks di UI.

## 11. CARA KERJA (URUTAN)

1. Fase A (bagian 1) selesai, termasuk `docs/reference-motion-spec.md` dan `docs/pinned-measurements.md`.
2. Audit kode aktual: catat yang tersimpan, yang rusak, dan akar masalah (bagian 4). Perbaiki akar, jangan tambal.
3. Implementasi Blade, CSS, JS, dan aset (bagian 5 sampai 10).
4. Jalankan `npm run build`, lalu jalankan server (Laravel/Vite) dan uji di browser.
5. Verifikasi visual berulang (bagian 12), perbaiki, ulangi sampai semua kriteria bagian 14 lulus.
Jangan berhenti setelah satu bagian dan hanya menyebut rencana bagian berikutnya. Jika ada error, perbaiki dan lanjutkan.

## 12. VERIFIKASI VISUAL WAJIB (SIKLUS SAMPAI LULUS, MINIMAL 3 PUTARAN)

Gunakan browser bawaan untuk menjalankan site. Untuk setiap ukuran layar di bagian 9 dan setiap checkpoint scroll (hero; pinned di progres 0/25/50/75/100%; scene 1, 3, 6, 7, dan 8 untuk tiap kata; marquee; modul item 1 sampai 5; statement; proyek awal dan akhir; prinsip awal dan akhir; footer), ambil screenshot ke `docs/visual-qa/`. Bandingkan berdampingan dengan frame referensi di `docs/ref-frames/`. Daftar semua selisih (posisi, ukuran, warna, font, timing), perbaiki, lalu ulangi.
Tambahkan skrip pemeriksa otomatis sekali pakai (Playwright atau setara; boleh install sebagai devDependency khusus uji) untuk:
- kontras teks vs latar terdekat (≥4.5:1 teks biasa, ≥3:1 teks besar);
- tidak ada scroll horizontal;
- bounding box tagline hero tidak berpotongan dengan headline atau kartu;
- lebar baris terpanjang headline hero (% viewport) dan tidak ada baris terbungkus di ≥1024px;
- ukuran kartu pinned di awal dan akhir.
Ulangi sampai bersih.

## 13. LARANGAN

- Menghapus fitur, auth, atau dashboard.
- Memakai gambar, logo, teks, atau animasi berhak cipta dari ZYWRA.
- Menampilkan nama file teknis di UI.
- Menambah klaim, statistik, klien, rating, atau testimoni fiktif.
- Memakai teks putih di atas latar putih atau krem.
- Menambah dekorasi, gradien, atau glow tanpa alasan desain.
- Menambah library baru tanpa alasan kuat.
- Melaporkan sesuatu berhasil tanpa benar-benar menjalankannya.
- Menyatakan "pixel-perfect" atau "100% identik" tanpa bukti.

## 14. KRITERIA LULUS (SEMUA HARUS TERPENUHI; PERIKSA SATU PER SATU DENGAN BUKTI)

1. Fase A selesai: `docs/reference-motion-spec.md` dan `docs/pinned-measurements.md` ada, dan keputusan time-driven vs scroll-driven didukung angka.
2. Hero: 3 baris tanpa pembungkusan di ≥1024px; baris terpanjang 58 sampai 68% lebar viewport; Orbitron 900 termuat; kata berganti terbaca, tunggal, tanpa bayangan; tagline tidak bersinggungan; kartu kaca proporsional; pill Masuk berteks; wadah visual mudah diganti; hero tidak di-pin dan section hitam masuk tanpa celah.
3. Pinned: ukuran kartu awal dan akhir cocok referensi (±8%); latar mengikuti tabel pemetaan; headline terpotong tepi di akhir; kartu keluar ke atas lalu strip logo masuk tanpa celah; scroll naik/turun halus tanpa jitter.
4. Film mini: semua 9 scene tampil berurutan dan dapat dibedakan jelas (riak, disolusi piksel, ketikan, blur wordmark, percikan gooey, tombol menggembung, pill miring bertumpuk, kerucut cahaya, highlight + kursor klik, orbit dengan 4 kata dan 4 ikon); semua teks terbaca.
5. "Identitas Merek": 3 kolom dengan proporsi persis seperti bagian 7.5; visual orisinal untuk tiap modul; item aktif berganti rapi; tidak ada teks putih di krem; tidak ada tulisan .PNG.
6. Statement, proyek (kursor LIHAT, horizontal pinned, kartu terakhir tidak terpotong), prinsip (kartu oranye sticky + panah), dan footer sesuai bagian 7.
7. Semua kredit publik: "Dibuat oleh Team NEXUS"; tidak ada "Reski Setiawan" yang tampil ke pengguna.
8. Tidak ada scroll horizontal, teks terpotong, atau elemen di luar viewport pada semua ukuran layar yang diuji.
9. `npm run build` bersih; tidak ada error Vite, Laravel, atau console; test suite yang ada tetap lulus; login dan dashboard tetap berfungsi.
10. Pemeriksaan kontras otomatis bersih.

## 15. LAPORAN AKHIR (WAJIB, JUJUR)

Laporkan:
- File yang diubah dan ditambah (satu baris alasan per file).
- Ringkasan hasil Fase A dan perbedaan terhadap spesifikasi di prompt ini.
- Perbaikan visual per section.
- Visual preview yang dibuat dan lokasinya.
- Animasi yang dipertahankan vs diperbaiki.
- Hasil build, test, dan skrip pemeriksa.
- Daftar ukuran layar yang benar-benar diuji, plus hasil ukur (lebar baris terpanjang hero, ukuran kartu pinned awal dan akhir, lebar kartu proyek).
- Cara mengganti visual hero dengan aset robot final.
- Masalah yang masih tersisa, selisih terhadap referensi, dan keputusan yang kamu ambil sendiri.
Jangan menulis "100% sempurna" tanpa bukti; tulis apa adanya.

Mulai sekarang: kerjakan Fase A, lalu langsung implementasikan sampai seluruh kriteria lulus. Periksa sendiri kekurangan lain yang belum disebut dan perbaiki tanpa menunggu saya.]
