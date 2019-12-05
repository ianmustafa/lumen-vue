# Contoh Proyek Lumen dan Vue.js dalam Satu Repositori

Dalam mengerjakan suatu proyek yang membutuhkan Vue.js, seringkali saya menggabungkan 
kedua basis kode proyek dalam satu repositori. Proyek ini saya harap bisa menjadi 
bahan rujukan dan dapat memberikan gambaran tentang bagaimana saya memulai 
proyeknya, termasuk langkah-langkah seperti apa yang saya lakukan.

## Mengapa _Back-end_ dan _Front-end_-nya Dijadikan Satu?
***Pertama:*** Manajemen berkas proyek menjadi lebih mudah untuk 
_single-man developer_, tapi tidak akan menyulitkan ketika proyeknya mulai 
dikerjakan oleh tim. Karena meskipun keduanya ada di dalam satu repositori, 
pada dasarnya  keduanya berdiri secara independen dan tetap diperlakukan 
sebagai dua aplikasi yang berbeda.

***Kedua:*** Tidak perlu pusing dengan Cross-Origin Resource Sharing, alias *CORS*. 
Saya yakin, ini adalah musuh terbesar dari kita yang sering bekerja dalam proyek 
yang bagian _back-end_ dan _front-end_-nya disajikan secara terpisah. Ya,
meskipun solusi dari masalah ini sangat simpel (tinggal 
[tambahkan HTTP header untuk CORS](https://enable-cors.org) saja), dengan 
menggunakan gaya seperti proyek ini, masalah yang disebabkan oleh CORS 
tidak akan timbul. Karena, sekalipun dalam pengembangannya, kedua proyek 
bersifat independen, namun kode hasil _build_ dari _front-end_ tetap 
disajikan dari _view_ milik _back-end_.

***Ketiga:*** Berkat penyajian hasil _build front-end_ dari _view_ milik _back-end_,
kita tidak lagi bingung akan pengaturan `API_URL` di _front-end_; kita cukup gunakan 
URL API tanpa menyertakan _hostname_ maupun IP/port dari _web server_, dan API akan
tetap bisa diakses. 🎉

***Keempat:*** Bisa hemat satu repo. 😂

## Langkah-Langkah Instalasi
Bagi manteman yang ingin mengklon proyek ini, berikut langkah-langkah yang harus 
dilakukan setelah mengklon proyeknya:
- Masuk ke direktori proyek, lalu pasang dependensi Lumen menggunakan Composer:
  ```sh
  $ cd lumen-vue
  $ composer install
  ```
- Salin berkas `.env.example` sebagai `.env`, lalu atur isinya sesuai kebutuhan.
- Jalankan _built-in web server_ bawaan PHP (Lumen tidak memiliki perintah 
  `php artisan serve`):
  ```sh
  $ php -S 127.0.0.1:8000 -t public
  ```
- Akses http://localhost:8000 melalui peramban.

## Penjelasan Singkat
### Sebelum Pengerjaan Front-end: Pembuatan API
Saya menggunakan studi kasus sederhana untuk API-nya: pengelolaan _task_. Saya siapkan 
sebuah RESTful API sederhana, lengkap dengan _migration_ dan _feature testing_-nya. 
Saya juga melakukan sedikit modifikasi terhadap _routing_ bawaan Lumen:
1. Semua _route endpoint_ yang dideklarasikan di dalam berkas
   [`routes/api.php`](routes/api.php) akan berada di dalam _path_ 
   `http://<domain atau API>/api/`.
2. Saya juga menambahkan satu _global routing_ untuk mengarahkan semua _endpoint_
   yang tidak tersedia dalam _routing_ Lumen ke halaman _single-page application_
   dari Vue.js.

Kedua modifikasi di atas saya lakukan dari baris-baris terakhir dalam berkas 
[`bootstrap/app.php`](bootstrap/app.php#:94-L103):
```php
$app->router->group([
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'api', // Tambahkan prefix disini...
], function ($router) {
    // ...dan ubah nama berkas router dari
    // web.php menjadi api.php
    require __DIR__.'/../routes/api.php';
});

// Dan tambahkan route berikut untuk menyajikan Vue.js
$app->router->get('/{route:.*}', function () {
    return view('spa');
});
```

Setelah itu, saya menjalankan _built-in web server_ bawaan PHP untuk mengaktifkan 
REST API _server_-nya pada http://localhost:8900. Baru saya mulai membuat bagian 
_front-end_-nya dengan Vue CLI.

### Pembuatan Front-end dengan Vue CLI
Setelah API-nya siap, saya mulai membuat _front-end_-nya dengan Vue CLI. Caranya
sama seperti biasa, hanya saja ada beberapa langkah tambahan yang harus dilakukan 
agar kita bisa mendapatkan hasil yang diinginkan.

1. Dari dalam direktori proyek, jalankan Vue CLI untuk membuat instalasi Vue.js 
   baru di dalam direktori `frontend`:
   ```sh
   $ vue create -n frontend
   ```
   _Flag_ `-n` digunakan untu memastikan Vue CLI tidak akan menginisialisasi Git 
   pada direktori `frontend` setelah instalasi.

   Dalam pilihan fitur, saya memilih fitur berikut (gunakan pilihan manual):
   - Babel;
   - CSS Pre-processor (saya pakai Sass/SCSS dengan `dart-sass`);
   - Linter (saya pakai ESLint hanya untuk mencegah terjadinya _syntax error_, tanpa konfigurasi tambahan; jadi, pilih yang atas);
2. Setelah selesai, masuk ke direktori `frontend`, lalu saya menginstal 2 _plugin_ 
   Vue tambahan: Axios untuk mengkases API yang sudah kita buat, serta Vuetify 
   untuk memudahkan pembuatan UI, karena saya malas 😂
   ```sh
   $ cd frontend
   $ vue add axios
   ```
   Abaikan _error_ yang terjadi setelah isntalasi Axios; kita akan perbaiki nanti. 
   Lanjutkan dengan instalasi Vuetify:
   ```sh
   $ vue add vuetify
   ```
   Saya pilih _preset_ **Default** saat instalasi Vuetify. Setelah selesai, error 
   yang sama dari hasil instalasi plugin Axios akan muncul. Abaikan dulu untuk 
   saat ini.
3. Selanjutnya, kita perlu melakukan konfigurasi terhadap instalasi Vue.js ini, 
   agar kita dapat mendapatkan hasil yang diinginkan. Kita lakukan modifikasi 
   terhadap berkas `vue.config.js` dalam direktori `frontend`. Gunakan
   konfigurasi berikut:
   ```js
   module.exports = {
     // Kita perlu membuat proxy ke alamat API server yang sudah kita buat
     // sebelumnya. Di sini saya menggunakan http://localhost:8900.
     // Sesuaikan dengan konfigurasi yang Anda gunakan.
     devServer: {
       proxy: 'http://localhost:8900'
     },

     // Kita perlu mengatur direktori output hasil build ke direktori public milik 
     // Lumen, agar semua berkas aset yang dihasilkan bisa diletakkan di sana
     outputDir: '../public',

     // Kita juga perlu mengatur lokasi berkas HTML hasil build untuk menggantikan
     // view yang digunakan oleh Lumen (yang sudah diatur di bootstrap/app.php).
     // Pastikan hanya menggantikan view bersangkutan pada mode production.
     indexPath: process.env.NODE_ENV === 'production'
       ? '../resources/views/spa.blade.php'
       : 'index.html',

     // Jangan lupa sertakan konfigurasi yang dibutuhkan oleh Vuetify
     'transpileDependencies': [
       'vuetify',
     ],
   };
   ```
4. Selanjutnya, kita perlu mengubah _build script_ untuk Vue.js di `package.json`.
   Cari bagian `"build"` pada properti `"scripts"` dan gantikan menjadi seperti
   berikut. Ingat, hanya ubah bagian `"build"`:
   ```json
   {
     "scripts": {
       "serve": "vue-cli-service serve",
       "build": "rm -rf ../public/{js,css,img} && vue-cli-service build --no-clean",
       "lint": "vue-cli-service lint"
     },
   }
   ```
5. Yang terakhir, kita lanjut untuk memperbaiki _error_ yang terjadi setelah 
   instalasi Axios. Kita hanya cukup mematikan ESLint untuk pesan _error_
   bersangkutan pada berkas `src/plugin/axios.js` dengan menambahkan 
   komntar berikut di atas baris isntalasi Vue plugin:
   ```js
   // Semudah menambahkan baris komentar di bawah ini:
   // eslint-disable-next-line no-unused-vars
   Plugin.install = function(Vue, options) {
     // ...
   }
   ```

Dari sini, konfigurasi sudah selesai. Selanjutnya, kita bisa melakukan _development_ 
seperti biasa: menjalankan `npm run serve`, mengubah UI, dan setelah selesai, 
jalankan `npm run build`. Untuk penjelasan lebih lengkap, silakan melihat 
basis kode yang sudah tersedia. 🍻

## Lisensi
[Lisensi MIT](LICENSE). 
