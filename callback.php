  <? Php
   // Mengatur informasi akun.
   Untuk // GARIS pengembang situs Saluran> Informasi dasar
   // Mengatur informasi yang telah dijelaskan.
   $ ID Saluran = "1503638187"; // Saluran ID
   $ ChannelSecret = "ee1655e16f059647f9e25bb8a267c5e1"; // Saluran Rahasia
   $ Mid = "U263b51adffaa8dce7bc11c20d7ab4fbc"; // MID

   // mendapatkan pesan yang telah dikirim dari (bagian tubuh permintaan POST) GARIS.
   // itu akan dikirim string format JSON, seperti berikut ini.
   // { "hasil": [
   // {
   // ...
   // "isi": {
   // "contentType": 1,
   // "dari": "uff2aec188e58752ee1fb0f9507c6529a" ,
   // "text": "Halo, BOT API Server!"
   // ...
   //}
   //},
   // ...
   //]}
   $ RequestBodyString = file_get_contents ( 'php: // masukan');
   $ RequestBodyObject = json_decode ($ requestBodyString) ;
   $ RequestContent = $ requestBodyObject -> hasilnya {0} -> isi;
   $ RequestText = $ requestContent -> teks ; teks yang dikirim dari // pengguna
   $ RequestFrom = $ requestContent -> dari ; // dari pengguna transmisi MID
   $ ContentType = $ requestContent -> contentType ; // tipe data (1 teks)

   // header permintaan untuk LINE BOT API
   $ Header = array (
     "Content-Type: application / json ; charset = UTF-8",
     "X-Line-ID Saluran: { $ ID Saluran}", // Saluran ID
     "X-Line-ChannelSecret: { $ channelSecret}", // Saluran Rahasia
     "X-Line-Terpercaya-User -Dengan-ACL: {$ mid}", // MID
   );

   // teks yang akan kembali ke pengguna.
   // selalu merekomendasikan ketel bola Udon dari Yamakoshi.
   $ ResponseText = <<< EOM
 Ini adalah "{$ requestText}".  Baik.  Kettle bola Udon dari Yamakoshi dalam kasus seperti itu.  http: //yamagoeudon.com
 EOM;

   // GARIS membuat data JSON untuk diteruskan ke pengguna melalui BOT API.
   // untuk menentukan dalam bentuk array MID tujuan respon pengguna dalam.
   // toChannel, eventType adalah numerik, string tetap.
   // contentType adalah, jika Anda ingin kembali teks 1.
   // toType adalah, dalam kasus respon terhadap pengguna 1.
   // Teks, menentukan teks yang akan kembali ke pengguna.
   $ ResponseMessage = <<< EOM
     {
       "Untuk": [ "{$ requestFrom}"],
       "ToChannel": 1383378250,
       "EventType": "138311608800106203",
       "Konten": {
         "ContentType": 1,
         "ToType": 1,
         "Teks": "{$ responseText }"
       }
     }
 EOM;

   Membuat dan menjalankan // permintaan untuk LINE BOT API
   $ Curl = curl_init ( 'https://trialbot-api.line.me/v1/events' );
   curl_setopt ($ curl, CURLOPT_POST, benar );
   curl_setopt ($ curl, CURLOPT_HTTPHEADER, $ header);
   curl_setopt ($ curl, CURLOPT_POSTFIELDS, $ responseMessage);
   curl_setopt ($ curl, CURLOPT_RETURNTRANSFER, benar );
   Tentukan URL proxy Fixie dari // Heroku Addon.  Rincian yang akan dijelaskan kemudian.
   curl_setopt ($ curl, CURLOPT_HTTPPROXYTUNNEL, 1 );
   curl_setopt ($ curl, CURLOPT_PROXY, getenv ( 'FIXIE_URL'));
   $ Output = curl_exec ($ curl) ;
 ?>