 <? Php
 error_log ( "callback awal.") ;

 // Pengaturan informasi Akun
 $ CHANNEL_ID = "1503638187";
 $ Channel_secret = "ee1655e16f059647f9e25bb8a267c5e1";
 $ Mid = "U263b51adffaa8dce7bc11c20d7ab4fbc" ;

 // Pengaturan URL Sumber Daya
 $ Original_content_url_for_image = "[image URL]";
 $ Preview_image_url_for_image = "[thumbnail URL image]";
 $ Original_content_url_for_video = "[Video URL]";
 $ Preview_image_url_for_video = "[Video URL gambar thumbnail]";
 $ Original_content_url_for_audio = "[suara URL]";
 $ Download_url_for_rich = "[Kaya image URL]";

 // Penerimaan Pesan
 $ Json_string = file_get_contents ( 'php: // masukan');
 $ JSON_OBJECT = json_decode ($ json_string) ;
 $ Content = $ JSON_OBJECT -> hasilnya {0} -> konten;
 $ Text = $ konten -> teks ;
 $ Dari = $ konten -> dari ;
 $ Message_id = $ konten -> id ;
 $ Content_type = $ konten -> contentType ;

 // Pengguna perolehan informasi
 api_get_user_profile_request ($ dari);

 // Penyimpanan Pesan gambar, video, jika suara
 if (in_array ($ content_type, Array (2, 3, 4))) {
     api_get_message_content_request ($ message_id);
 }

 // Generasi konten Pesan
 $ Image_content = <<< EOM
         "ContentType": 2,
         "OriginalContentUrl": "{$ original_content_url_for_image }",
         "PreviewImageUrl": "{$ preview_image_url_for_image }"
 EOM;
 $ Video_content = <<< EOM
         "ContentType": 3,
         "OriginalContentUrl": "{$ original_content_url_for_video }",
         "PreviewImageUrl": "{$ preview_image_url_for_video }"
 EOM;
 $ Audio_content = <<< EOM
         "ContentType": 4,
         "OriginalContentUrl": "{$ original_content_url_for_audio }",
         "ContentMetadata": {
             "AUDLEN": "240000"
         }
 EOM;
 $ Location_content = <<< EOM
         "ContentType": 7,
         "Text": "Konvensi center" ,
         "Lokasi": {
             "Title": "Konvensi center" ,
             "Lintang": 35,61823286112982,
             "Bujur": 139,72824096679688
         }
 EOM;
 $ Sticker_content = <<< EOM
         "ContentType": 8,
         "ContentMetadata": {
           "STKID": "100",
           "STKPKGID": "1",
           "STKVER": "100"
         }
 EOM;
 $ Rich_content = <<< EOM
         "ContentType": 12,
         "ContentMetadata": {
             "DOWNLOAD_URL": "{$ download_url_for_rich }",
             "SPEC_REV": "1",
             "ALT_TEXT" :, "Alt Text ."
             "MARKUP_JSON": "{\" kanvas \ ": {\" width \ ": 1040, \" height \ ": 1040, \" initialScene \ ": \" scene1 \ "}, \" gambar \ ": {\ "image1 \": {\ " x \": 0, \ "y \": 0, \ "w \": 1040, \ "h \": 1040}}, \ "tindakan \": {\ "link1 \ ": {\" Jenis \ ": \" web \ ", \" teks \. ": \" Open link1 \ ", \" params \ ": {\" linkUri \ ": \" http: // baris .me / \ "}}, \ " link2 \ ": {\" Jenis \ ": \" web \ ", \" teks \. ": \" Open link2 \ ", \" params \ ": {\" linkUri \ ": \" http://linecorp.com \ "}}}, \" adegan \ ": {\" scene1 \ ": {\" menarik \ ": [{\" image \ ": \" image1 \ ", \" x \ " : 0, \" y \ ": 0, \" w \ ": 1040, \" h \ ": 1040}], \" pendengar \ ": [{\" Jenis \ " : \ "sentuhan \", \ "params \": [0, 0, 1040, 720], \ "tindakan \": \ "link1 \"}, {\ "Jenis \": \ "sentuhan \", \ "params \": [0, 720, 1040, 720], \ "tindakan \": \ "link2 \"}]}}} "
         }
 EOM;

 // Mengubah pesan kembali dalam menanggapi pesan yang diterima
 $ EVENT_TYPE = "138311608800106203";
 if ($ teks == "image" ) {
     $ Content = $ image_content;
 } Lain jika ($ teks == " Video") {
     $ Content = $ video_content;
 } Lain jika ($ teks == " audio") {
     $ Content = $ audio_content;
 } Lain jika ($ teks == " lokasi") {
     $ Content = $ location_content;
 / *
 } Lain jika ($ teks == " sticker") {
 $ Content = $ sticker_content;
 * /
 } Lain jika ($ teks == " kaya") {
     $ Content = $ rich_content;
 } Lain jika ($ teks == " multi-") {
     $ EVENT_TYPE = "140177271400161403";
 $ Content = <<< EOM
     "MessageNotified": 0,
     "Pesan": [
         {{$ Image_content}},
         {{$ Video_content}},
         {{$ Audio_content}},
         {{$ Location_content}},
         {{$ Sticker_content}},
         {{$ Rich_content}}
     ]
 EOM;
 } Lain {// selain transmisi teks di atas
     if ($ content_type! = 1) {
         $ Teks = "non-teks";
     }
 $ Content = <<< EOM
         "ContentType": 1,
         "Text" :. "Saya melihat itu adalah bahwa" {$ text} ".  Hal ini memang.  "
 EOM;
 }
 $ Post = <<< EOM
 {
     "Untuk": [ "{$ dari}"],
     "ToChannel": 1383378250,
     "EventType": "{$ EVENT_TYPE }",
     "Content": {
         "Prototipe": 1,
         {$ Content}
     }
 }
 EOM;

 api_post_request ( "/ v1 / peristiwa" , $ post);

 error_log ( "callback end.") ;

 Fungsi api_post_request ($ path, $ post ) {
     $ Url = "https://trialbot-api.line.me {$ path }";
     $ Header = array (
         "Content-Type: application / json ",
         "X-Line-ID Saluran: { $ GLOBALS [ 'CHANNEL_ID']}",
         "X-Line-ChannelSecret: { $ GLOBALS [ 'channel_secret']}",
         "X-Line-Terpercaya-User -Dengan-ACL: {$ GLOBALS [ 'pertengahan']}"
     );

     $ Keriting = curl_init ($ url) ;
     curl_setopt ($ curl, CURLOPT_POST, benar );
     curl_setopt ($ curl, CURLOPT_HTTPHEADER, $ header);
     curl_setopt ($ curl, CURLOPT_POSTFIELDS, $ post);
     curl_setopt ($ curl, CURLOPT_RETURNTRANSFER, benar );
     $ Output = curl_exec ($ curl) ;
     error_log ($ output);
 }

 Fungsi api_get_user_profile_request ($ mid) {
     $ Url = "https://trialbot-api.line.me/v1/profiles?mids= {$ mid }";
     $ Header = array (
         "X-Line-ID Saluran: { $ GLOBALS [ 'CHANNEL_ID']}",
         "X-Line-ChannelSecret: { $ GLOBALS [ 'channel_secret']}",
         "X-Line-Terpercaya-User -Dengan-ACL: {$ GLOBALS [ 'pertengahan']}"
     ); 

     $ Keriting = curl_init ($ url) ;
     curl_setopt ($ curl, CURLOPT_HTTPHEADER, $ header);
     curl_setopt ($ curl, CURLOPT_RETURNTRANSFER, benar );
     $ Output = curl_exec ($ curl) ;
     error_log ($ output);
 }

 Fungsi api_get_message_content_request ($ message_id) {
     $ Url = "https://trialbot-api.line.me/v1/bot/message/ {$ message_id } / content";
     $ Header = array (
         "X-Line-ID Saluran: { $ GLOBALS [ 'CHANNEL_ID']}",
         "X-Line-ChannelSecret: { $ GLOBALS [ 'channel_secret']}",
         "X-Line-Terpercaya-User -Dengan-ACL: {$ GLOBALS [ 'pertengahan']}"
     ); 

     $ Keriting = curl_init ($ url) ;
     curl_setopt ($ curl, CURLOPT_HTTPHEADER, $ header);
     curl_setopt ($ curl, CURLOPT_RETURNTRANSFER, benar );
     $ Output = curl_exec ($ curl) ;
     file_put_contents ( "/ tmp / {$ message_id}", $ output);
 }