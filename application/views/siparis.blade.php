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
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-success btn-block" onclick="urunIslem({{$urun['id']}}, '+')"
                                        id="btn{{$urun['id']}}">+
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-danger btn-block" onclick="urunIslem({{$urun['id']}}, '-')"
                                        id="btn{{$urun['id']}}">-
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="col-12">
                <hr>
                <h3>Fiş Bilgisi</h3>
                <div id="siparis">
                    <p id="saat"></p>
                    <p>Sipariş No: <span id="siparisNo"></span></p>
                    <p>Tutar: <span class="tutar"></span> TL</p>
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
        const date = new Date();
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
        $("#btnOlustur").text("Sayfanın yenilenmesi ve işlemin tamamlanması için ekranda herhangi bir yere tıkla!");
        $("#saat").text(date.toLocaleDateString() + ' ' + date.toLocaleTimeString());
        for (const id in sepet) {
            const adet = sepet[id]
            const urun = urunler[id];
            const metin = `${urun['fiyat'] * adet} TL | ${urun['isim']} x${adet}`;
            icerik.append(`<li>${metin}</li>`);
            data.icerik.push(metin);
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
                location.reload();
            }
        }).fail(xhr => {
            alert(xhr?.responseJSON?.error ?? 'Sunucu hatası!');
            location.reload()
        });
    }

    function urunIslem(id, islem) {
        id = id.toString();
        if (islem === '+') {
            if (!sepet[id])
                sepet[id] = 1;
            else
                sepet[id] += 1;
        } else if(islem === '-' && id in sepet) {
            if(sepet[id] === 1)
                delete sepet[id];
            else
                sepet[id] -= 1;
        }
        tutarHesapla();
    }

    function tutarHesapla() {
        let tutar = 0;
        for (const id in sepet)
            tutar += urunler[id]['fiyat'] * sepet[id];
        $(".tutar").text(tutar);
    }
</script>

</body>
</html>