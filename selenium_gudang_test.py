from selenium import webdriver
from selenium.webdriver.common.by import By
import time
import os

# 1. Membuat folder otomatis 'bukti_selenium' untuk menampung screenshot laporan
os.makedirs('bukti_selenium', exist_ok=True)

# 2. Inisialisasi WebDriver Chrome (Otomatis mendeteksi Google Chrome di Mac M2)
driver = webdriver.Chrome()

try:
    print("=== MEMULAI UI TESTING SELENIUM (STATE TRANSITION) ===")

    # ----------------------------------------------------------------------
    # STATE 1: Mengakses Halaman Login Aplikasi Gudang
    # ----------------------------------------------------------------------
    driver.get("http://127.0.0.1:8000/login")
    driver.maximize_window()
    time.sleep(2) # Memberikan jeda waktu agar aset CSS/JS Vite termuat sempurna

    # Dokumentasi Bukti 1: Kondisi awal halaman login kosong
    driver.save_screenshot("bukti_selenium/1_halaman_login_awal.png")
    print("[DOKUMENTASI] Screenshot 1: Halaman login awal berhasil disimpan.")

    # ----------------------------------------------------------------------
    # TRANSISI & STATE 2: Pengisian Kredensial Form Login & Enter Otomatis
    # ----------------------------------------------------------------------
    # Mengisi field input email bawaan skema autentikasi proyek
    driver.find_element(By.NAME, "email").send_keys("admin@gudang.com")

    # Mengisi field input password ke dalam variabel
    password_field = driver.find_element(By.NAME, "password")
    password_field.send_keys("rahasia123")

    # Dokumentasi Bukti 2: Form login terisi data kredensial sebelum diklik
    driver.save_screenshot("bukti_selenium/2_form_login_terisi.png")
    print("[DOKUMENTASI] Screenshot 2: Form login terisi berhasil disimpan.")

    # Melakukan Transisi: Mengirimkan perintah ENTER langsung pada field password (Lebih Aman & Kebal Error Tombol)
    password_field.send_keys("\n")
    print("[INFO] Menekan ENTER otomatis pada keyboard...")
    time.sleep(4) # Beri jeda 4 detik agar proses autentikasi backend & pengalihan selesai sempurna

    # ----------------------------------------------------------------------
    # STATE 3: Berhasil Lolos Validasi & Masuk ke Halaman Dashboard
    # ----------------------------------------------------------------------
    # Memastikan URL tujuan saat ini berpindah ke halaman dashboard internal
    assert "dashboard" in driver.current_url.lower(), f"Gagal masuk ke halaman dashboard! URL Saat Ini: {driver.current_url}"

    # Dokumentasi Bukti 3: Bukti utama UI Automation berhasil menembus login
    driver.save_screenshot("bukti_selenium/3_sukses_masuk_dashboard.png")
    print("[DOKUMENTASI] Screenshot 3: Halaman dashboard berhasil disimpan.")

    # ----------------------------------------------------------------------
    # STATE 4: Navigasi Menu/Pindah ke Halaman Manajemen Produk (/products)
    # ----------------------------------------------------------------------
    # Menuju rute produk sesuai arsitektur resource route 'products' di web.php
    driver.get("http://127.0.0.1:8000/products")
    time.sleep(2) # Tunggu tabel data produk selesai di-render browser

    # Dokumentasi Bukti 4: Menampilkan tabel daftar produk inventaris gudang
    driver.save_screenshot("bukti_selenium/4_halaman_products.png")
    print("[DOKUMENTASI] Screenshot 4: Halaman Manajemen Produk berhasil disimpan.")

    print("\n=== UI TESTING SELENIUM: SEMUA SKENARIO STATE PASSED (100% SUKSES) ===")

except Exception as e:
    print(f"\n[ERROR] Pengujian terhenti karena kendala: {e}")

finally:
    # 3. Menutup browser Google Chrome otomatis setelah pengujian selesai
    driver.quit()