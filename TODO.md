# TODO - Fase User (Redesign halaman pendaftaran & pembayaran)

- [ ] 1) Redesign `resources/views/pendaftaran/index.blade.php` (hero/page heading, breadcrumb, summary cards, table modern, badges, empty state)
- [ ] 2) Redesign `resources/views/pendaftaran/create.blade.php` jadi wizard modern (6-7 section cards) tanpa mengubah JS/id/name/fetch/event listener
- [ ] 3) Redesign `resources/views/pendaftaran/_anggota_form.blade.php` tampilan anggota pakai card modern (tanpa mengubah name field)
- [ ] 4) Redesign `resources/views/pendaftaran/edit.blade.php` jadi detail modern dengan section cards + visual timeline
- [ ] 5) Redesign `resources/views/pendaftaran/bayar.blade.php` jadi invoice modern (tetap extend layouts.main, status badge, VA/Midtrans tetap via script & logic identik)

- [ ] 6) Pengecekan akhir: tidak ada syntax error Blade, tidak ada route/controller/query berubah, tidak ada JS/fetch/midtrans callback/id/name berubah
- [ ] 7) Rapikan output final sesuai format: daftar file diubah, ringkasan perubahan, checklist business logic/Midtrans/Google Maps/form payload

