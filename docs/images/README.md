# Screenshot README

Folder ini berisi gambar yang ditampilkan di `README.md` utama.

| File | Halaman |
| --- | --- |
| `landing-page.png` | Beranda |
| `login.png` | Masuk |
| `register.png` | Daftar akun |
| `panduan.png` | Panduan pendakian |
| `tentang.png` | Tentang |
| `form-pendaftaran.png` | Formulir SIMAKSI |
| `admin-dashboard.png` | Dashboard admin |
| `admin-gunung.png` | Manajemen gunung (admin) |

Screenshot halaman publik diambil dari situs live dengan Chrome headless (ukuran 1440×900). Opsi reduced motion dipakai supaya animasi GSAP tidak tertangkap setengah jalan:

```bash
chrome --headless=new --hide-scrollbars --force-prefers-reduced-motion \
  --window-size=1440,900 --virtual-time-budget=6000 \
  --screenshot=landing-page.png https://app-eta-ten-11.vercel.app/
```

Untuk halaman yang butuh login (dashboard pendaki, riwayat, pembayaran Midtrans), ambil screenshot manual dari browser. Simpan di folder ini, lalu tambahkan ke tabel **Tampilan** di `README.md`.
