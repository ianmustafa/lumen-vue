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
