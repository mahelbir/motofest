<!DOCTYPE html>
<html lang="{{language()}}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="@asset('css/bootstrap.min.css')">
    <title>Sipariş | @sets("site.name")</title>
</head>
<body style="background-color: #e6e6e6;">

<div class="container-fluid">
    <div class="mt-5">

        <div class="row">
            <div class="col-12">
                <h1>Tutar: <span class="tutar">0</span>
                    <button class="btn btn-primary" id="btnOlustur" onclick="olustur()">
                        Oluştur
                    </button>
                </h1>
            </div>
            @foreach($urunler as $urun)
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <img src="{{ $urun['resim']  }}" class="img-fluid mb-2" style="height: 150px">
                            <h3>{{$urun['isim']}}</h3>
                            <h5>{{$urun['fiyat']}} TL</h5>
                        </div>
                        <button class="btn btn-success" onclick="urun({{$urun['id']}})" id="btn{{$urun['id']}}">Ekle
                        </button>
                    </div>
                </div>
            @endforeach

            <div class="col-12">
                <hr>
                <h3>Fiş Bilgisi</h3>
                <div id="siparis">
                    <p>Sipariş No: <span id="siparisNo"></span></p>
                    <p>Tutar: <span class="tutar"></span></p>
                    <ul id="siparisIcerik"></ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="@asset('js/jquery.min.js')"></script>
<script src="@asset('js/print.min.js')"></script>

<script>
    const urunler = {!! $json !!};
    const sepet = {};

    function olustur() {
        const tutar = parseFloat($(".tutar").first().text());
        if (tutar === 0)
            return alert("Sepet boş!");

        const data = {
            tutar,
            icerik: []
        };
        const icerik = $("#siparisIcerik");
        icerik.html('');
        $(".btn").prop('disabled', true);
        $("#btnOlustur").text("Sayfayı yenilemek için ekranda herhangi bir yere tıkla!");
        for (const id in sepet) {
            const adet = sepet[id]
            const urun = urunler[id];
            const metin = `${urun['isim']} x${adet}`;
            icerik.append(`<li></li>`);
            data.icerik.push(urun['isim']);
        }
        data.icerik = data.icerik.join();

        $.post({
            url: "@base_url('/siparis/yeni')",
            dataType: "json",
            data
        }).done(res => {
            if (res?.id) {
                $('#siparisNo').text(res.id);
                printJS({
                    printable: 'siparis',
                    type: 'html',
                    onPrintDialogClose: () => {
                        alert("Sipariş No: " + res.id);
                        location.reload();
                    }
                });
            } else {
                alert(res?.error ?? "Bir hata oluştu!");
                location.reload()
            }
        }).fail(xhr => {
            alert(xhr?.responseJSON?.error ?? 'Sunucu hatası!');
            location.reload()
        });
    }

    function urun(id) {
        id = id.toString();

        const btn = $("#btn" + id);
        if (id in sepet) {
            delete sepet[id];
            btn.removeClass("btn-danger");
            btn.addClass("btn-success");
            btn.text("Ekle");
        } else {
            if (!sepet[id])
                sepet[id] = 1
            else
                sepet[id] += 1;
            btn.addClass("btn-danger");
            btn.removeClass("btn-success");
            btn.text("Çıkar");
        }

        tutarHesapla();
    }

    function tutarHesapla() {
        let tutar = 0;
        for (const id in sepet)
            tutar += parseFloat(urunler[id]['fiyat']);
        $(".tutar").text(tutar);
    }
</script>

</body>
</html>